
# Help Screen

Every PHP class that pertains to a CLI command contains eth method:

~~~bash
public function help(Cli $cli):CliHelpScreen;
~~~

This provides the contents of the uniformly styled help screen for each CLI command.  For example, running the command:

~~~php
./vendor/bin/apex-cli help hello
~~~

This command will display the help screen for the 'hello' command, and the `help()` function within the `Hello.php` class is:

~~~php
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
    $help->addParam('name', 'Some name to echo to terminal.');

    // Add flags
    $help->addFlag('--email', 'Optional e-mail address to echo.');

    // Add examples
    $help->addExample('./apex-cli hello Matt --email me@domain.com');

    // Return
    return $help;
}
~~~

It first instantiates the `CliHelpScreen` object with some general properties such as title, usage and descriptoin.  Then is adds the necessary information within the parameters, flags and examples sections of the help screen, and last returns the object.


## Help Index

Within every directory where your CLI commands are stored you should add a Help.php class which explains the sub-commands within the directory.  for example, if you run the command:

~~~bash
./vendor/bin/apex-cli help account
~~~

It will display the help screen contents contained within the file at /examples/Account/Help.php.


## CliHelpScreen Functions

You may view a list of all available functions by visiting the [CLIHelpScreen Reference](functions/help_screen/index.md) section of the documentation.



