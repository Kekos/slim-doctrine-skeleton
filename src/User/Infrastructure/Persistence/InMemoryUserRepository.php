<?php declare(strict_types=1);

namespace App\User\Infrastructure\Persistence;

use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserNotFoundException;
use App\User\Domain\UserRepository;

use Assert\AssertionFailedException;

use function array_values;

class InMemoryUserRepository implements UserRepository
{
    /** @var User[] */
    private array $users = [];

    /**
     * @param User[]|null $users
     * @throws AssertionFailedException
     */
    public function __construct(array $users = null)
    {
        $users = $users ?? [
            new User(UserId::fromString('d15381dd-3a2b-41b8-9a31-b17cd40ed0c7'), 'bill.gates', 'Bill', 'Gates'),
            new User(UserId::fromString('e5df306c-5060-4daa-bbe6-c46fd7c18e6d'), 'steve.jobs', 'Steve', 'Jobs'),
            new User(UserId::fromString('42b2485a-f753-49e4-9489-166bf7b5c0f2'), 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            new User(UserId::fromString('6188b4c1-3ae4-4ad2-b0df-990e8c94bc97'), 'evan.spiegel', 'Evan', 'Spiegel'),
            new User(UserId::fromString('6d95f2b5-2b52-4ac4-9ef2-16974638525f'), 'jack.dorsey', 'Jack', 'Dorsey'),
        ];

        foreach ($users as $user) {
            $this->users[(string) $user->getId()] = $user;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(UserId $id): User
    {
        $string_id = (string) $id;
        if (!isset($this->users[$string_id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$string_id];
    }

    public function add(User $user): void
    {
        $string_id = (string) $user->getId();
        $this->users[$string_id] = $user;
    }
}
