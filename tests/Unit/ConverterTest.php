<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\Tests\TestPicaRecords;
use DNB\WikibaseConverter\Converter;
use DNB\WikibaseConverter\PicaRecord;
use DNB\WikibaseConverter\WikibaseRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Converter
 */
class ConverterTest extends TestCase {

	public function testEmptyPicaRecordResultsInEmptyWikibaseRecord() {
		$converter = $this->newConverter();

		$this->assertEquals(
			new WikibaseRecord(),
			$converter->picaToWikibase( PicaRecord::newEmpty() )
		);
	}

	private function newConverter(): Converter {
		return Converter::fromArrayMapping( [] );
	}

	public function testEmptyMappingResultsInEmptyRecord() {
		$converter = $this->newConverter();

		$this->assertEquals(
			new WikibaseRecord(),
			$converter->picaToWikibase( TestPicaRecords::gnd1() )
		);
	}

	public function testSimpleValue() {
		$converter = Converter::fromArrayMapping( [
			'P1C4' => [
				'P1' => [
					'type' => 'string',

					'subfields' => [ 'b' ]
				]
			]
		] );

		$pica = PicaRecord::withFields( [
			[
				'name' => 'P1C4',
				'subfields' => [
					[ 'name' => 'a', 'value' => 'wrong' ],
					[ 'name' => 'b', 'value' => 'right' ],
					[ 'name' => 'c', 'value' => 'wrongAgain' ],
				]
			]
		] );

		$wikibaseRecord = $converter->picaToWikibase( $pica );

		$this->assertSame(
			[ 'right' ],
			$wikibaseRecord->getValuesForProperty( 'P1' )
		);

//		$this->assertSame(
//			[ 'P3' ],
//			$valuesPerProperty->getPropertyIds()
//		);
//
//		$this->assertSame(
//			[ 'Congress of Neurological Surgeons' ],
//			$valuesPerProperty->getValuesForProperty( 'P3' )
//		);
	}

}
