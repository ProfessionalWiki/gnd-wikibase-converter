<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class IdConverter {

	public function gndToWikibaseId( string $gndId ): string {
		return $gndId . '01';
	}

}
