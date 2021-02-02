<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

/**
 * @internal
 */
class SubfieldCondition {

	private string $subfieldName;
	private ?string $subfieldValue;

	public function __construct( string $subfieldName, ?string $subfieldValue ) {
		$this->subfieldName = $subfieldName;
		$this->subfieldValue = $subfieldValue;
	}

	/**
	 * @param array<string, string> $subfieldsAsMap
	 */
	public function matches( array $subfieldsAsMap ): bool {
		return $this->subfieldValue === ( $subfieldsAsMap[$this->subfieldName] ?? null );
	}

}
