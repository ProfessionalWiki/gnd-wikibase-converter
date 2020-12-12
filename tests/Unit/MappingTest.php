<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\Mapping;
use DNB\WikibaseConverter\PropertyDefinitionList;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Mapping
 */
class MappingTest extends TestCase {

	public function testNoDefinitionsWhenMappingIsEmpty() {
		$mapping = Mapping::newEmpty();

		$this->assertEquals(
			new PropertyDefinitionList(),
			$mapping->getProperties()
		);
	}

}
