<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;

interface ValueSource {

	/**
	 * @return array<int, string>
	 */
	public function valueFromSubfields( Subfields $subfields ): array;

}
