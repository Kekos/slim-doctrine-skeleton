<?php declare(strict_types=1);

namespace App\User\Infrastructure\Action;

use App\User\Domain\User;
use App\User\Domain\UserId;
use Assert\Assert;
use Psr\Http\Message\ResponseInterface as Response;

use function sprintf;
use function uuid_create;

use const UUID_TYPE_RANDOM;

class AddUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $data = (array) $this->request->getParsedBody();
        $user_id = UserId::fromString(uuid_create(UUID_TYPE_RANDOM));

        Assert::lazy()
            ->that($data)
            ->keyExists('username')
            ->that($data)
            ->keyExists('firstname')
            ->that($data)
            ->keyExists('lastname')
            ->verifyNow();

        Assert::lazy()
            ->that($data['username'])
            ->notEmpty()
            ->that($data['firstname'])
            ->notEmpty()
            ->that($data['lastname'])
            ->notEmpty()
            ->verifyNow();

        $user = new User(
            $user_id,
            $data['username'],
            $data['firstname'],
            $data['lastname'],
        );

        $this->user_repository->add($user);

        $this->logger->info(sprintf('User of id `%s` was created.', $user_id));

        return $this->respondWithData($user, 201);
    }
}
