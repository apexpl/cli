<?php
declare(strict_types=1);

namespace Apex\Cli\Examples;

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

 
