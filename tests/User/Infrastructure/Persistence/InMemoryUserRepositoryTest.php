<?php declare(strict_types=1);

namespace Tests\User\Infrastructure\Persistence;

use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserNotFoundException;
use App\User\Infrastructure\Persistence\InMemoryUserRepository;
use Tests\TestCase;

use function array_values;

class InMemoryUserRepositoryTest extends TestCase
{
    public function testFindAll(): void
    {
        $user = new User(
            UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7'),
            'bill.gates',
            'Bill',
            'Gates',
        );

        $user_repository = new InMemoryUserRepository([1 => $user]);

        $this->assertEquals([$user], $user_repository->findAll());
    }

    public function testFindAllUsersByDefault(): void
    {
        $users = [
            new User(UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7'), 'bill.gates', 'Bill', 'Gates'),
            new User(UserId::fromString('e5df306c-5060-4daa-bbe6-c46fd7c18e6d'), 'steve.jobs', 'Steve', 'Jobs'),
            new User(UserId::fromString('42b2485a-f753-49e4-9489-166bf7b5c0f2'), 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            new User(UserId::fromString('6188b4c1-3ae4-4ad2-b0df-990e8c94bc97'), 'evan.spiegel', 'Evan', 'Spiegel'),
            new User(UserId::fromString('6d95f2b5-2b52-4ac4-9ef2-16974638525f'), 'jack.dorsey', 'Jack', 'Dorsey'),
        ];

        $user_repository = new InMemoryUserRepository();

        $this->assertEquals(array_values($users), $user_repository->findAll());
    }

    public function testFindUserOfId(): void
    {
        $id = UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7');
        $user = new User(
            $id,
            'bill.gates',
            'Bill',
            'Gates',
        );

        $user_repository = new InMemoryUserRepository([$user]);

        $this->assertEquals($user, $user_repository->findUserOfId($id));
    }

    public function testFindUserOfIdThrowsNotFoundException(): void
    {
        $user_repository = new InMemoryUserRepository([]);
        $this->expectException(UserNotFoundException::class);
        $user_repository->findUserOfId(
            UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7'),
        );
    }

    public function testAdd(): void
    {
        $user_repository = new InMemoryUserRepository([]);
        $id = UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7');
        $user = new User(
            $id,
            'bill.gates',
            'Bill',
            'Gates',
        );

        $user_repository->add($user);

        $this->assertEquals(
            $user,
            $user_repository->findUserOfId($id),
        );
    }
}
