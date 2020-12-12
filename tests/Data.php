<?php

declare( strict_types = 1 );

namespace DNB\Tests;

class Data {

	public static function getGnd1Json(): array {
		return self::getGndJson( 'GND-1-formatted.json' );
	}

	private static function getGndJson( string $fileName ): array {
		return json_decode( self::getGndData( $fileName ), true );
	}

	private static function getGndData( string $fileName ): string {
		return file_get_contents( __DIR__ . '/../data/gnd/' . $fileName );
	}

}
