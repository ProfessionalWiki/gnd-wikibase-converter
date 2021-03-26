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
	public function testGndToNumericId( string $gndId, int $transformedId ): void {
		$this->assertSame(
			$transformedId,
			( new IdConverter() )->gndToNumericId( $gndId )
		);
	}

	public function transformationProvider(): \Generator {
		yield 'Case 0: all numeric' => [ '123', 1230 ];
		yield 'Case 1: ends on X' => [ '123X', 1231 ];
		yield 'Case 2: second last is dash' => [ '12-3', 1232 ];
		yield 'Case 3: ends on -X' => [ '12-X', 123 ];

		yield [ '110-7', 11072 ];
		yield [ '101115658X', 1011156581 ];
		yield [ '1033476056', 10334760560 ];
		yield [ '1324-X', 13243 ];
	}

	public function testThrowsExceptionOnInvalidGndId(): void {
		$this->expectException( \InvalidArgumentException::class );
		( new IdConverter() )->gndToNumericId( '~=[,,_,,]:3' );
	}

	/**
	 * @dataProvider transformationProvider
	 */
	public function testNumericIdToGnd( string $gndId, int $transformedId ): void {
		$this->assertSame(
			$gndId,
			( new IdConverter() )->numericIdToGnd( $transformedId )
		);
	}

	public function testThrowsExceptionOnInvalidNumericGndId(): void {
		$this->expectException( \InvalidArgumentException::class );
		( new IdConverter() )->numericIdToGnd( 1337 );
	}

}
