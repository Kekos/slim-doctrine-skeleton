<?php declare(strict_types=1);

use App\Application\Middleware\ExceptionCatcherMiddleware;
use Kekos\ParseRequestBodyMiddleware\ParseRequestBodyMiddleware;
use Slim\App;

return static function (App $app) {
    $container = $app->getContainer();
    if ($container === null) {
        throw new RuntimeException('Container not configured');
    }

    $app->addMiddleware($container->get(ParseRequestBodyMiddleware::class));
    $app->addMiddleware($container->get(ExceptionCatcherMiddleware::class));
};
