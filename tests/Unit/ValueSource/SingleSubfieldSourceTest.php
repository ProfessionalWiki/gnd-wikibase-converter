<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource
 */
class SingleSubfieldSourceTest extends TestCase {

	public function testWhenNoSubfieldNoValuesAreReturned(): void {
		$subfieldSource = new SingleSubfieldSource(
			'a'
		);

		$this->assertSame(
			[],
			$subfieldSource->valueFromSubfields(
				new Subfields( [
					'b' => [ 'wrong' ],
					'c' => [ 'maw' ],
				] )
			)
		);
	}
	public function testAllValuesOfTheSubfieldAreReturned(): void {
		$subfieldSource = new SingleSubfieldSource(
			'a',
			2
		);

		$this->assertSame(
			[ 'b', 'e', 'h' ],
			$subfieldSource->valueFromSubfields(
				new Subfields( [
					'b' => [ 'wrong' ],
					'a' => [ 'abc', 'def', 'ghi' ],
					'c' => [ 'maw' ],
				] )
			)
		);
	}

	public function testAllOutOfBoundValuesAreIgnored(): void {
		$subfieldSource = new SingleSubfieldSource(
			'a',
			2
		);

		$this->assertSame(
			[ 'e' ],
			$subfieldSource->valueFromSubfields(
				new Subfields( [
					'b' => [ 'wrong' ],
					'a' => [ 'a', 'def', 'g' ],
					'c' => [ 'maw' ],
				] )
			)
		);
	}

}
