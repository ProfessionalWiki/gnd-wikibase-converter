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

	public function testWhenPositionIsOutOfBounds_nullIsReturned(): void {
		$subfieldSource = new SingleSubfieldSource(
			'a',
			4
		);

		$this->assertNull(
			$subfieldSource->valueFromSubfields(
				Subfields::fromSingleValueMap( [
					'a' => 'abc'
				] )
			)
		);
	}

}
