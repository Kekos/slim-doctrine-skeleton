<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseUserRepository implements UserRepository
{
    /** @var EntityRepository */
    private $repository;

    public function __construct(EntityManager $entity_manager)
    {
        $this->repository = $entity_manager->getRepository(User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        /** @var User $user */
        $user = $this->repository->find($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
