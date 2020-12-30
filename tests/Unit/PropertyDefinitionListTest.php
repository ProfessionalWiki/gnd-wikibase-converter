<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\PropertyDefinition;
use DNB\WikibaseConverter\PackagePrivate\PropertyDefinitionList;
use PHPUnit\Framework\TestCase;

class PropertyDefinitionListTest extends TestCase {

	public function testGetDefinitions() {
		$p1 = new PropertyDefinition(
			'P1',
			'string',
		);

		$p2 = new PropertyDefinition(
			'P2',
			'string',
		);

		$this->assertEquals(
			[ 'P2' => $p2, 'P1' => $p1 ],
			( new PropertyDefinitionList( $p2, $p1 ) )->asArray()
		);
	}

}
