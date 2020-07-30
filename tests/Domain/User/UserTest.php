<?php declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class UserTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            [1, 'bill.gates', 'Bill', 'Gates'],
            [2, 'steve.jobs', 'Steve', 'Jobs'],
            [3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'],
            [4, 'evan.spiegel', 'Evan', 'Spiegel'],
            [5, 'jack.dorsey', 'Jack', 'Dorsey'],
        ];
    }

    /**
     * @dataProvider userProvider
     */
    public function testGetters(int $id, string $username, string $first_name, string $last_name): void
    {
        $user = new User($id, $username, $first_name, $last_name);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($first_name, $user->getFirstName());
        $this->assertEquals($last_name, $user->getLastName());
    }

    /**
     * @dataProvider userProvider
     */
    public function testJsonSerialize(int $id, string $username, string $first_name, string $last_name): void
    {
        $user = new User($id, $username, $first_name, $last_name);

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
