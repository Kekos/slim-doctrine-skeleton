<?php declare(strict_types=1);

namespace Tests\Common\Domain;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Common\Domain\Fixtures\TestAggregateUuid;

final class AggregateUuidTest extends TestCase
{
	public function testFromStringToString(): void {
		$id = 'efbeeba1-c717-4a3f-862c-a7e514f82e23';
		$aggregate_id = TestAggregateUuid::fromString($id);

		$this->assertEquals($id, (string) $aggregate_id);
    }

	public function testAssertUuid(): void {
		$this->expectException(InvalidArgumentException::class);

		TestAggregateUuid::fromString('not a UUID');
    }

	public function testEqualsTrue(): void {
		$id1 = TestAggregateUuid::fromString('efbeeba1-c717-4a3f-862c-a7e514f82e23');
		$id2 = TestAggregateUuid::fromString('efbeeba1-c717-4a3f-862c-a7e514f82e23');

		$this->assertTrue($id1->equals($id2));
    }

	public function testEqualsFalse(): void {
		$id1 = TestAggregateUuid::fromString('efbeeba1-c717-4a3f-862c-a7e514f82e23');
		$id2 = TestAggregateUuid::fromString('d34b41cd-fb6a-4110-8b9a-41f11c835905');

		$this->assertFalse($id1->equals($id2));
		$this->assertFalse($id1->equals(new stdClass()));
    }
}
