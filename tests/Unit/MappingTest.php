<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\Mapping;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Mapping
 */
class MappingTest extends TestCase {

	public function testGetPropertyDefinitions() {
		$mapping = Mapping::newFromArray( [
			'P1C4' => [
				'P1' => [
					'type' => 'string',
					'subfields' => [ 'a' ]
				]
			]
		] );

		$this->assertEquals(
			[],
			$mapping->getPropertyDefinitions()
		);
	}

}
