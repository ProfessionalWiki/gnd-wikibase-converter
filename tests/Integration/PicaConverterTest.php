<?php

declare( strict_types = 1 );

namespace DNB\Tests\Integration;

use DNB\Tests\TestPicaJson;
use DNB\WikibaseConverter\PicaConverter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PicaConverter
 */
class PicaConverterTest extends TestCase {

	public function testCreatesWikibaseRecord() {
		$converter = new PicaConverter();

		$wikibaseRecord = $converter->picaJsonToWikibaseRecord( TestPicaJson::gnd5string() );

		$this->assertSame(
			[ 'http://d-nb.info/gnd/275-6' ],
			$wikibaseRecord->getValuesForProperty( 'P1' )
		);
	}

	public function testSmoke() {
		$converter = new PicaConverter();

		foreach ( TestPicaJson::gndStrings() as $jsonString ) {
			$wikibaseRecord = $converter->picaJsonToWikibaseRecord( $jsonString );

			$this->assertNotEmpty( $wikibaseRecord->getPropertyIds() );
		}
	}

}
