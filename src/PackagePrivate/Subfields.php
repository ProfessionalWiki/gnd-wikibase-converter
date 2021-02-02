<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

class Subfields {

	/**
	 * @param array<string, string> $subfields
	 */
	public static function newFromMap( array $subfields ): self {
		return new self( $subfields );
	}

	/**
	 * @var array<string, string>
	 */
	public array $map;

	/**
	 * @param array<string, string> $map
	 */
	private function __construct( array $map ) {
		$this->map = $map;
	}

}
