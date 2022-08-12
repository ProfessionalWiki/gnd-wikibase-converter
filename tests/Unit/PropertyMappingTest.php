<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\GndStatement;
use DNB\WikibaseConverter\PackagePrivate\PropertyMapping;
use DNB\WikibaseConverter\PackagePrivate\QualifierMapping;
use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PackagePrivate\ValueMap;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyMapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\SubfieldCondition
 * @covers \DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource
 * @covers \DNB\WikibaseConverter\PackagePrivate\Subfields
 */
class PropertyMappingTest extends TestCase {

	public function testNoSubfieldsLeadsToNoValues(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'a' )
		);

		$this->assertEquals(
			[],
			$mapping->convert( Subfields::fromSingleValueMap( [] ) )
		);
	}

	public function testOnySpecifiedSubfieldIsUsed(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'b' )
		);

		$subfields = [
			'a' => 'AAA',
			'b' => 'BBB',
			'c' => 'CCC',
		];

		$this->assertEquals(
			[
				new GndStatement(
					'P1',
					'BBB'
				)
			],
			$mapping->convert( Subfields::fromSingleValueMap( $subfields ) )
		);
	}

	public function testEqualityConditionDoesNotMatch(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			new SubfieldCondition( 'a', 'gnd' )
		);

		$subfields = [
			'a' => 'not gnd',
			'x' => '42',
		];

		$this->assertEquals(
			[],
			$mapping->convert( Subfields::fromSingleValueMap( $subfields ) )
		);
	}

	public function testEqualityConditionMatches(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			new SubfieldCondition( 'a', 'gnd' )
		);

		$subfields = [
			'a' => 'gnd',
			'x' => '42',
		];

		$this->assertEquals(
			[
				new GndStatement(
					'P1',
					'42'
				)
			],
			$mapping->convert( Subfields::fromSingleValueMap( $subfields ) )
		);
	}

	public function testValueMappingReturnsMappedValue(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			null,
			new ValueMap(
				[
					'a' => 'AAA',
					'b' => 'BBB',
					'c' => 'CCC',
				]
			)
		);

		$subfields = [
			'x' => 'b',
			'z' => '42',
		];

		$this->assertEquals(
			[
				new GndStatement(
					'P1',
					'BBB'
				)
			],
			$mapping->convert( Subfields::fromSingleValueMap( $subfields ) )
		);
	}

	public function testValueMappingDoesNotReturnValueWhenThereIsNoMapping(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			null,
			new ValueMap(
				[
					'foo' => 'bar'
				]
			)
		);

		$subfields = [
			'x' => 'b',
			'z' => '42',
		];

		$this->assertEquals(
			[],
			$mapping->convert( Subfields::fromSingleValueMap( $subfields ) )
		);
	}

	/**
	 * @dataProvider positionParameterProvider
	 */
	public function testPositionParameter( string $value, int $position, ?string $expected ): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x', $position )
		);

		$this->assertEquals(
			$expected === null ? [] : [
				new GndStatement(
					'P1',
					$expected
				)
			],
			$mapping->convert( Subfields::fromSingleValueMap( [ 'x' => $value ] ) )
		);
	}

	public function positionParameterProvider(): iterable {
		yield 'value is extracted' => [ 'abc', 2, 'b' ];
		yield 'value is extracted at start of string' => [ 'abc', 1, 'a' ];
		yield 'value is extracted at end of string' => [ 'abc', 3, 'c' ];
		yield 'position too low' => [ 'abc', 0, null ];
		yield 'position too high' => [ 'abc', 4, null ];
	}

}
