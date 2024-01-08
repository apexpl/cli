<?php
declare(strict_types=1);

namespace Apex\Cli\Examples\Account;

use Apex\Cli\{Cli, CliHelpScreen};
use Apex\Cli\Interfaces\CliCommandInterface;

/**
 * Delete account
 */
class Delete implements CliCommandInterface
{

    /**
     * Process
     */
    public function process(Cli $cli, array $args):void 
    {

        //? Get args
        $username = $args[0] ?? '';
        if ($username == '') {
            $cli->error("You did not specify a username.");
            return;
        }

        // Confirm
        if (!$cli->getConfirm("Are you sure you wish to delete the account '$username'?")) {
            $cli->send("Aborting.\n\n");
            return;
        }

        // Success
        $cli->sendHeader('Account Deleted');
        $cli->send("Successfully deleted account with username '$username'.\n\n");
    }

    /**
     * Help
     */
    public function help(Cli $cli):CliHelpScreen
    {

        $help = new CliHelpScreen(
        title: 'Delete Account',
        usage: './apex-cli account delete <USERNAME>',
        description: 'Delete an account existin the system.'
    );

        $help->addParam('username', 'The username to delete');
        $help->addExample('./apex-cli account delete matt');

        return $help;
    }


}

