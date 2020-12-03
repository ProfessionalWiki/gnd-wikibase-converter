<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\Tests\Data;
use DNB\WikibaseConverter\Pica;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Pica
 */
class PicaTest extends TestCase {

	public function testGetFields() {
		$pica = new Pica( Data::getGnd1Json() );

		$this->assertEquals(
			Data::getGnd1Json()['fields'],
			$pica->getFields()
		);
	}

}
