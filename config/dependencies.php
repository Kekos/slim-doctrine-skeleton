<?php declare(strict_types=1);

use App\Common\Infrastructure\Doctrine\MySqlQuoteStrategy;
use App\User\Infrastructure\Type\UserIdType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Configuration\Migration\ConfigurationLoader as DoctrineConfigurationLoader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Kekos\DoctrineConsoleFactory\MigrationsConfigurationLoader;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;

use function DI\autowire;

return [
    RequestFactoryInterface::class => autowire(Psr17Factory::class),
    ResponseFactoryInterface::class => autowire(Psr17Factory::class),
    ServerRequestFactoryInterface::class => autowire(Psr17Factory::class),
    StreamFactoryInterface::class => autowire(Psr17Factory::class),
    UploadedFileFactoryInterface::class => autowire(Psr17Factory::class),
    UriFactoryInterface::class => autowire(Psr17Factory::class),

    LoggerInterface::class => static function (ContainerInterface $c) {
        $settings = $c->get('settings');

        $logger_settings = $settings['logger'];
        $logger = new Logger($logger_settings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($logger_settings['path'], $logger_settings['level']);
        $logger->pushHandler($handler);

        return $logger;
    },

    EntityManager::class => static function (ContainerInterface $c) {
        $settings = $c->get('settings')['doctrine'];
        $is_dev = ($_SERVER['APP_ENV'] !== 'production');
        $proxy_dir = null;

        if (!$is_dev) {
            $proxy_dir = $settings['proxy_dir'];
        }

        Type::addType(UserIdType::USER_ID, UserIdType::class);

        $config = ORMSetup::createXMLMetadataConfiguration([$settings['metadata_dir']], $is_dev, $proxy_dir);
        $config->setQuoteStrategy(new MySqlQuoteStrategy());

        if (!($database_url = $_SERVER['DATABASE_URL'])) {
            throw new RuntimeException('Missing `DATABASE_URL`');
        }

        $dsn_parser = new DsnParser(['mysql' => 'pdo_mysql']);
        $db_params = $dsn_parser->parse($database_url);
        $conn = DriverManager::getConnection($db_params, $config);

        return new EntityManager($conn, $config);
    },

    DoctrineConfigurationLoader::class => static function (ContainerInterface $c) {
        $settings = $c->get('settings')['doctrine']['migrations'];

        return new MigrationsConfigurationLoader($settings);
    },
];
