<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\Tests\Data;
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
		$mapping = ( new MappingDeserializer() )->jsonArrayToObject( Data::getMapping029A() );

		$this->assertEmpty( $mapping->getPropertyMappings( '404' ) );
		$this->assertCount( 1, $mapping->getPropertyMappings( '029A' ) );

		$this->assertEquals(
			new PropertyMapping(
				propertyId: 'P3',
				propertyType: 'string',
			),
			$mapping->getPropertyMappings( '029A' )['P3']
		);
	}

}
