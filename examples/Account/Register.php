<?php
declare(strict_types=1);

namespace Apex\Cli\Examples\Account;

use Apex\Cli\{Cli, CliHelpScreen};
use Apex\Cli\Interfaces\CliCommandInterface;

/**
 * Register account
 */
class Register implements CliCommandInterface
{

    /**
     * Process
     */
    public function process(Cli $cli, array $args):void 
    {

        //? Get args
        $opt = $cli->getArgs(['email','password']);
        $username = $args[0] ?? '';
        if ($username == '') {
            $cli->error("You did not specify a username.");
            return;
        }

        // Get e-mail / password
        $email = $opt['email'] ?? 'undefined';
        $password = $opt['password'] ?? 'undefined';

        // Success
        $cli->sendHeader('Account Registered');
        $cli->send("Successfully registered user '$username' with email '$email' and password '$password'.\n\n");
    }

    /**
     * Help
     */
    public function help(Cli $cli):CliHelpScreen
    {

        $help = new CliHelpScreen(
        title: 'Register Account',
        usage: './apex-cli account register <USERNAME> [--email <EMAIL>] [--password <PASSWORD]',
        description: 'Register a new account within the system.'
    );

        $help->addParam('username', 'The username to register.');
        $help->addFlag('--email', 'Optional e-mail address of account.');
        $help->addFlag('--password', 'Optional password of account.');
        $help->addExample('./apex-cli account register matt --email my@domain.com pp-assword secret');

        return $help;
    }


}

