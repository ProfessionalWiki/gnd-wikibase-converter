<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

use DNB\WikibaseConverter\PackagePrivate\Converter;
use DNB\WikibaseConverter\PackagePrivate\PicaRecord;

/**
 * Facade for the PackagePrivate services
 */
class PicaConverter {

	private ?Converter $converter = null;

	public static function newWithDefaultMapping(): self {
		return new self();
	}

	private function getConverter(): Converter {
		if ( $this->converter === null ) {
			$this->converter = $this->newConverter();
		}

		return $this->converter;
	}

	private function newConverter(): Converter {
		return Converter::fromArrayMapping( json_decode( self::getMappingJson(), true ) );
	}

	public static function getMappingJson(): string {
		return file_get_contents( __DIR__ . '/mapping.json' );
	}

	/**
	 * @throws InvalidPica
	 */
	public function picaJsonToGndItem( string $json ): GndItem {
		$jsonArray = json_decode( $json, true );

		if ( !is_array( $jsonArray ) ) {
			throw new InvalidPica( 'Invalid JSON' );
		}

		return $this->getConverter()->picaToWikibase( new PicaRecord( $jsonArray ) );
	}

}
