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

	public function matches( Subfields $subfields ): bool {
		return $this->subfieldValue === ( $subfields->map[$this->subfieldName] ?? null );
	}

}
