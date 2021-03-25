<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class IdConverter {

	public function gndToWikibaseId( string $gndId ): string {
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
			return $this->transformGndId( $gndId, 4 );
		}

		return 'TODO'; // TODO
	}

	private function transformGndId( string $gndId, int $caseNumber ): string {
		return preg_replace('/[^0-9]/', '', $gndId )
			. str_pad( (string)$caseNumber, 2, '0', STR_PAD_LEFT );
	}

}
