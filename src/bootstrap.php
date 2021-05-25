<?php declare(strict_types=1);

use Dotenv\Dotenv;
use App\Application\ContainerFactory;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// The check is to ensure we don't use .env in production
if (empty($_SERVER['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "vlucas/phpdotenv" as a Composer dependency to load variables from a .env file.');
    }

    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

$container = ContainerFactory::create();

date_default_timezone_set($container->get('settings')['default_timezone']);

AppFactory::setContainer($container);
$app = AppFactory::create();
