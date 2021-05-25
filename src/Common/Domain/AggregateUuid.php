<?php declare(strict_types=1);

namespace App\Common\Domain;

use Assert\Assertion;
use Assert\AssertionFailedException;

use function get_class;

trait AggregateUuid {

	private string $id;

	private function __construct() {
	}

	public function __toString(): string {
		return $this->id;
	}

	public function equals($other_id): bool {
		return (
			get_class($other_id) === get_class($this)
			&& (string) $other_id === (string) $this
		);
	}

    /**
     * @param string $id
     * @return static
     * @throws AssertionFailedException
     */
	public static function fromString(string $id) {
		Assertion::uuid($id);

		$object_id = new static();
		$object_id->id = $id;

		return $object_id;
	}
}