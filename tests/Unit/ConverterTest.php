<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\Converter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\Converter
 */
class ConverterTest extends TestCase {

	public function testGetId() {
		$jsonString = file_get_contents( __DIR__ . '/../../data/GND-1-formatted.json' );
		$pica = json_decode( $jsonString, true );

		$this->assertSame(
			'110-7',
			$this->getId( $pica )
		);
	}

	private function getId( array $pica ): string {
		return ( new Converter() )->getIdFromPica( $pica );
	}

}
