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
	 * Format 0: ID ends on -X: 3 is appended. ie 12-X -> 123
	 */
	public function gndToNumericId( string $gndId ): string {
		if ( ctype_digit( $gndId ) ) {
			return $gndId . '0';
		}

		if ( str_ends_with( $gndId, '-X' ) ) {
			return preg_replace('/[^0-9]/', '', $gndId ) . '3';
		}

		if ( str_ends_with( $gndId, 'X' ) ) {
			return preg_replace('/[^0-9]/', '', $gndId ) . '1';
		}

		if ( substr( $gndId, -2, 1 ) === '-' ) {
			return preg_replace('/[^0-9]/', '', $gndId ) . '2';
		}

		throw new \InvalidArgumentException( 'Invalid GND ID' );
	}

	private function transformGndId( string $gndId, int $caseNumber ): string {
		return preg_replace('/[^0-9]/', '', $gndId )
			. str_pad( (string)$caseNumber, 2, '0', STR_PAD_LEFT );
	}

}
