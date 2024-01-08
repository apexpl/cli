
# Define Shortcuts

During initialization of the `Apex\Cli\Cli` class via the command line script you created in the [Getting Started](getting_started.md) page, you may also define shortcuts.  This can be done via either the [Cli::addShortcut()](functions/addshortcut.md) or [Cli::addShortcuts()](functions/addshortcuts.md) commands.

For example, when instantiating the Cli class:

~~php

// Define shortcuts
$shortcuts = [
    'acct' => 'account',
    'account reg' => 'account register',
    'register' => 'account register'
];

// Run command
$cli = new \Apex\CLi\Cli($cmd_namespace, $autoconfirm_typos);
$cli->addShortcuts($shortcuts);
$cli->run();
~~~

With the above if you run the command `./my-cli acct delete` it will check for the command `./my-cli account delete`.  Plus the other two simply point to the `./my-cli account regisrer` command but in a shortened form.


