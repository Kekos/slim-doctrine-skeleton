<?php declare(strict_types=1);

namespace Tests\User\Domain;

use App\User\Domain\User;
use App\User\Domain\UserId;
use Tests\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class UserTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            ['d15381dd-3a2b-41b8-9a31-b17cd40ed0c7', 'bill.gates', 'Bill', 'Gates'],
            ['e5df306c-5060-4daa-bbe6-c46fd7c18e6d', 'steve.jobs', 'Steve', 'Jobs'],
            ['42b2485a-f753-49e4-9489-166bf7b5c0f2', 'mark.zuckerberg', 'Mark', 'Zuckerberg'],
            ['6188b4c1-3ae4-4ad2-b0df-990e8c94bc97', 'evan.spiegel', 'Evan', 'Spiegel'],
            ['6d95f2b5-2b52-4ac4-9ef2-16974638525f', 'jack.dorsey', 'Jack', 'Dorsey'],
        ];
    }

    /**
     * @dataProvider userProvider
     */
    public function testGetters(string $id, string $username, string $first_name, string $last_name): void
    {
        $user = new User(UserId::fromString($id), $username, $first_name, $last_name);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($first_name, $user->getFirstName());
        $this->assertEquals($last_name, $user->getLastName());
    }

    /**
     * @dataProvider userProvider
     */
    public function testJsonSerialize(string $id, string $username, string $first_name, string $last_name): void
    {
        $user = new User(UserId::fromString($id), $username, $first_name, $last_name);

        $expected_payload = json_encode(
            [
                'id' => $id,
                'username' => $username,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'created_time' => null,
            ], JSON_THROW_ON_ERROR
        );

        $this->assertEquals($expected_payload, json_encode($user, JSON_THROW_ON_ERROR));
    }
}
