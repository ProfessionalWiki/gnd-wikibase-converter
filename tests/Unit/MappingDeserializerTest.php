<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\InvalidMapping;
use DNB\WikibaseConverter\PackagePrivate\MappingDeserializer;
use DNB\WikibaseConverter\PackagePrivate\PropertyMapping;
use DNB\WikibaseConverter\PackagePrivate\QualifierMapping;
use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use DNB\WikibaseConverter\PackagePrivate\ValueMap;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\MappingDeserializer
 * @covers \DNB\WikibaseConverter\PackagePrivate\Mapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyMapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource
 */
class MappingDeserializerTest extends TestCase {

	public function testSimplePropertyMapping(): void {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P3' => [
				'field' => '029A',
				'subfield' => 'b'
			]
		] );

		$this->assertEmpty( $mapping->getPropertyMappings( '404' ) );

		$this->assertEquals(
			[
				new PropertyMapping(
					'P3',
					new SingleSubfieldSource( 'b' )
				)
			],
			$mapping->getPropertyMappings( '029A' )
		);
	}

	public function testPropertyMappingWithCondition(): void {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P2' => [
				'field' => '007K',
				'subfield' => '0',
				'condition' => [
					'subfield' => 'a',
					'equalTo' => 'gnd',
				],
			]
		] );

		$this->assertEquals(
			[
				new PropertyMapping(
					'P2',
					new SingleSubfieldSource( '0' ),
					new SubfieldCondition( 'a', 'gnd' )
				)
			],
			$mapping->getPropertyMappings( '007K' )
		);
	}

	public function testValueMapping(): void {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => '0',
				'valueMap' => [
					'a' => 'Q1',
					'b' => 'Q2',
				]
			],
		] );

		$this->assertEquals(
			new PropertyMapping(
				'P1',
				new SingleSubfieldSource( '0' ),
				null,
				new ValueMap( [ 'a' => 'Q1', 'b' => 'Q2' ] )
			),
			$mapping->getPropertyMappings( 'P1C4' )[0]
		);
	}

	public function testPosition(): void {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => '0',
				'position' => 42
			],
		] );

		$this->assertEquals(
			new PropertyMapping(
				'P1',
				new SingleSubfieldSource( '0', 42 )
			),
			$mapping->getPropertyMappings( 'P1C4' )[0]
		);
	}

	public function testPositionPlusConcatMapResultsInException(): void {
		$this->expectException( InvalidMapping::class );

		( new MappingDeserializer() )->jsonArrayToObject( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => [
					'a' => '$'
				],
				'position' => 42
			],
		] );
	}

	public function testMultipleFieldsToOneProperty(): void {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( json_decode(
			<<<EOD
{
	"P29": [
		{
			"_": "Früherer Name",
			"field": "028@",
			"subfield": "a"
		},
		{
			"field": "029@",
			"subfield": "b"
		}
	]
}
EOD,
			true
		) );

		$this->assertEquals(
			new PropertyMapping('P29', new SingleSubfieldSource( 'a' ) ),
			$mapping->getPropertyMappings( '028@' )[0]
		);

		$this->assertEquals(
			new PropertyMapping('P29', new SingleSubfieldSource( 'b' ) ),
			$mapping->getPropertyMappings( '029@' )[0]
		);
	}

	public function testQualifiers(): void {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => '0',
				'qualifiers' => [
					'P50' => 'b',
					'P51' => [
						'subfield' => 'x'
					],
					'P52' => [
						'subfield' => 'y',
						'position' => 2,
					],
					'P53' => [
						'subfield' => 'z',
						'valueMap' => [
							'foo' => 'Q1',
							'bar' => 'Q2',
						],
					]
				]
			],
		] );

		$this->assertEquals(
			new PropertyMapping(
				'P1',
				new SingleSubfieldSource( '0' ),
				null,
				null,
				[
					new QualifierMapping( 'P50', new SingleSubfieldSource( 'b' ) ),
					new QualifierMapping( 'P51', new SingleSubfieldSource( 'x' ) ),
					new QualifierMapping( 'P52', new SingleSubfieldSource( 'y', 2 ) ),
					new QualifierMapping( 'P53', new SingleSubfieldSource( 'z' ), new ValueMap( [ 'foo' => 'Q1', 'bar' => 'Q2' ] ) ),
				]
			),
			$mapping->getPropertyMappings( 'P1C4' )[0]
		);
	}

}
