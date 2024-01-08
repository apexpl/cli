<?php
declare(strict_types=1);

namespace Apex\Cli\Examples\Account;

use Apex\Cli\{Cli, CliHelpScreen};
use Apex\Cli\Interfaces\CliCommandInterface;

/**
 * Register account
 */
class ListAll implements CliCommandInterface
{

    /**
     * Process
     */
    public function process(Cli $cli, array $args):void 
    {

        // Set users, first row is column headers
        $users = [
            ['Username', 'Full Name', 'E-Mail'],
            ['matt', 'Matt Dizak', 'matt@apexpl.io'],
            ['jsmith', 'John Smith', 'john@example.com'],
            ['mary', 'Mary Gibbons', 'marray@domain.com'],
            ['mike', 'Mike Jacobs', 'mike@test.com']
        ];

        // Display table
        $cli->sendTable($users);
    }

    /**
     * Help
     */
    public function help(Cli $cli):CliHelpScreen
    {

        $help = new CliHelpScreen(
        title: 'List All Accounts',
        usage: './apex-cli account list-all',
        description: 'List all accounts within system.'
    );

        $help->addExample('./apex-cli account list-all');

        return $help;
    }


}

