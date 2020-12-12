<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PropertyMapping;
use DNB\WikibaseConverter\PropertyWithValues;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PropertyMapping
 */
class PropertyMappingTest extends TestCase {

	public function testNoSubfieldsLeadsToNoValues() {
		$mapping = new PropertyMapping(
			propertyId: 'P1',
			subfields: [ 'a' ],
		);

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[]
			),
			$mapping->convert( [] )
		);
	}

	public function testOnySpecifiedSubfieldsAreUsed() {
		$mapping = new PropertyMapping(
			propertyId: 'P1',
			subfields: [ 'b' ],
		);

		$subfields = [
			[ 'name' => 'a', 'value' => 'AAA' ],
			[ 'name' => 'b', 'value' => 'BBB' ],
			[ 'name' => 'c', 'value' => 'CCC' ],
			[ 'name' => 'b', 'value' => 'B2' ],
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[ 'BBB', 'B2' ]
			),
			$mapping->convert( $subfields )
		);
	}

}
