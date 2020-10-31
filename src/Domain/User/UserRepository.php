<?php declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @throws UserNotFoundException
     */
    public function findUserOfId(UserId $id): User;
}
