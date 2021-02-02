<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;

class ConcatValueSource implements ValueSource {

	private array $segments;

	/**
	 * @param array<int, string> $segments
	 */
	public function __construct( array $segments ) {
		$this->segments = $segments;
	}

	public function valueFromSubfields( Subfields $subfields ): ?string {

	}

}
