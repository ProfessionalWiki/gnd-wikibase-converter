<?php

declare( strict_types = 1 );

namespace DNB\Tests;

class Data {

	public static function getGndJson( string $fileName ): array {
		return json_decode( Files::getGndData( $fileName ), true );
	}

	public static function getGnd1Json(): array {
		return self::getGndJson( 'GND-1-formatted.json' );
	}

	public static function getMapping029A(): array {
		return json_decode( Files::getMappingData( '029A-P3.json' ), true );
	}

}
