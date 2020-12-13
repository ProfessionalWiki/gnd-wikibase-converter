<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PropertyMapping;
use DNB\WikibaseConverter\PropertyWithValues;
use DNB\WikibaseConverter\SubfieldCondition;
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

	public function testEqualityConditionDoesNotMatch() {
		$mapping = new PropertyMapping(
			propertyId: 'P1',
			subfields: [ '0' ],
			condition: new SubfieldCondition( 'a', 'gnd' )
		);

		$subfields = [
			[ 'name' => 'a', 'value' => 'not gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[]
			),
			$mapping->convert( $subfields )
		);
	}

	public function testEqualityConditionMatches() {
		$mapping = new PropertyMapping(
			propertyId: 'P1',
			subfields: [ '0' ],
			condition: new SubfieldCondition( 'a', 'gnd' )
		);

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[ '42' ]
			),
			$mapping->convert( $subfields )
		);
	}

	public function testEqualityConditionWithoutMatchingSubfield() {
		$mapping = new PropertyMapping(
			propertyId: 'P1',
			subfields: [ '0' ],
			condition: new SubfieldCondition( 'does not exist', 'gnd' )
		);

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[]
			),
			$mapping->convert( $subfields )
		);
	}

}
