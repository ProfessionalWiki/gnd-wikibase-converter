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
		yield 'Case 1, 9 chars' => [ '118557513', '11855751301' ];
		yield 'Case 1, 10 chars' => [ '1033476056', '103347605601' ];

		yield 'Case 2, 9 chars' => [ '10111565X', '1011156502' ];
		yield 'Case 2, 10 chars' => [ '101115658X', '10111565802' ];

		yield 'Case 3 (8 num + dash + num)' => [ '4039025-1', '4039025103' ];
	}

}
