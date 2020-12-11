<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\MappingDeserializer;
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
					'type' => 'string',

					'subfields' => [ 'b' ]
				]
			]
		] );

		$this->assertEmpty( $mapping->getPropertyMappings( '404' ) );
		$this->assertCount( 1, $mapping->getPropertyMappings( '029A' ) );

		$this->assertEquals(
			new PropertyMapping(
				propertyId: 'P3',
				propertyType: 'string',
				subfields: [ 'b' ]
			),
			$mapping->getPropertyMappings( '029A' )['P3']
		);
	}

}
