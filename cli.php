#!/usr/bin/env php
<?php declare(strict_types=1);

use Kekos\DoctrineConsoleFactory\DoctrineCommandFactory;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

require __DIR__ . '/src/bootstrap.php';

$command_loader = new FactoryCommandLoader(
    [

    ]
);

$console_app = new SymfonyApplication('App', '1.0.0');
$console_app->setCommandLoader($command_loader);

$doctrine_command_factory = $container->get(DoctrineCommandFactory::class);
$doctrine_command_factory->addCommands($console_app);

$console_app->run();
