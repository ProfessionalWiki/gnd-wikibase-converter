<?php

declare( strict_types = 1 );

namespace DNB\Tests\Integration;

use DNB\Tests\TestPicaJson;
use DNB\WikibaseConverter\InvalidPica;
use DNB\WikibaseConverter\PicaConverter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PicaConverter
 */
class PicaConverterTest extends TestCase {

	public function testCreatesWikibaseRecord(): void {
		$wikibaseRecord = $this->newConverter()->picaJsonToGndItem( TestPicaJson::gnd5string() );

		$this->assertSame(
			[ 'http://d-nb.info/gnd/275-6' ],
			$wikibaseRecord->getMainValuesForProperty( 'P1' )
		);
	}

	private function newConverter(): PicaConverter {
		return new PicaConverter();
	}

	public function testSmoke(): void {
		$converter = $this->newConverter();

		foreach ( TestPicaJson::gndStrings() as $jsonString ) {
			$wikibaseRecord = $converter->picaJsonToGndItem( $jsonString );

			$this->assertNotEmpty( $wikibaseRecord->getPropertyIds() );
		}
	}

	/**
	 * @dataProvider invalidPicaProvider
	 */
	public function testInvalidPicaCausesException( string $line ): void {
		$converter = $this->newConverter();

		$this->expectException( InvalidPica::class );
		$converter->picaJsonToGndItem( $line );
	}

	public function invalidPicaProvider(): \Generator {
		yield 'json syntax error' => [ 'not json' ];
		yield 'fields key missing' => [ '{}' ];
		yield 'fields not an array' => [ '{"fields": "not an array"}' ];
		yield 'empty string' => [ '' ];
		yield 'non-map json' => [ '42' ];
	}

}
