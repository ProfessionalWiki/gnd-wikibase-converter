<?php

declare( strict_types = 1 );

namespace DNB\Tests\Smoke;

use DNB\WikibaseConverter\PackagePrivate\MappingDeserializer;
use DNB\WikibaseConverter\PicaConverter;
use PHPUnit\Framework\TestCase;

class MappingJsonTest extends TestCase {

	public function testCanDeserialize(): void {
		$jsonString = PicaConverter::getMappingJson();
		$mapping = json_decode( $jsonString, true );

		$this->assertIsArray( $mapping );
		$this->assertArrayHasKey( 'P150', $mapping );
		$this->assertIsArray( $mapping['P150'] );
	}

	/**
	 * @depends testCanDeserialize
	 */
	public function testCanInstantiateObjects(): void {
		$mapping = ( new MappingDeserializer )->jsonArrayToObject( json_decode( PicaConverter::getMappingJson(), true ) );

		$this->assertNotEmpty( $mapping->getPropertyMappings( '003U' ) );
	}

}
