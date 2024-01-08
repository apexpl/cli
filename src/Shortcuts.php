<?php
declare(strict_types = 1);

namespace Apex\Cli;

/**
 * Cli Shortcuts
 */
abstract class Shortcuts
{

    // Properties
    private array $shortcuts = [];

    /**
     * Add shortcut
     */ 
    public function addShortcut(string $shortcut, string $cmd):void
    {

        // Initialize
        $args = explode(' ', $shortcut);
        $parent = '';

        // Go through args
        while (count($args) > 0) {
            $arg = array_shift($args);
            $chk = $parent == '' ? $arg : $parent . ' ' . $arg;
            $parent = $this->shortcuts[$chk] ?? $chk;
        }
        $this->shortcuts[$shortcut] = $cmd;
    }

    /**
     * Add multiple shortcuts
     */
    public function addShortcuts(array $shortcuts, ?string $parent = null):void
    {

        // Add shortcuts
        foreach ($shortcuts as $shortcut => $cmd) {
            $shortcut = $parent === null ? $shortcut : $parent . ' ' . $shortcut;
            $this->addShortcut($shortcut, $cmd);
        }

    }

    /**
     * Apply
     */
    public function applyShortcuts(array $args):array
    {

        // Check for zero
        if (count($args) == 0) { 
            return [];
        }

        // Check for help
        $is_help = false;
        if ($args[0] == 'help' || $args[0] == 'h') { 
            array_shift($args);
            $is_help = true;
        }

        // Go through args
        $parent = '';
        while (count($args) > 0) {
            $arg = array_shift($args);
            $chk = $parent == '' ? $arg : $parent . ' ' . $arg;
            $parent = $this->shortcuts[$chk] ?? $chk;
        }
        $args = explode(' ', $parent);

        // Add help, if needed
        if ($is_help === true) { 
            array_unshift($args, 'help');
        }

        // Return
        return $args;
    }

    /**
     8 Get shortcuts
     */
    public static function get(string $first, string $second):array
    {

        // Check
        $shortcuts = [];
        $commands = self::$second_level[$first] ?? [];
        $commands = array_flip($commands);

        // Check if command exists
        if (isset($commands[$second])) {

            // Replace first argumant, if possible.
            $top = array_flip(self::$top_level);
            $tmp_first = $top[$first] ?? $first;

            // Add to shortcuts
            $shortcuts[] = $tmp_first . ' ' . $commands[$second];
        }


        // Check ship top level
        foreach (self::$skip_top_level as $source => $dest) {

            // Check
            if ($dest[0] != $first || $dest[1] != $second) {
                continue;
            }
            $shortcuts[] = $source;
        }

        // Return
        return $shortcuts;
    }

}


