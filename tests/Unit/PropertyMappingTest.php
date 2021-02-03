<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\PackagePrivate\PropertyMapping;
use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use DNB\WikibaseConverter\GndStatement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyMapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\SubfieldCondition
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
			[
				'foo' => 'bar'
			]
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

	public function testQualifiers(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			null,
			[],
			[
				'P50' => 'a',
				'P51' => 'b',
				'P52' => 'c',
			]
		);

		$this->assertEquals(
			[
				new GndStatement(
					'P1',
					'foo',
					[
						new GndQualifier( 'P50', 'AAA' ),
						new GndQualifier( 'P52', 'CCC' ),
					]
				)
			],
			$mapping->convert( Subfields::fromSingleValueMap( [
				'x' => 'foo',
				'c' => 'CCC',
				'a' => 'AAA',
			] ) )
		);
	}

}
