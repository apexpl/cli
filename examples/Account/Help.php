<?php
declare(strict_types=1);

namespace Apex\Cli\Examples\Account;

use Apex\Cli\{Cli, CliHelpScreen};

/**
 * Help
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
            title: 'Account Commands',
            usage: './apex-cli account <COMMAND> <ARGUMENTS> [FLAGS]',
            description: 'Manage all accounts within the system.'
        );
        $help->setParamsTitle('COMMANDS');

        // Set commands
        $help->addParam('delete', 'Delete an account.');
        $help->addParam('list-all', 'List all accounts within system.');
        $help->addParam('register', 'Register a new account within system.');

        // Return
        return $help;
    }

}

 
