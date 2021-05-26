<?php declare(strict_types=1);

namespace Tests\User\Infrastructure\Action;

use App\Common\Application\Actions\ActionPayload;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\Persistence\InMemoryUserRepository;
use DI\Container;
use Tests\TestCase;

use function current;
use function json_encode;

use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class AddUserActionTest extends TestCase
{
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $post = [
            'username' => 'bill.gates',
            'firstname' => 'Bill',
            'lastname' => 'Gates',
        ];
        $user_repository = new InMemoryUserRepository([]);

        $container->set(UserRepository::class, $user_repository);

        $request = $this->createRequest('POST', '/users');
        $request = $request->withParsedBody($post);
        $response = $app->handle($request);

        $user = current($user_repository->findAll());

        $payload = (string) $response->getBody();
        $expected_payload = new ActionPayload(201, $user);
        $serialized_payload = json_encode($expected_payload, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        $this->assertEquals($serialized_payload, $payload);
    }
}
