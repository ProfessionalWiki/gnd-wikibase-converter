<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\MappingDeserializer;
use DNB\WikibaseConverter\PropertyMapping;
use DNB\WikibaseConverter\SubfieldCondition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\MappingDeserializer
 * @covers \DNB\WikibaseConverter\Mapping
 * @covers \DNB\WikibaseConverter\PropertyMapping
 */
class MappingDeserializerTest extends TestCase {

	public function testSimplePropertyMapping() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'029A' => [
				'P3' => [
					'subfields' => [ 'b' ]
				]
			]
		] );

		$this->assertEmpty( $mapping->getPropertyMappings( '404' ) );

		$this->assertEquals(
			[
				new PropertyMapping(
					propertyId: 'P3',
					subfields: [ 'b' ]
				)
			],
			$mapping->getPropertyMappings( '029A' )
		);
	}

	public function testPropertyMappingWithCondition() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'007K' => [
				'P2' => [
					'subfields' => [ '0' ],
					'conditions' => [
						[
							'subfield' => 'a',
							'equalTo' => 'gnd',
						]
					],
				]
			]
		] );

		$this->assertEquals(
			[
				new PropertyMapping(
					propertyId: 'P2',
					subfields: [ '0' ],
					condition: new SubfieldCondition( 'a', 'gnd' )
				)
			],
			$mapping->getPropertyMappings( '007K' )
		);
	}

	public function testValueMapping() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1C4' => [
				'P1' => [
					'subfields' => [ '0' ],
					'valueMap' => [
						'a' => [ 'id' => 'Q1', 'labels' => [ 'en' => 'AAA' ] ],
						'b' => [ 'id' => 'Q2', 'labels' => [ 'en' => 'BBB' ] ],
					]
				],
			]
		] );

		$this->assertEquals(
			new PropertyMapping(
				propertyId: 'P1',
				subfields: [ '0' ],
				valueMap: [ 'a' => 'Q1', 'b' => 'Q2' ]
			),
			$mapping->getPropertyMappings( 'P1C4' )[0]
		);
	}

}
