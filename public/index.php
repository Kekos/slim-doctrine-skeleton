<?php declare(strict_types=1);

use App\Application\Handlers\ProductionErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\Handlers\WhoopsErrorHandler;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;

require __DIR__ . '/../src/bootstrap.php';

$settings = $app->getContainer()->get('settings');

if ($settings['displayErrorDetails']) {
    $whoops = new WhoopsErrorHandler($settings['whoopsEditor']);
} else {
    $error_handler = new ProductionErrorHandler($app->getResponseFactory(), $app->getCallableResolver());
    $app->add($error_handler->getMiddleware());
}

// Create Request object from globals
$server_request_creator = ServerRequestCreatorFactory::create();
$request = $server_request_creator->createServerRequestFromGlobals();

// Register middleware
$middleware = require __DIR__ . '/../config/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

if (isset($error_handler)) {
    // Create Shutdown Handler
    $shutdown_handler = new ShutdownHandler($request, $error_handler->getErrorHandler());
    register_shutdown_function($shutdown_handler);
}

// Add Routing Middleware
$app->addRoutingMiddleware();

// Run App & Emit Response
$response = $app->handle($request);
$response_emitter = new ResponseEmitter();
$response_emitter->emit($response);
