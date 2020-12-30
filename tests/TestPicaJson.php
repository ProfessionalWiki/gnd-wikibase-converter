<?php

declare( strict_types = 1 );

namespace DNB\Tests;

class TestPicaJson {

	public static function getGnd1Json(): array {
		return self::getGndJson( 'GND-1-formatted.json' );
	}

	private static function getGndJson( string $fileName ): array {
		return json_decode( self::getGndData( $fileName ), true );
	}

	private static function getGndData( string $fileName ): string {
		return file_get_contents( __DIR__ . '/../data/gnd/' . $fileName );
	}

	public static function gnd5string(): string {
		return self::getGndData( 'GND-5-formatted.json'  );
	}

	public static function gndStrings(): \Generator {
		yield self::getGndData( 'GND-1-formatted.json'  );
		yield self::getGndData( 'GND-2-formatted.json'  );
		yield self::getGndData( 'GND-5-formatted.json'  );
		yield self::getGndData( '102111847-Christian.json'  );
		yield self::getGndData( '118540238-Johann.json'  );
	}

}
