<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class IdConverter {

	/**
	 * Reversible transformation from GND-ID to numeric ID.
	 *
	 * The last digit represents the GND-ID format. This allows
	 * both stripping out non-numeric characters without collisions
	 * and turning the number back into the original GND-ID.
	 *
	 * Format 0: all numeric ID: 0 is appended. ie 123 -> 1230
	 * Format 1: numeric ID plus X: 1 is appended. ie 123X -> 1231
	 * Format 2: numeric ID with a dash: 2 is appended. ie 12-3 -> 1232
	 * Format 3: ID ends on -X: 3 is appended. ie 12-X -> 123
	 */
	public function gndToNumericId( string $gndId ): int {
		if ( ctype_digit( $gndId ) ) {
			return (int)( $gndId . '0' );
		}

		if ( str_ends_with( $gndId, '-X' ) ) {
			return (int)( $this->removeNonNumbers( $gndId ) . '3' );
		}

		if ( str_ends_with( $gndId, 'X' ) ) {
			return (int)( $this->removeNonNumbers( $gndId ) . '1' );
		}

		if ( substr( $gndId, -2, 1 ) === '-' ) {
			return (int)( $this->removeNonNumbers( $gndId ) . '2' );
		}

		throw new \InvalidArgumentException( 'Invalid GND ID' );
	}

	private function removeNonNumbers( string $string ): string {
		return preg_replace('/[^0-9]/', '', $string );
	}

	/**
	 * Reverse of @see gndToNumericId
	 */
	public function numericIdToGnd( int $numericId ): string {
		$idFormat = substr( (string)$numericId, -1, 1 );
		$idWithoutFormat = substr( (string)$numericId, 0, -1 );

		switch ( $idFormat ) {
			case '0':
				return $idWithoutFormat;
			case '1':
				return $idWithoutFormat . 'X';
			case '2':
				return substr( $idWithoutFormat, 0, -1 ) . '-' . substr( $idWithoutFormat, -1, 1 );
			case '3':
				return $idWithoutFormat . '-X';
			default:
				throw new \InvalidArgumentException( 'Invalid numeric GND ID' );
		}
	}

}
