<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\PropertyMapping;
use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PropertyWithValues;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyMapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\SubfieldCondition
 */
class PropertyMappingTest extends TestCase {

	public function testNoSubfieldsLeadsToNoValues(): void {
		$mapping = new PropertyMapping(
			'P1',
			'a',
		);

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[]
			),
			$mapping->convert( Subfields::newFromMap( [] ) )
		);
	}

	public function testOnySpecifiedSubfieldIsUsed(): void {
		$mapping = new PropertyMapping(
			'P1',
			'b',
		);

		$subfields = [
			'a' => 'AAA',
			'b' => 'BBB',
			'c' => 'CCC',
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[ 'BBB' ]
			),
			$mapping->convert( Subfields::newFromMap( $subfields ) )
		);
	}

	public function testEqualityConditionDoesNotMatch(): void {
		$mapping = new PropertyMapping(
			'P1',
			'x',
			null,
			new SubfieldCondition( 'a', 'gnd' )
		);

		$subfields = [
			'a' => 'not gnd',
			'x' => '42',
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[]
			),
			$mapping->convert( Subfields::newFromMap( $subfields ) )
		);
	}

	public function testEqualityConditionMatches(): void {
		$mapping = new PropertyMapping(
			'P1',
			'x',
			null,
			new SubfieldCondition( 'a', 'gnd' )
		);

		$subfields = [
			'a' => 'gnd',
			'x' => '42',
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[ '42' ]
			),
			$mapping->convert( Subfields::newFromMap( $subfields ) )
		);
	}

	public function testValueMapping(): void {
		$mapping = new PropertyMapping(
			'P1',
			'x',
			null,
			null,
			[
				'a' => 'AAA',
				'b' => 'BBB',
				'c' => 'CCC',
			]
		);

		$subfields = [
			'x' => 'b',
			'z' => '42',
		];

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				[ 'BBB' ]
			),
			$mapping->convert( Subfields::newFromMap( $subfields ) )
		);
	}

	/**
	 * @dataProvider positionParameterProvider
	 */
	public function testPositionParameter( string $value, int $position, array $expected ): void {
		$mapping = new PropertyMapping(
			'P1',
			'x',
			$position
		);

		$this->assertEquals(
			new PropertyWithValues(
				'P1',
				$expected
			),
			$mapping->convert( Subfields::newFromMap( [ 'x' => $value ] ) )
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
