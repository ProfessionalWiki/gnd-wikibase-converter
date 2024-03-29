<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate\ValueSource;

use DNB\WikibaseConverter\PackagePrivate\Subfields;

class SingleSubfieldSource implements ValueSource {

	private string $subfieldName;
	private ?int $position;

	public function __construct( string $subfieldName, ?int $position = null ) {
		$this->subfieldName = $subfieldName;
		$this->position = $position;
	}

	public function valueFromSubfields( Subfields $subfields ): array {
		$values = [];

		foreach ( $subfields->map[$this->subfieldName] ?? [] as $subfieldValue ) {
			$value = $this->extractFromSubfieldValue( $subfieldValue );

			if ( $value !== null ) {
				$values[] = $value;
			}
		}

		return $values;
	}

	private function extractFromSubfieldValue( string $subfieldValue ): ?string {
		if ( $this->position === null ) {
			return $subfieldValue;
		}

		if ( $this->positionIsOutOfBounds( $subfieldValue ) ) {
			return null;
		}

		return substr( $subfieldValue, $this->position -1, 1 );
	}

	private function positionIsOutOfBounds( string $subfieldValue ): bool {
		return $this->position < 1 || $this->position > strlen( $subfieldValue );
	}

}
