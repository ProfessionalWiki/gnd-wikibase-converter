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
					'subfields' => [ 'b' ]
				]
			]
		] );

		$this->assertEmpty( $mapping->getFieldMapping( '404' )->propertyMappings );
		$this->assertCount( 1, $mapping->getFieldMapping( '029A' )->propertyMappings );

		$this->assertEquals(
			new PropertyMapping(
				propertyId: 'P3',
				subfields: [ 'b' ]
			),
			$mapping->getFieldMapping( '029A' )->propertyMappings['P3']
		);
	}

}
