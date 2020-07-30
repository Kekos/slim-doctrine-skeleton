<?php declare(strict_types=1);

namespace App\Application\Handlers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\ResponseEmitter;

use function error_get_last;

class ShutdownHandler
{
    private Request $request;
    private ErrorHandlerInterface $error_handler;

    public function __construct(Request $request, ErrorHandlerInterface $error_handler)
    {
        $this->request = $request;
        $this->error_handler = $error_handler;
    }

    public function __invoke(): void
    {
        $error = error_get_last();
        if (!$error) {
            return;
        }

        $message = 'An error while processing your request. Please try again later.';

        $exception = new HttpInternalServerErrorException($this->request, $message);
        $response = $this->error_handler->__invoke(
            $this->request,
            $exception,
            false,
            false,
            false
        );

        $response_emitter = new ResponseEmitter();
        $response_emitter->emit($response);
    }
}
