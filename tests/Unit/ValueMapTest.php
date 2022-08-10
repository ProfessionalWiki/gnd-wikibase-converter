<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\ValueMap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\ValueMap
 */
class ValueMapTest extends TestCase {

	public function testValueMappingReturnsMappedValue(): void {
		$mapping = new ValueMap(
			[
				'a' => 'AAA',
				'b' => 'BBB',
				'c' => 'CCC',
			]
		);

		$this->assertSame( 'BBB', $mapping->map( 'b' ) );
	}

	public function testValueMappingDoesNotReturnValueWhenThereIsNoMapping(): void {
		$mapping = new ValueMap(
			[
				'a' => 'AAA',
				'b' => 'BBB',
				'c' => 'CCC',
			]
		);

		$this->assertNull( $mapping->map( 'B' ) );
	}

}
