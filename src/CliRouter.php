<?php
declare(strict_types = 1);

namespace Apex\Cli;

use Apex\Cli\Exceptions\CliHelpIndexNotExistsException;
use Symfony\Component\String\UnicodeString;

/**
 * CLI Router
 */
abstract class CliRouter extends Shortcuts
{

    // Properties
    public array $argv = [];
    public array $orig_argv = [];
    protected bool $is_help = false;
    protected ?string $rootdir;

    /**
     * Determine route
     *
     * @return string Fully qualified class name of CLI command.
     */
    public function determineRoute(array $args):?string
    {

        // Apply shortcuts
        $args = $this->applyShortcuts($args);
        $namespaces = is_array($this->cmd_namespace) ? $this->cmd_namespace : [$this->cmd_namespace];

        // GO through root namespaces
        $class_name = null;
        foreach ($namespaces as $root_namespace) {
            if ($class_name = $this->checkRootNamespace($args, $root_namespace)) {
                break;
            }
        }

        // Set help class, if needed
        if ($class_name === null && class_exists(rtrim($namespaces[0], "\\") . "\\Help")) {
            $class_name = rtrim($namespaces[0], "\\") . "\\Help";
            $this->is_help = true;
        }

        // Return
        return $class_name;
    }

    /**
     * Check root namespace
     */
    private function checkRootNamespace(array $args, string $root_namespace):?string
    {

        // Get args
        $this->orig_argv = $args;
        list($args, $opt) = $this->getArgs([], true);

        // Get root directory
        if (!$this->rootdir = $this->getRootDirectory($root_namespace)) {
            return null;
        }

        // Check for help
        list($this->is_help, $args) = $this->checkIsHelp($args, $opt);

        // Check for typos
        $tmp_args = $this->checkTypos($args);
        $passed_args = [];

        // Determine command
        $class_name = null;
        while (count($tmp_args) > 0) { 
            $chk_class = rtrim($root_namespace, "\\") . "\\" . implode("\\", array_map(fn ($a) => $this->convert_case($a, 'title'), $tmp_args));

            if (class_exists($chk_class)) { 
                $class_name = $chk_class;
                break;
            } elseif (class_exists($chk_class . "\\Help")) { 
                $class_name = $chk_class . "\\Help";
                $this->is_help = true;
                break;
            } else { 
                array_unshift($passed_args, array_pop($tmp_args));
            }
        }
        $this->argv = $passed_args;
        $this->args = $passed_args;

        // return
        return $class_name;
    }

    /**
     * Check is help
     */
    private function checkIsHelp(array $args, array $opt):array
    {

        // Check options
        $is_help = $opt['help'] ?? false;
        if (isset($opt['h']) && $opt['h'] === true) { 
            $is_help = true;
        }

        // Check for help
        $first = $args[0] ?? '';
        if ($first == 'help' || $first == 'h') { 
            $is_help = true;
            array_shift($args);
        }

        // Return
        return [$is_help, $args];
    }

    /**
     * Get command line arguments and options
     */
    public function getArgs(array $has_value = [], bool $return_args = false):array
    {

        // Initialize 
        $tmp_args = $this->orig_argv;
        list($args, $options) = [[], []];

        // Go through args
        while (count($tmp_args) > 0) { 
            $var = array_shift($tmp_args);

            // Long option with =
            if (preg_match("/^-{1,2}(\w+?)=(.+)$/", $var, $match)) { 
                $options[$match[1]] = $match[2];

            } elseif (preg_match("/^-{1,2}(.+)$/", $var, $match) && in_array($match[1], $has_value)) { 
                $value = isset($tmp_args[0]) ? array_shift($tmp_args) : '';
                if ($value == '=') { 
                    $value = isset($tmp_args[0]) ? array_shift($tmp_args) : '';
                }
                $options[$match[1]] = $value;

            } elseif (preg_match("/^--([a-zA-Z0-9_\-]+)/", $var, $match)) { 
                $options[$match[1]] = true;

            } elseif (preg_match("/^-(\w+)/", $var, $match)) { 
                $chars = str_split($match[1]);
                foreach ($chars as $char) { 
                    $options[$char] = true;
                }

            } else { 
                $args[] = $var;
            }
        }

        // Return
        if ($return_args === true) { 
            return array($args, $options);
        } else { 
            return $options;
        }
    }

