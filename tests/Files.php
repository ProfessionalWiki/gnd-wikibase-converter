<?php

declare( strict_types = 1 );

namespace DNB\Tests;

class Files {

	public static function getMappingJson(): string {
		return file_get_contents( __DIR__ . '/../src/mapping.json' );
	}



}
