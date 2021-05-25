<?php declare(strict_types=1);

namespace App\User\Infrastructure\Action;

use App\User\Domain\UserId;
use Psr\Http\Message\ResponseInterface as Response;

use function sprintf;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $user_id = $this->resolveArg('id');
        $user = $this->user_repository->findUserOfId(
            UserId::fromString($user_id),
        );

        $this->logger->info(sprintf('User of id `%s` was viewed.', $user_id));

        return $this->respondWithData($user);
    }
}
