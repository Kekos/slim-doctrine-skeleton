<?php declare(strict_types=1);

use App\User\Domain\UserRepository;
use App\User\Infrastructure\Persistence\DatabaseUserRepository;

use function DI\autowire;

return [
    UserRepository::class => autowire(DatabaseUserRepository::class),
];
