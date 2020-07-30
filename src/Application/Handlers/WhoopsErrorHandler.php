<?php declare(strict_types=1);

namespace App\Application\Handlers;

use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as WhoopsRun;
use Whoops\Util\Misc as WhoopsMisc;

final class WhoopsErrorHandler
{
    public function __construct(string $editor = null)
    {
        $whoops = new WhoopsRun();

        if (WhoopsMisc::isCommandLine()) {
            $whoops->prependHandler(new PlainTextHandler());
        } else {
            $handler = new PrettyPageHandler();

            if ($editor !== null) {
                $handler->setEditor($editor);
            }

            $whoops->prependHandler($handler);
        }

        $whoops->register();
    }
}
