<?php declare(strict_types=1);

namespace App\User\Infrastructure\Persistence;

use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserNotFoundException;
use App\User\Domain\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DatabaseUserRepository implements UserRepository
{
    private readonly EntityRepository $repository;

    public function __construct(
        private readonly EntityManager $entity_manager,
    ) {
        $this->repository = $entity_manager->getRepository(User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(UserId $id): User
    {
        /** @var User $user */
        $user = $this->repository->find((string) $id);

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

    public function add(User $user): void
    {
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();
    }
}
