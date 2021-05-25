<?php declare(strict_types=1);

namespace Tests;

use App\Common\Application\ContainerFactory;
use Exception;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;

use function fopen;

class TestCase extends PHPUnit_TestCase
{
    /**
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
        $container = ContainerFactory::create();
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../config/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../config/routes.php';
        $routes($app);

        return $app;
    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ServerRequestInterface {
        $uri = new Uri($path);
        $handle = fopen('php://temp', 'wb+');
        $stream = (new Psr17Factory())->createStreamFromResource($handle);

        $request = new ServerRequest($method, $uri, $headers, $stream, '1.1', $serverParams);

        return $request->withCookieParams($cookies);
    }
}
