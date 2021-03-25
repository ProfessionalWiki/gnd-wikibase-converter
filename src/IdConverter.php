<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class IdConverter {

	/**
	 * Strips non-numeric characters from the GND ID and appends two digits.
	 * The two digits indicate the format of the GND ID and allow reconstructing
	 * it from the number.
	 *
	 * Format 01: 1[012]?\d{7}[0-9]			118557513	-> 11855751301
	 * Format 02: 1[012]?\d{7}X				10111565X	-> 1011156502
	 * Format 03: [47]\d{6}-\d				4039025-1	-> 4039025103
	 * Format 04: /[1-9]\d{0,7}-[0-9]		12345678-9	-> 12345678904
	 * Format 05: /[1-9]\d{0,7}-X			12345678-X	-> 1234567805
	 * Format 06: 3\d{7}[0-9]				323456789	-> 32345678906
	 * Format 07: 3\d{7}[X]					32345678X	-> 3234567807
	 */
	public function gndToNumericId( string $gndId ): string {
		if ( preg_match( '/1[012]?\d{7}[0-9X]/', $gndId ) === 1 ) {
			if ( str_ends_with( $gndId, 'X' ) ) {
				return $this->transformGndId( $gndId, 2 );
			}

			return $this->transformGndId( $gndId, 1 );
		}

		if ( preg_match( '/[47]\d{6}-\d/', $gndId ) === 1 ) {
			return $this->transformGndId( $gndId, 3 );
		}

		if ( preg_match( '/[1-9]\d{0,7}-[0-9X]/', $gndId ) === 1 ) {
			if ( str_ends_with( $gndId, 'X' ) ) {
				return $this->transformGndId( $gndId, 5 );
			}

			return $this->transformGndId( $gndId, 4 );
		}

		if ( preg_match( '/3\d{7}[0-9X]/', $gndId ) === 1 ) {
			if ( str_ends_with( $gndId, 'X' ) ) {
				return $this->transformGndId( $gndId, 7 );
			}

			return $this->transformGndId( $gndId, 6 );
		}

		throw new \InvalidArgumentException( 'Invalid GND ID' );
	}

	private function transformGndId( string $gndId, int $caseNumber ): string {
		return preg_replace('/[^0-9]/', '', $gndId )
			. str_pad( (string)$caseNumber, 2, '0', STR_PAD_LEFT );
	}

}
