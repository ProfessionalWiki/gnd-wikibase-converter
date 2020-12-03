<?php

declare( strict_types = 1 );

namespace DNB\Tests;

class Files {

	public static function getMappingJson(): string {
		return file_get_contents( __DIR__ . '/../src/mapping.json' );
	}

	public static function getGndData( string $fileName ): string {
		return file_get_contents( __DIR__ . '/../data/gnd/' . $fileName );
	}

	public static function getMappingData( string $fileName ): string {
		return file_get_contents( __DIR__ . '/../data/mapping/' . $fileName );
	}

}