    /**
     * Convert naming convention
     *
     * @param string $word The word / phrase to convert
     * @param string $case Naming convention to convert to.  Supported values are -- lower, upper, title, camel, phrase
     *
     * @return The converted word / string.
     */
    protected function convert_case(string $word, string $case = 'title'):string
    {

        // Get new case
        $word = new UnicodeString($word);
        $word = match ($case) { 
            'camel' => $word->camel(), 
            'title' => $word->camel()->title(), 
            'lower' => strtolower(preg_replace("/(.)([A-Z][a-z])/", '$1_$2', (string) $word)),
            'upper' => strtoupper(preg_replace("/(.)([A-Z][a-z])/", '$1_$2', (string) $word)), 
            'phrase' => ucwords(strtolower(preg_replace("/(.)([A-Z][a-z])/", '$1 $2', (string) $word->camel()))), 
            default => $word
        };

        // Return
        return (string) $word;
    }

    /**
     * Check for tyos within command name
     */
    protected function checkTypos(array $tmp_args):array
    {

        // Initialize

        // Go through command elements
        list($x, $has_typos, $args, $final_cmd) = [-1, false, [], []];
        foreach ($tmp_args as $arg) {
            $cmd = $this->convert_case($arg, 'title');
            $parent_dir = rtrim($this->rootdir . '/' . implode('/', $args), '/');
            $x++;

            // Check for .php file
            if (file_exists($parent_dir . '/' . $cmd . '.php')) {
                $final_cmd[] = $this->convert_case($arg);
                break;
            } elseif (is_dir($parent_dir . '/' . $cmd)) {
                $args[] = $cmd;
                $final_cmd[] = $this->convert_case($arg);
                continue;
            } elseif (!is_dir($parent_dir)) {
                break;
            }

            // Check levenshtein distance of files /ithin parent
            list($min_distance, $fixed_cmd, $is_file) = [null, null, false];
            $files = scandir($parent_dir);
            foreach ($files as $file) {
                if (str_starts_with($file, '.') || $file == 'Help.php') {
                    continue;
                }

                // Check distance
                $distance = levenshtein($cmd, preg_replace("/\.php$/", "", $file));
                if ($distance > 2) {
                    continue;
                } elseif ($min_distance === null || $distance < $min_distance) {
                    $min_distance = $distance;
                    $fixed_cmd = preg_replace("/\.php$/", "", $file);
                    $is_file = str_ends_with($file, '.php') ? true : false;
                    $has_typos = true;
                }
            }

            // Check if typo found
            if ($fixed_cmd === null) {
                continue;
            }
            $tmp_args[$x] = $fixed_cmd;
            $args[] = $fixed_cmd;

            $final_cmd[] = $this->convert_case($fixed_cmd, 'lower');
        }

        // Check if has typos
        if ($has_typos === true && $is_file === true && $this->autoconfirm_typos === false && !$this->getConfirm("No command exists with that name, but a similarly named command '" . implode(" ", $final_cmd) . "' does exist.  Did you want to run this command?")) {
            $this->send("\nOk, aborting.\n\n");
            exit(0);
        }

        // Return
        return $tmp_args;
    }

    /**
     * Get root directory
     */
    protected function getRootDirectory(string $root_namespace):?string
    {

        // Check for root help class
        $help_class = rtrim($root_namespace, "\\") . "\\Help";
        if (!class_exists($help_class)) {
            return null;
        }

        // Get directory name
        $obj = new \ReflectionClass($help_class);
        $rootdir = rtrim($obj->getFilename(), "/Help.php");

        // Return
        return $rootdir;
    }

}

