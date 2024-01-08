
# Create CLI Commands

Every CLI command is contained within its own PHP class, and the command itself is routed based on the file location of the PHP class.  For example, if the root namespace points to the directory `/app/Console/` and you wish to create a command that can be executed with:

~~~bash
./my-cli hello
~~~

You would simply place a PHP class at `/app/Console/Hello.php`.  For another example, if you wish to create a CLI command at:

~~~bash
./my-cli account list-all
~~~

You would create a PHP class located at `/app/Console/Account/ListAll.php`.  When the above command is run in terminal, this PHP class would be instantiated and the `process()` method within executed.


## Example CLI Commands

You may view example CLI commands within the /examples/ directory of this package, and run them with the following commands within terminal:

~~~bash
vendor/bin/apex-cli hello <NAME>
vendor/bin/apex-cli help account
vendor/bin/apex-cli account list-all
vendor/bin/apex-cli account delete <USERNAME>
vendor/bin/apex-cli account register <USERNAME> [--email <EMAIL>] [--password <PASSWORD>]
~~~


## CliCommandInterface

Every PHP class that represents a CLI command should implement `Apex\Cli\Interfaces\CliCommandInterface`, the contents of which is:

~~~php
<?php

namespace Apex\Cli\Interfaces;

use Apex\Cli\{Cli, CliHelpScreen};

/**
 * Cli Command Interface
 */
interface CliCommandInterface
{

    /**
     * Process, exected when the command is run from terminal.
     */
    public function process(Cli $cli, array $args):void;

    /**
     * Help.  Displays uniformly styled help screen when command is run either with 
     * -h flag or 'help' as first argument in terminal command.
     */
    public function help(Cli $cli):CliHelpScreen;

}
~~~


## Example

Below shows the example class located within this package at /examples/Hello.php.

~~~php
<?php
declare(strict_types=1);

namespace Apex\Cli\Examples;

use Apex\Cli\{Cli, CliHelpScreen};
use Apex\Cli\Interfaces\CliCommandInterface;

/**
     * Example hellow class
     */
class Hello implements CliCommandInterface
{

    /**
     * Process
     */
    public function process(Cli $cli, array $args):void 
    {

        // Get options
        $opt = $cli->getArgs(['email']);
        $name = $args[0] ?? 'there';
        $email = $opt['email'] ?? 'unknown';

        // Send message
        $cli->send("Hi $name, hope you are enjoying your day.  Your e-mail address is $email.\n\n");
    }

    /**
     * Help
     */
    public function help(Cli $cli):CliHelpScreen
    {

        $help = new CliHelpScreen(
        title: 'Hello Example',
        usage: './apex-cli hello [<NAME>] [--email <EMAIL>]',
        description: 'Some description for the hello command'
    );

        // Add parameters
        $help->addParam('name', 'Some name to echo to termian.');

        // Add flags
        $help->addFlag('--email', 'Optional e-mail address to echo.');

        // Add examples
        $help->addExample('./apex-cli hello Matt --email me@domain.com');

        // Return
        return $help;
    }

}
~~~

As you can see from the `process()` method above, it checks the additional arguments passed for a name, and checks whether or not an `-s` flag is present and if an `--email` flag with value is present.


## Cli Helper Functions

Many methods are available within the `$cli` object allowing you to output word wrapped text, get user input, get a password, define new password, render a two column array or SQL styme data table, and more.  For a list of all available helper functions, please visit the [Apex\Cli\CLi Function Reference](./functions/) section of the documentation.


## Arguments and Flags

All non-flag arguments will be passed to the `process()` method as the one-dimensional `$args` array.  You may then use the [Cli::getArgs()](functions/getargs.md) method.  This methods accepts one array of any long flags (start with two hyphens --) that accept a value, and returns an associative array of all sort and long flags.

All short and long flags present that do not have a value attached to them will have a value of true.  Otherwise, for flags that do have values assigned, the value within the array will be its value.  For example, with the CLI command:

~~~bash
./my-cli some command arg1 arg2 -ae --filename /home/username/test.txt --title "My Test Title"
~~~

~~~
$opt = $cli->getArgs(['filename', 'title']);

$filename = $opt['filename'];  // /home/username/test.txt
$title = $opt['title'];    / "My Test Title"
$opt['a'];   /// true
$opt['e'];    // true
~~~


## Help Screen

Using the above example, you can see the `help()` method simply defines and returns an instance of the `CliHelpScreen` class, which is then rendered and output as a uniformly styled, well structured help screen providing all necessary information on the CLI command.  For full details, please visit the [Help Screen](help_screen.md) page of the documentation.


