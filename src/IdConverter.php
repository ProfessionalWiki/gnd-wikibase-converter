<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class IdConverter {

	public function gndToWikibaseId( string $gndId ): string {
		if ( preg_match( '/1[012]?\d{7}[0-9X]/', $gndId ) === 1 ) {
			if ( str_ends_with( $gndId, 'X' ) ) {
				return preg_replace('/[^0-9]/', '', $gndId ) . '02';
			}

			return $gndId . '01';
		}


	}

}
