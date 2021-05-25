<?php declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Type;

use App\User\Domain\UserId;

final class UserIdType extends AbstractUuidType
{
    public const USER_ID = 'user_id';

    public function getName(): string
    {
        return self::USER_ID;
    }

    protected function createValueObjectFromValue($value): object
    {
        return UserId::fromString($value);
    }

    protected function getValueObjectClassName(): string
    {
        return UserId::class;
    }
}
