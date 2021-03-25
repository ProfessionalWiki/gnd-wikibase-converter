<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\IdConverter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\IdConverter
 */
class IdConverterTest extends TestCase {

	/**
	 * @dataProvider transformationProvider
	 */
	public function testFoo( string $gndId, string $transformedId ): void {
		$this->assertSame(
			$transformedId,
			( new IdConverter() )->gndToWikibaseId( $gndId )
		);
	}

	public function transformationProvider(): \Generator {
		yield [ '118557513', '11855751301' ];
		yield [ '11855751', '1185575101' ];
	}

}
