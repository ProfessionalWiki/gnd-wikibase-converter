<?php

declare( strict_types = 1 );

namespace DNB\Tests\Smoke;

use DNB\Tests\Files;
use DNB\WikibaseConverter\MappingDeserializer;
use PHPUnit\Framework\TestCase;

class MappingJsonTest extends TestCase {

	public function testCanDeserialize() {
		$jsonString = Files::getMappingJson();
		$mapping = json_decode( $jsonString, true );

		$this->assertIsArray( $mapping );
		$this->assertArrayHasKey( '007K', $mapping );
		$this->assertIsArray( $mapping['007K'] );
	}

	/**
	 * @depends testCanDeserialize
	 */
	public function testCanInstantiateObjects() {
		$mapping = ( new MappingDeserializer )->jsonArrayToObject( json_decode( Files::getMappingJson(), true ) );

		$this->assertNotEmpty( $mapping->getPropertyMappings( '003U' ) );
	}

}
