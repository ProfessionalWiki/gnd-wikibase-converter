<?php

declare( strict_types = 1 );

namespace DNB\Tests;

use DNB\WikibaseConverter\PicaRecord;

class TestPicaRecords {

	public static function gnd1(): PicaRecord {
		return new PicaRecord( self::getGndJson( 'GND-1-formatted.json'  ) );
	}

	private static function getGndJson( string $fileName ): array {
		return json_decode( Files::getGndData( $fileName ), true );
	}

	public static function gnd2(): PicaRecord {
		return new PicaRecord( self::getGndJson( 'GND-2-formatted.json'  ) );
	}

	public static function gnd5(): PicaRecord {
		return new PicaRecord( self::getGndJson( 'GND-5-formatted.json'  ) );
	}

}
