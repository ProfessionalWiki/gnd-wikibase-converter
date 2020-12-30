<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\PropertyMapping;
use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use DNB\WikibaseConverter\PropertyWithValues;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyMapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\SubfieldCondition
 */
class PropertyMappingTest extends TestCase {

	public function testNoSubfieldsLeadsToNoValues() {
		$mapping = new PropertyMapping(
			'P1',
			[ 'a' ],
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
			'P1',
			[ 'b' ],
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
			'P1',
			[ '0' ],
			null,
			new SubfieldCondition( 'a', 'gnd' )
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
			'P1',
			[ '0' ],
			null,
			new SubfieldCondition( 'a', 'gnd' )
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

	public function testValueMapping() {
		$mapping = new PropertyMapping(
			'P1',
			[ '0' ],
			null,
			null,
			[
				'a' => 'AAA',
				'b' => 'BBB'
			]
		);

		$subfields = [
			[ 'name' => '0', 'value' => 'a' ],
			[ 'name' => '0', 'value' => 'should be skipped' ],
			[ 'name' => '0', 'value' => 'b' ],
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[ 'AAA', 'BBB' ]
			),
			$mapping->convert( $subfields )
		);
	}

	/**
	 * @dataProvider positionParameterProvider
	 */
	public function testPositionParameter( string $value, int $position, array $expected ) {
		$mapping = new PropertyMapping(
			'P1',
			[ '0' ],
			$position
		);

		$subfields = [
			[ 'name' => '0', 'value' => $value ],
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				$expected
			),
			$mapping->convert( $subfields )
		);
	}

	public function positionParameterProvider(): iterable {
		yield 'value is extracted' => [ 'abc', 2, [ 'b' ] ];
		yield 'value is extracted at start of string' => [ 'abc', 1, [ 'a' ] ];
		yield 'value is extracted at end of string' => [ 'abc', 3, [ 'c' ] ];
		yield 'position too low' => [ 'abc', 0, [] ];
		yield 'position too high' => [ 'abc', 4, [] ];
	}

}
