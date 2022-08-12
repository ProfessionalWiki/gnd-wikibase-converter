<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\ValueSource;

class QualifierMapping {

	private string $propertyId;
	private ValueSource $valueSource;
	private ValueMap $valueMap;

	public function __construct(
		string $propertyId,
		ValueSource $valueSource,
		?ValueMap $valueMap = null
	) {
		$this->propertyId = $propertyId;
		$this->valueSource = $valueSource;
		$this->valueMap = $valueMap ?? new ValueMap( [] );
	}

	/**
	 * @return GndQualifier[]
	 */
	public function qualifiersFromSubfields( Subfields $subfields ): array {
		$qualifiers = [];

		foreach ( $this->valueSource->valueFromSubfields( $subfields ) as $value ) {
			$mappedValue = $this->valueMap->map( $value );

			// TODO: https://github.com/ProfessionalWiki/gnd-wikibase-converter/issues/8
			if ( is_string( $mappedValue ) ) {
				$qualifiers[] = new GndQualifier( $this->propertyId, $mappedValue );
			}
		}

		return $qualifiers;
	}

}
