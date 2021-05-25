<?php declare(strict_types=1);

namespace App\User\Domain;

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

    public function add(User $user): void;
}
