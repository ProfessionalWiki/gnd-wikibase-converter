<?php

declare( strict_types = 1 );

namespace DNB\Tests;

use DNB\WikibaseConverter\PackagePrivate\PicaRecord;

class TestPicaRecords {

	public static function gnd1(): PicaRecord {
		return new PicaRecord( self::getGndJson( 'GND-1-formatted.json'  ) );
	}

	private static function getGndJson( string $fileName ): array {
		return json_decode( self::getGndData( $fileName ), true );
	}

	private static function getGndData( string $fileName ): string {
		return file_get_contents( __DIR__ . '/../data/gnd/' . $fileName );
	}

}
