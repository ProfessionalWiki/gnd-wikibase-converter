<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\PackagePrivate\QualifierMapping;
use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\QualifierMapping
 */
class QualifierMappingTest extends TestCase {

	public function testPosition(): void {
		$mapping = new QualifierMapping( 'P1', new SingleSubfieldSource( 'sub', 3 ) );

		$this->assertEquals(
			[
				new GndQualifier( 'P1', '3' ),
				new GndQualifier( 'P1', '8' ),
			],
			$mapping->qualifiersFromSubfields( new Subfields( [
				'wrong' => [ 'abc', 'def' ],
				'sub' => [ '12345', '67890' ],
				'alsoWrong' => [ 'xyz', 'foo' ],
			] ) )
		);
	}

}
