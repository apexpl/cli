<?php
declare(strict_types = 1);

use Apex\Cli\Cli;
use PHPUnit\Framework\TestCase;

/**
 * Router tests
 */
class ShortcutTest extends TestCase
{

    /**
     * Test flags
     */
    public function testShortcuts():void
    {

        // Set shortcuts
        $shortcuts = [
            'acct' => 'account',
            'account reg' => 'account register',
            'register' => 'account register'
        ];

        // Start cli
        $cli = new Cli("Apex\\Cli\\Examples");
        $cli->addShortcuts($shortcuts);

        // Test 'acct reg'
        $args = $cli->applyShortcuts(['acct', 'reg']);
        $this->assertEquals('account', $args[0]);
        $this->assertEquals('register', $args[1]);

        // Test 'register'
        $args = $cli->applyShortcuts(['register']);
        $this->assertEquals('account', $args[0]);
        $this->assertEquals('register', $args[1]);

        // Test account reg
        $args = $cli->applyShortcuts(['account', 'reg']);
        $this->assertEquals('account', $args[0]);
        $this->assertEquals('register', $args[1]);

        // Test does not exist']);
        $args = $cli->applyShortcuts(['does', 'not', 'exist']);
        $this->assertEquals('does', $args[0]);
        //$this->assertEquals('not', $args[1]);
        $this->assertEquals('exist', $args[2]);
    }

}



