<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;

class ConcatValueSource implements ValueSource {

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
			$segments[] = str_replace( '$',  $subfields->map[$subfieldName], $format );
		}

		return $segments === [] ? null : implode( '', $segments );
	}

}
