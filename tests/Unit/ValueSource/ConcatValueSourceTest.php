<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\ConcatValueSource;
use PHPUnit\Framework\TestCase;

class ConcatValueSourceTest extends TestCase {

	public function testWhenConcatSpecIsEmpty_returnsNull(): void {
		$this->assertNull(
			$this->getConcatenatedValue(
				[],
				[
					'a' => 'foo',
					'b' => 'bar',
				]
			)
		);
	}

	/**
	 * @param array<string, string> $concatSpec
	 * @param array<string, string> $subfields
	 */
	private function getConcatenatedValue( array $concatSpec, array $subfields ): ?string {
		return ( new ConcatValueSource( $concatSpec ) )->valueFromSubfields( Subfields::fromSingleValueMap( $subfields ) );
	}

	public function testConcatReturnsAllValuesWhenAllArePresent(): void {
		$this->assertSame(
			'a: foo, b: bar',
			$this->getConcatenatedValue(
				[
					'a' => 'a: $, ',
					'b' => 'b: $'
				],
				[
					'a' => 'foo',
					'b' => 'bar',
				]
			)
		);
	}

	public function testConcatReturnsOnlyPresentValues(): void {
		$this->assertSame(
			'a: foo, c: baz',
			$this->getConcatenatedValue(
				[
					'a' => 'a: $, ',
					'b' => 'b: $',
					'c' => 'c: $',
				],
				[
					'a' => 'foo',
					'c' => 'baz',
				]
			)
		);
	}

	public function testConcatRepeatsRepeatedSubfields(): void {
		$concatSpec = [
			'a' => 'a: $, ',
			'b' => 'b: $, ',
		];

		$subfields = [
			'a' => [ 'one', 'two' ],
			'b' => [ 'tree', 'four' ],
		];

		$this->assertSame(
			'a: one, a: two, b: tree, b: four, ',
			( new ConcatValueSource( $concatSpec ) )->valueFromSubfields( new Subfields( $subfields ) )
		);
	}

}
