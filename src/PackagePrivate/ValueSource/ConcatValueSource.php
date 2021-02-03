<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;

class ConcatValueSource implements ValueSource {

	/**
	 * @var array<string, string>
	 */
	private array $concatSpec;

	/**
	 * @param array<string, string> $concatSpec
	 */
	public function __construct( array $concatSpec ) {
		$this->concatSpec = $concatSpec;
	}

	public function valueFromSubfields( Subfields $subfields ): ?string {
		$segments = [];

		foreach ( $this->concatSpec as $subfieldName => $format ) {
			if ( array_key_exists( $subfieldName, $subfields->map ) ) {
				$segments[] = str_replace( '$',  $subfields->map[$subfieldName], $format );
			}
		}

		return $segments === [] ? null : implode( '', $segments );
	}

}
