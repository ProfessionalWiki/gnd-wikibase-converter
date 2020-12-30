<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\EntityDefinitionReader;
use DNB\WikibaseConverter\PackagePrivate\PropertyDefinition;
use DNB\WikibaseConverter\PackagePrivate\PropertyDefinitionList;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\EntityDefinitionReader
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyDefinitionList
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyDefinition
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
