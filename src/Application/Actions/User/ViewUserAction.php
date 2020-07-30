<?php declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

use function sprintf;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->user_repository->findUserOfId($userId);

        $this->logger->info(sprintf('User of id `%s` was viewed.', $userId));

        return $this->respondWithData($user);
    }
}
