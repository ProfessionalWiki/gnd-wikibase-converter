<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

class ValueMap {

	/**
	 * @var array<string, string> $valueMap
	 */
	private array $valueMap;

	/**
	 * @param array<string, string> $valueMap
	 */
	public function __construct(
		array $valueMap
	) {
		$this->valueMap = $valueMap;
	}

	public function map( string $subfieldValue ): ?string {
		if ( $this->valueMap === [] ) {
			return $subfieldValue;
		}

		if ( array_key_exists( $subfieldValue, $this->valueMap ) ) {
			return $this->valueMap[$subfieldValue];
		}

		return null;
	}

}
