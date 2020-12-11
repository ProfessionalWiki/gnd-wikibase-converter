<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\Tests\Data;
use DNB\WikibaseConverter\Converter;
use DNB\WikibaseConverter\MappingDeserializer;
use DNB\WikibaseConverter\PicaRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Converter
 */
class ConverterTest extends TestCase {

	public function testSimpleValue() {
		$valuesPerProperty = $this->getConverter()->picaToWikibase( $this->getGnd1Pica() );

		$this->assertSame(
			[ 'P3' ],
			$valuesPerProperty->getPropertyIds()
		);

		$this->assertSame(
			[ 'Congress of Neurological Surgeons' ],
			$valuesPerProperty->getValuesForProperty( 'P3' )
		);
	}

	private function getGnd1Pica(): PicaRecord {
		return new PicaRecord( Data::getGndJson( 'GND-1-formatted.json' ) );
	}

	private function getConverter(): Converter {
		return new Converter(
			( new MappingDeserializer() )->jsonArrayToObject( Data::getMapping029A() )
		);
	}

}
