<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\Mapping;
use DNB\WikibaseConverter\PropertyDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Mapping
 */
class MappingTest extends TestCase {

	public function testNoDefinitionsWhenMappingIsEmpty() {
		$mapping = Mapping::newEmpty();

		$this->assertEquals(
			[],
			$mapping->getPropertyDefinitions()
		);
	}

	public function testGetPropertyDefinitions() {
		$mapping = Mapping::newFromArray( [
			'P1C4' => [
				'P1' => [
					'type' => 'string'
				]
			]
		] );

		$this->assertEquals(
			[
				new PropertyDefinition( 'P1', 'string' )
			],
			$mapping->getPropertyDefinitions()
		);
	}

}
