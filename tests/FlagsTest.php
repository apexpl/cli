<?php
declare(strict_types = 1);

use Apex\Cli\Cli;
use PHPUnit\Framework\TestCase;

/**
 * Flags tests
 */
class FlagsTest extends TestCase
{

    /**
     * Test help
     */
    public function testHelp():void
    {

        // Init
        $cli = new Cli("Apex\\Cli\\Examples");
        $cli->addShortcut('acct', 'account');

        // Test
        $this->assertEquals($cli->determineRoute(['help', 'account']), "Apex\\Cli\\Examples\\Account\\Help");
        $this->assertEquals($cli->determineRoute(['-h', 'account']), "Apex\\Cli\\Examples\\Account\\Help");
        $this->assertEquals($cli->determineRoute(['--help', 'account']), "Apex\\Cli\\Examples\\Account\\Help");
        $this->assertEquals($cli->determineRoute(['acct', '-h']), "Apex\\Cli\\Examples\\Account\\Help");
        $this->assertEquals($cli->determineRoute(['accont', '-h']), "Apex\\Cli\\Examples\\Account\\Help");
        $this->assertEquals($cli->determineRoute(['help', 'acct']), "Apex\\Cli\\Examples\\Account\\Help");
        $this->assertEquals($cli->determineRoute(['help', 'accont']), "Apex\\Cli\\Examples\\Account\\Help");
    }

    /**
     * args
     */
    public function testArgs():void
    {

        // Init
        $cli = new Cli("Apex\\Cli\\Examples");
        $class = $cli->determineRoute(['account', 'register', 'matt', 'matt@apexpl.io', 'pass12345']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Check args
        $args = $cli->argv;
        $this->assertCount(3, $args);
        $this->assertEquals($args[0], 'matt');
        $this->assertEquals($args[1], 'matt@apexpl.io');
        $this->assertEquals($args[2], 'pass12345');
    }

    /**
     * Short flags and args
     */
    public function testShortFlags():void
    {

        // INit
        $cli = new Cli("Apex\\Cli\\Examples");
        $class = $cli->determineRoute(['account', 'register', '-agm', 'matt', 'matt@apexpl.io', '--repo', 'apexpl.io']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Get args and options
        $args = $cli->argv;
        $opt = $cli->getArgs(['repo']);

        // Check args
        $this->assertCount(3, $args);
        $this->assertEquals($args[0], 'matt');
        $this->assertEquals($args[1], 'matt@apexpl.io');
        $this->assertEquals($opt['repo'], 'apexpl.io');
        $this->assertTrue($opt['a']);
        $this->assertTrue($opt['g']);
        $this->assertTrue($opt['m']);
    }

}


