<?php declare(strict_types=1);

namespace App\User\Infrastructure\Action;

use App\Common\Application\Actions\Action;
use App\User\Domain\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    public function __construct(
        LoggerInterface $logger,
        protected UserRepository $user_repository,
    ) {
        parent::__construct($logger);
    }
}
