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
		return ( new ConcatValueSource( $concatSpec ) )->valueFromSubfields( Subfields::newFromMap( $subfields ) );
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

}
