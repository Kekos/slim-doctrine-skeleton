<?php declare(strict_types=1);

namespace App\Common\Domain;

use Assert\Assertion;
use Assert\AssertionFailedException;

trait AggregateUuid
{

    private string $id;

    private function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals($other_id): bool
    {
        return (
            $other_id::class === $this::class
            && (string) $other_id === (string) $this
        );
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $id): static
    {
        Assertion::uuid($id);

        $object_id = new static();
        $object_id->id = $id;

        return $object_id;
    }
}