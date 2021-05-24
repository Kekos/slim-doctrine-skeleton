<?php declare(strict_types=1);

namespace App\Common\Application\Handlers;

use ErrorException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\Middleware\ErrorMiddleware;

use function set_error_handler;

final class ProductionErrorHandler
{
    private ErrorHandlerInterface $error_handler;
    private ErrorMiddleware $middleware;

    public function __construct(
        ResponseFactoryInterface $response_factory,
        CallableResolverInterface $callable_resolver,
        LoggerInterface $logger
    ) {
        $this->error_handler = new ErrorHandler(
            $callable_resolver,
            $response_factory,
            $logger,
        );

        set_error_handler(static function ($level, $message, $file = null, $line = null) {
            throw new ErrorException($message, 0, $level, $file, $line);
        });

        $this->middleware = new ErrorMiddleware(
            $callable_resolver,
            $response_factory,
            false,
            true,
            true
        );
        $this->middleware->setDefaultErrorHandler($this->error_handler);
    }

    public function getMiddleware(): ErrorMiddleware
    {
        return $this->middleware;
    }

    public function getErrorHandler(): ErrorHandlerInterface
    {
        return $this->error_handler;
    }
}
