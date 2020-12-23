<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\EntityDefinitionReader;
use DNB\WikibaseConverter\PropertyDefinition;
use DNB\WikibaseConverter\PropertyDefinitionList;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\EntityDefinitionReader
 * @covers \DNB\WikibaseConverter\PropertyDefinitionList
 * @covers \DNB\WikibaseConverter\PropertyDefinition
 */
class EntityDefinitionReaderTest extends TestCase {

	public function testSimplePropertyDefinition() {
		$this->assertEquals(
			new PropertyDefinitionList(
				new PropertyDefinition( 'P1', 'string' )
			),
			$this->propertiesFromJson( [
				'P1C4' => [
					'P1' => [
						'type' => 'string'
					],
				]
			] )
		);
	}

	private function propertiesFromJson( array $json ): PropertyDefinitionList {
		return ( new EntityDefinitionReader() )->propertyDefinitionsFromJsonArray( $json );
	}

	public function testMultiplePropertyDefinitions() {

		$this->assertEquals(
			new PropertyDefinitionList(
				new PropertyDefinition( 'P1', 'string' ),
				new PropertyDefinition( 'P2', 'string' ),
				new PropertyDefinition( 'P3', 'string' ),
			),
			$this->propertiesFromJson( [
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
			] )
		);
	}

	public function testLabels() {
		$this->assertEquals(
			new PropertyDefinitionList(
				new PropertyDefinition(
					'P1',
					'string',
					[ 'en' => 'English', 'de' => 'German' ]
				)
			),
			$this->propertiesFromJson( [
				'P1C4' => [
					'P1' => [
						'type' => 'string',
						'labels' => [ 'en' => 'English', 'de' => 'German' ]
					],
				]
			] )
		);
	}

}
