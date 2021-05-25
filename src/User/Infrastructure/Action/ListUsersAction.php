<?php declare(strict_types=1);

namespace App\User\Infrastructure\Action;

use Psr\Http\Message\ResponseInterface as Response;

class ListUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $users = $this->user_repository->findAll();

        $this->logger->info('Users list was viewed.');

        return $this->respondWithData($users);
    }
}
