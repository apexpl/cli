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

