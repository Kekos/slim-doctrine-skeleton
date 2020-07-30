<?php declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\Container;
use Tests\TestCase;

use function json_encode;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class ViewUserActionTest extends TestCase
{
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');
        $user_repository = new InMemoryUserRepository([
            1 => $user,
        ]);

        $container->set(UserRepository::class, $user_repository);

        $request = $this->createRequest('GET', '/users/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expected_payload = new ActionPayload(200, $user);
        $serialized_payload = json_encode($expected_payload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        $this->assertEquals($serialized_payload, $payload);
    }
}
