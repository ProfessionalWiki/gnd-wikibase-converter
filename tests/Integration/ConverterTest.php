<?php

declare( strict_types = 1 );

namespace DNB\Tests\Integration;

use DNB\Tests\TestPicaRecords;
use DNB\WikibaseConverter\PackagePrivate\Converter;
use DNB\WikibaseConverter\PackagePrivate\PicaRecord;
use DNB\WikibaseConverter\WikibaseRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\Converter
 * @covers \DNB\WikibaseConverter\PackagePrivate\MappingDeserializer
 */
class ConverterTest extends TestCase {

	public function testEmptyPicaRecordResultsInEmptyWikibaseRecord(): void {
		$converter = $this->newConverterWithEmptyMapping();

		$this->assertEquals(
			new WikibaseRecord(),
			$converter->picaToWikibase( PicaRecord::newEmpty() )
		);
	}

	private function newConverterWithEmptyMapping(): Converter {
		return Converter::fromArrayMapping( [] );
	}

	public function testEmptyMappingResultsInEmptyRecord(): void {
		$converter = $this->newConverterWithEmptyMapping();

		$this->assertEquals(
			new WikibaseRecord(),
			$converter->picaToWikibase( TestPicaRecords::gnd1() )
		);
	}

	public function testCorrectFieldIsUsed(): void {
		$converter = Converter::fromArrayMapping( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => 'a'
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

//	public function testCorrectSubfieldsAreUsed(): void {
//		$converter = Converter::fromArrayMapping( [
//			'P1C4' => [
//				'P1' => [
//					'subfields' => [ 'b', 'd' ]
//				]
//			]
//		] );
//
//		$pica = PicaRecord::withFields( [
//			[
//				'name' => 'P1C4',
//				'subfields' => [
//					[ 'name' => 'a', 'value' => 'wrong' ],
//					[ 'name' => 'b', 'value' => 'right' ],
//					[ 'name' => 'c', 'value' => 'wrongAgain' ],
//					[ 'name' => 'd', 'value' => 'rightAgain' ],
//				]
//			]
//		] );
//
//		$this->assertSame(
//			[ 'right', 'rightAgain' ],
//			$converter->picaToWikibase( $pica )->getValuesForProperty( 'P1' )
//		);
//	}

	public function testMultiplePropertiesForOnePicaField(): void {
		$converter = Converter::fromArrayMapping( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => 'c'
			],
			'P2' => [
				'field' => 'P1C4',
				'subfield' => 'a'
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

	public function testRepeatedPicaFieldsAllGetUsed(): void {
		$converter = Converter::fromArrayMapping( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => 'a'
			]
		] );

		$pica = PicaRecord::withFields( [
			[
				'name' => 'P1C4',
				'subfields' => [
					[ 'name' => 'a', 'value' => 'first' ],
				]
			],
			[
				'name' => 'P1C4',
				'subfields' => [
					[ 'name' => 'a', 'value' => 'second' ],
				]
			]
		] );

		$this->assertSame(
			[ 'first', 'second' ],
			$converter->picaToWikibase( $pica )->getValuesForProperty( 'P1' )
		);
	}

	public function testMultiValueConcatenation(): void {
		$converter = Converter::fromArrayMapping( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => [
					'N' => 'N: $, ',
					'Y' => 'Y: $, ',
					'A' => 'A: $, ',
					'n' => 'n: $, ',
				]
			]
		] );

		$pica = PicaRecord::withFields( [
			[
				'name' => 'P1C4',
				'subfields' => [
					[ 'name' => 'Y', 'value' => 'y1' ],
					[ 'name' => 'N', 'value' => 'N1' ],
					[ 'name' => 'n', 'value' => 'n1' ],
					[ 'name' => 'A', 'value' => 'A1' ],
					[ 'name' => 'N', 'value' => 'N2' ],
				]
			]
		] );

		$this->assertSame(
			[ 'N: N1, N: N2, Y: y1, A: A1, n: n1, ' ],
			$converter->picaToWikibase( $pica )->getValuesForProperty( 'P1' )
		);
	}

}
