<?php

namespace Apex\Cli\Interfaces;

use Apex\Cli\{Cli, CliHelpScreen};

/**
 * Cli Command Interface
 */
interface CliCommandInterface
{

    /**
     * Process
     */
    public function process(Cli $cli, array $args):void;

    /**
     * Help
     */
    public function help(Cli $cli):CliHelpScreen;

}


