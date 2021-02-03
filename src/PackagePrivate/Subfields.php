<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

class Subfields {

	/**
	 * @var array<string, array<int, string>>
	 */
	public array $map;

	/**
	 * @param array<string, string> $subfields
	 */
	public static function fromSingleValueMap( array $subfields ): self {
		$map = [];

		foreach ( $subfields as $name => $value ) {
			$map[$name] = [ $value ];
		}

		return new self( $map );
	}

	/**
	 * @param array<string, array<int, string>> $map
	 */
	public function __construct( array $map ) {
		$this->map = $map;
	}

}
