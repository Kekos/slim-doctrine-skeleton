<?php declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\UserId;
use App\Domain\User\UserRepository;
use App\Domain\User\User;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\Container;
use Tests\TestCase;

use function json_encode;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class ListUserActionTest extends TestCase
{
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(
            UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7'),
            'bill.gates',
            'Bill',
            'Gates',
        );
        $user_repository = new InMemoryUserRepository([$user]);

        $container->set(UserRepository::class, $user_repository);

        $request = $this->createRequest('GET', '/users');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expected_payload = new ActionPayload(200, [$user]);
        $serialized_payload = json_encode($expected_payload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        $this->assertEquals($serialized_payload, $payload);
    }
}
