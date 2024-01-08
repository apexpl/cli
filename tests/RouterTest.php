<?php
declare(strict_types = 1);

use Apex\Cli\Cli;
use PHPUnit\Framework\TestCase;

/**
 * Router tests
 */
class RouterTest extends TestCase
{

    /**
     * Test account register
     */
    public function testAccountRegister():void
    {

        // Init
        $cli = new Cli("Apex\\Cli\\Examples");

        // Test 'accoun register'
        $class = $cli->determineRoute(['account', 'register']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Test 'account register' with args / flags
        $class = $cli->determineRoute(['account', 'register', 'matt', '--email', 'matt@apexpl.io', '-ro']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Test account help class
        $class = $cli->determineRoute(['account', 'junk', 'command']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Help");

        // Test base help class
        $class = $cli->determineRoute(['some', 'junk', 'command']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Help");
    }

    /**
     * No help class
     */
    public function testNoHelpIndex():void
    {
        $cli = new Cli();
        $class = $cli->determineRoute(['account register']);
        $this->assertNull($class);
    }

    /**
     * Shortcuts help
     */
    public function testShortcuts():void
    {

        // Init
        $cli = new Cli("Apex\\Cli\\Examples");
        $cli->addShortcuts([
            'acct' => 'account',
            'account reg' => 'account register',
            'register' => 'account register'
        ]);

        // Test acct reg
        $class = $cli->determineRoute(['acct', 'reg']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Test account reg
        $class = $cli->determineRoute(['account', 'reg']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Test register
        $class = $cli->determineRoute(['register']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Test account help
        $class = $cli->determineRoute(['acct', 'junk']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Help");
    }

    /**
     * Test typos
     */
    public function testTypos():void
    {

        // Init
        $cli = new Cli("Apex\\Cli\\Examples", true);

        // Test accon regie
        $class = $cli->determineRoute(['acont', 'reiste']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Register");

        // Test account help
        $class = $cli->determineRoute(['acont', 'rgitr']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Account\\Help");

        // Test too much difference
        $class = $cli->determineRoute(['acnt', 'rgitr']);
        $this->assertEquals($class, "Apex\\Cli\\Examples\\Help");
    }

}

