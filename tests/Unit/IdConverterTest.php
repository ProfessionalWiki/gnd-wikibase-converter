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
		yield 'Format 1, 9 chars' => [ '118557513', '11855751301' ];
		yield 'Format 1, 10 chars' => [ '1033476056', '103347605601' ];

		yield 'Format 2, 9 chars' => [ '10111565X', '1011156502' ];
		yield 'Format 2, 10 chars' => [ '101115658X', '10111565802' ];

		yield 'Format 3 (8 num + dash + num)' => [ '4039025-1', '4039025103' ];

		yield 'Format 4, 3 chars (min)' => [ '1-7', '1704' ];
		yield 'Format 4, 5 chars' => [ '191-0', '191004' ];
		yield 'Format 4, 10 chars (max)' => [ '12345678-9', '12345678904' ];
	}

}
