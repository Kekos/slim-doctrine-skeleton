<?php declare(strict_types=1);

namespace App\User\Infrastructure\Action;

use App\Common\Application\Actions\Action;
use App\User\Domain\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    protected UserRepository $user_repository;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        parent::__construct($logger);

        $this->user_repository = $userRepository;
    }
}
