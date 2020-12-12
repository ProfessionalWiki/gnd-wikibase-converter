<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\MappingDeserializer;
use DNB\WikibaseConverter\PropertyDefinition;
use DNB\WikibaseConverter\PropertyDefinitionList;
use DNB\WikibaseConverter\PropertyMapping;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\MappingDeserializer
 * @covers \DNB\WikibaseConverter\Mapping
 * @covers \DNB\WikibaseConverter\PropertyMapping
 */
class MappingDeserializerTest extends TestCase {

	public function testDeserializesPropertyMapping() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'029A' => [
				'P3' => [
					'subfields' => [ 'b' ]
				]
			]
		] );

		$this->assertEmpty( $mapping->getFieldMapping( '404' )->propertyMappings );

		$this->assertEquals(
			[
				new PropertyMapping(
					propertyId: 'P3',
					subfields: [ 'b' ]
				)
			],
			$mapping->getFieldMapping( '029A' )->propertyMappings
		);
	}

	public function testSimplePropertyDefinition() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1C4' => [
				'P1' => [
					'type' => 'string'
				],
			]
		] );

		$this->assertEquals(
			new PropertyDefinitionList(
				new PropertyDefinition( 'P1', 'string' )
			),
			$mapping->getProperties()
		);
	}

	public function testMultiplePropertyDefinitions() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1C4' => [
				'P1' => [
					'type' => 'string'
				],
				'P2' => [
					'type' => 'string'
				],
			],
			'M0R3' => [
				'P3' => [
					'type' => 'string'
				],
			]
		] );

		$this->assertEquals(
			new PropertyDefinitionList(
				new PropertyDefinition( 'P1', 'string' ),
				new PropertyDefinition( 'P2', 'string' ),
				new PropertyDefinition( 'P3', 'string' ),
			),
			$mapping->getProperties()
		);
	}

	public function testLabels() {
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( [
			'P1C4' => [
				'P1' => [
					'type' => 'string',
					'labels' => [ 'en' => 'English', 'de' => 'German' ]
				],
			]
		] );

		$this->assertEquals(
			new PropertyDefinitionList(
				new PropertyDefinition(
					'P1',
					'string',
					labels: [ 'en' => 'English', 'de' => 'German' ]
				)
			),
			$mapping->getProperties()
		);
	}

}
