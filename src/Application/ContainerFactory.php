<?php declare(strict_types=1);

namespace App\Application;

use DI\Container;
use DI\ContainerBuilder;

use function dirname;

class ContainerFactory
{
    public static function create(): Container
    {
        $builder = new ContainerBuilder();
        $root = dirname(__DIR__, 2);

        if ($_SERVER['APP_ENV'] === 'production') {
            $builder->enableCompilation($root . '/var/cache');
        }

        $builder->addDefinitions($root . '/config/settings.php');
        $builder->addDefinitions($root . '/config/dependencies.php');
        $builder->addDefinitions($root . '/config/repositories.php');

        return $builder->build();
    }
}
