<?php declare(strict_types=1);

namespace App\Application\Middleware;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Assert\LazyAssertionException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;

use function array_map;
use function json_encode;

use const JSON_THROW_ON_ERROR;

class ExceptionCatcherMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $response_factory;

    public function __construct(ResponseFactoryInterface $response_factory)
    {
        $this->response_factory = $response_factory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);

        } catch (AssertionFailedException $ex) {
            $response = $this->response_factory->createResponse(400)
                ->withHeader('Content-Type', 'application/json');

            $message = $ex->getMessage();
            $details = [];

            if ($ex instanceof LazyAssertionException) {
                $message = 'Assertions failed';
                $details = array_map(
                    fn(InvalidArgumentException $ex) => $ex->getMessage(),
                    $ex->getErrorExceptions(),
                );
            }

            $response->getBody()->write(
                json_encode(
                    [
                        'error' => $message,
                        'details' => $details,
                    ],
                    JSON_THROW_ON_ERROR,
                )
            );

        } catch (HttpException $ex) {
            $response = $this->response_factory->createResponse($ex->getCode())
                ->withHeader('Content-Type', 'application/json');

            $response->getBody()->write(
                json_encode(
                    [
                        'error' => $ex->getMessage(),
                    ],
                    JSON_THROW_ON_ERROR,
                )
            );
        }

        return $response;
    }
}
