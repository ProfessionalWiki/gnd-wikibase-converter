<?php

declare( strict_types = 1 );

namespace DNB\Tests\Smoke;

use DNB\Tests\Files;
use DNB\WikibaseConverter\PackagePrivate\MappingDeserializer;
use DNB\WikibaseConverter\PicaConverter;
use PHPUnit\Framework\TestCase;

class MappingJsonTest extends TestCase {

	public function testCanDeserialize() {
		$jsonString = PicaConverter::getMappingJson();
		$mapping = json_decode( $jsonString, true );

		$this->assertIsArray( $mapping );
		$this->assertArrayHasKey( '007K', $mapping );
		$this->assertIsArray( $mapping['007K'] );
	}

	/**
	 * @depends testCanDeserialize
	 */
	public function testCanInstantiateObjects() {
		$mapping = ( new MappingDeserializer )->jsonArrayToObject( json_decode( PicaConverter::getMappingJson(), true ) );

		$this->assertNotEmpty( $mapping->getPropertyMappings( '003U' ) );
	}

}
