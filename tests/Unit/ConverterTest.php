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
		$converter = $this->newConverterWithEmptyMapping();

		$this->assertEquals(
			new WikibaseRecord(),
			$converter->picaToWikibase( PicaRecord::newEmpty() )
		);
	}

	private function newConverterWithEmptyMapping(): Converter {
		return Converter::fromArrayMapping( [] );
	}

	public function testEmptyMappingResultsInEmptyRecord() {
		$converter = $this->newConverterWithEmptyMapping();

		$this->assertEquals(
			new WikibaseRecord(),
			$converter->picaToWikibase( TestPicaRecords::gnd1() )
		);
	}

	public function testCorrectFieldIsUsed() {
		$converter = Converter::fromArrayMapping( [
			'P1C4' => [
				'P1' => [
					'type' => 'string',
					'subfields' => [ 'a' ]
				]
			]
		] );

		$pica = PicaRecord::withFields( [
			[
				'name' => 'WRONG1',
				'subfields' => [ [ 'name' => 'a', 'value' => 'wrong' ] ]
			],
			[
				'name' => 'P1C4',
				'subfields' => [ [ 'name' => 'a', 'value' => 'right' ] ]
			],
			[
				'name' => 'WRONG2',
				'subfields' => [ [ 'name' => 'a', 'value' => 'wrongAgain' ] ]
			],
		] );

		$this->assertSame(
			[ 'right' ],
			$converter->picaToWikibase( $pica )->getValuesForProperty( 'P1' )
		);
	}

	public function testCorrectSubfieldsAreUsed() {
		$converter = Converter::fromArrayMapping( [
			'P1C4' => [
				'P1' => [
					'type' => 'string',
					'subfields' => [ 'b', 'd' ]
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
					[ 'name' => 'd', 'value' => 'rightAgain' ],
				]
			]
		] );

		$this->assertSame(
			[ 'right', 'rightAgain' ],
			$converter->picaToWikibase( $pica )->getValuesForProperty( 'P1' )
		);
	}

	public function testMultiplePropertiesForOnePicaField() {
		$converter = Converter::fromArrayMapping( [
			'P1C4' => [
				'P1' => [
					'type' => 'string',
					'subfields' => [ 'c' ]
				],
				'P2' => [
					'type' => 'string',
					'subfields' => [ 'a' ]
				]
			]
		] );

		$pica = PicaRecord::withFields( [
			[
				'name' => 'P1C4',
				'subfields' => [
					[ 'name' => 'a', 'value' => 'aaa' ],
					[ 'name' => 'b', 'value' => 'bbb' ],
					[ 'name' => 'c', 'value' => 'ccc' ],
				]
			]
		] );

		$wikibaseRecord = $converter->picaToWikibase( $pica );

		$this->assertSame(
			[ 'ccc' ],
			$wikibaseRecord->getValuesForProperty( 'P1' )
		);

		$this->assertSame(
			[ 'aaa' ],
			$wikibaseRecord->getValuesForProperty( 'P2' )
		);
	}

}
