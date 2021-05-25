<?php declare(strict_types=1);

namespace App\Common\Application\Handlers;

use ErrorException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Interfaces\ErrorHandlerInterface;
use Slim\ResponseEmitter;

use function error_get_last;
use function ob_get_clean;

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

        $message = $error['message'];

        $exception = new HttpInternalServerErrorException(
            $this->request,
            $message,
            new ErrorException(
                $message,
                0,
                $error['type'],
                $error['file'],
                $error['line'],
            ),
        );

        $response = $this->error_handler->__invoke(
            $this->request,
            $exception,
            false,
            true,
            true,
        );

        ob_get_clean();

        $response_emitter = new ResponseEmitter();
        $response_emitter->emit($response);
    }
}
