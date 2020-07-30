<?php declare(strict_types=1);

use Monolog\Logger;

use function DI\env;

return [
    'settings' => [
        'displayErrorDetails' => true, // Should be set to false in production
        'whoopsEditor' => env('WHOOPS_EDITOR', null),
        'default_timezone' => 'Europe/Stockholm',

        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_SERVER['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => Logger::DEBUG,
        ],

        'doctrine' => [
            'proxy_dir' => dirname(__DIR__) . '/var/doctrine_proxy',
            'metadata_dir' => __DIR__ . '/entity_meta',
            'cache_dir' => dirname(__DIR__) . '/var/doctrine',

            'migrations' => [
                'migrations_paths' => [
                    'App\\Infrastructure\\Doctrine\\Migration' => dirname(__DIR__) . '/src/Infrastructure/Doctrine/Migration',
                ],
                'custom_template' => __DIR__ . '/doctrine_migrations_class.php.tpl',
            ],
        ],
    ],
];
