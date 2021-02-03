<?php

declare( strict_types = 1 );

namespace DNB\Tests\Integration;

use DNB\Tests\TestPicaRecords;
use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\GndStatement;
use DNB\WikibaseConverter\PackagePrivate\Converter;
use DNB\WikibaseConverter\PackagePrivate\PicaRecord;
use DNB\WikibaseConverter\GndItem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\Converter
 * @covers \DNB\WikibaseConverter\PackagePrivate\MappingDeserializer
 */
class ConverterTest extends TestCase {

	public function testEmptyPicaRecordResultsInEmptyWikibaseRecord(): void {
		$converter = $this->newConverterWithEmptyMapping();

		$this->assertEquals(
			new GndItem(),
			$converter->picaToWikibase( PicaRecord::newEmpty() )
		);
	}

	private function newConverterWithEmptyMapping(): Converter {
		return Converter::fromArrayMapping( [] );
	}

	public function testEmptyMappingResultsInEmptyRecord(): void {
		$converter = $this->newConverterWithEmptyMapping();

		$this->assertEquals(
			new GndItem(),
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
			$converter->picaToWikibase( $pica )->getMainValuesForProperty( 'P1' )
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
			$wikibaseRecord->getMainValuesForProperty( 'P1' )
		);

		$this->assertSame(
			[ 'aaa' ],
			$wikibaseRecord->getMainValuesForProperty( 'P2' )
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
			$converter->picaToWikibase( $pica )->getMainValuesForProperty( 'P1' )
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
			$converter->picaToWikibase( $pica )->getMainValuesForProperty( 'P1' )
		);
	}

	public function testQualifiers(): void {
		$converter = Converter::fromArrayMapping( [
			'P1' => [
				'field' => 'P1C4',
				'subfield' => 'x',
				'qualifiers' => [
					'P50' => 'a',
					'P51' => 'b',
					'P52' => 'c',
				]
			]
		] );

		$pica = PicaRecord::withFields( [
			[
				'name' => 'P1C4',
				'subfields' => [
					[ 'name' => 'x', 'value' => 'main value' ],
					[ 'name' => 'c', 'value' => 'C1' ],
					[ 'name' => 'a', 'value' => 'A1' ],
					[ 'name' => 'c', 'value' => 'C2' ],
				]
			]
		] );

		$this->assertEquals(
			[ new GndStatement(
				'P1',
				'main value',
				[
					new GndQualifier( 'P50', 'A1' ),
					new GndQualifier( 'P52', 'C1' ),
					new GndQualifier( 'P52', 'C2' ),
				]
			) ],
			$converter->picaToWikibase( $pica )->getStatementsForProperty( 'P1' )
		);
	}

}
