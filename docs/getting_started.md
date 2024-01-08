

# Getting Started

To start, you need a script that will initialize the package and run any necessary CLI command.  An example of this script can be found at /vendor/bin/apex-cli on your machine.

For example, create a file named `my-cli` or 'my-cli.php' if using Windows, within the same directory the /vendor/ directory resides with the following contents:

~~~php
#!/usr/bin/env php
<?php

// Define array of root namespaces all CLI commands reside within.
$cmd_namespace = [
    "App\\Console"
];

// Load composer
require_once('./vendor/autoload.php');

// Run CLI command
$cli = new \Apex\Cli\Cli($cmd_namespace);
$cli->run();
~~~

The only modification to the above code that needs to be made is modifying the `$cmd_namespace` array on line 5.  Every CLI command is a separate PHP class and routed based on file / directory structure.  For example, if the namespace `App\Controler\` pointed to the directory `/app/Console/`', and you wanted the following command to work:

~~~bash
./my-cli account register
~~~

You would create a file located at `/app/Console/Account/Register.php`, so the class would exist at `App\Console\Account\Register`.  The `cmd_namespace` array above is simply which namespace(s) will be used to search for the PHP classes.  You may specify multiple namespaces within the array which will be checked sequentially for a CLI command class.

Last, ensure the script is executable by changing its permissions to 0755 with the command:

~~~bash
chmod 07555 my-cli
~~~


#### /vendor/bin/apex-cli

If preferred, you may use the script included in this page at /vendor/bin/apex-cli as it also allows you to place the script within your environment path such as /usr/local/bin/ so it can be executed anywhere on your server as long as you're within the correct directory of the installed software.


## Help Index Class

For the package to work correctly, you must have a `Help.php` class within each root namespace defined above.  Using the above example, you would need a file at /app/Console/Help.php, which provides a base summary of all available CLI commands.  For example:

~~~php
<?php
declare(strict_types=1);

namespace App\Console;

use Apex\Cli\{Cli, CliHelpScreen};

/**
 * Help
 * 
 * A Help class within the root namespace of commands is required 
 * in order for the apex/cli package to work.
 */
class Help
{

    /**
     * Help
     */
    public function help(Cli $cli):CliHelpscreen
    {

        // Start help
        $help = new CliHelpScreen(
            title: 'Help Screen Example',
            usage: './apex-cli <COMMAND> [ARGUMENTS] [FLAGS]',
            description: 'Example CLI help screen.  This Help.php class is required within the root namespace for this package to work, but should be modified to give an outline of the commands available within your application.'
        );
        $help->setParamsTitle('AVAILABLE COMMANDS');

        // Add commands
        $help->addParam('account', "Register, list and delete accounts.");
        $help->addParam('hello', "Get a greeting");


        // Return
        return $help;
    }

}
~~~

If running the above script either without specifying a command, with `help` as the command, or with an invalid command this help screen will be displayed.  For full information on the `CliHelpScreen` class, please visit the [Help Screen](help_screen.md) page of the documentation.  


## Create CLI Commands

You may now begin creating the actual classes for each CLI command.  To continue, visit the [Create CLI Commands](create.md) page of the documentation.


