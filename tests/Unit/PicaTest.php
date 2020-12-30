<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\Tests\TestPicaJson;
use DNB\WikibaseConverter\PackagePrivate\PicaRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\PicaRecord
 */
class PicaTest extends TestCase {

	public function testGetFields() {
		$pica = new PicaRecord( TestPicaJson::getGnd1Json() );

		$this->assertEquals(
			TestPicaJson::getGnd1Json()['fields'],
			$pica->getFields()
		);
	}

}
