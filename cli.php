#!/usr/bin/env php
<?php declare(strict_types=1);

use Kekos\DoctrineConsoleFactory\DoctrineCommandFactory;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;
use function App\getVersion;

require __DIR__ . '/src/bootstrap.php';

$command_loader = new FactoryCommandLoader(
    [

    ]
);

$console_app = new SymfonyApplication('App', getVersion());
$console_app->setCommandLoader($command_loader);

$doctrine_command_factory = $container->get(DoctrineCommandFactory::class);
$doctrine_command_factory->addCommands($console_app);

$console_app->run();
