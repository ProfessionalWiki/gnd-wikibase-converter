<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\ValueSource;

class QualifierMapping {

	public const INVALID_VALUE_PROPERTY_ID = 'P645';

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

			if ( is_string( $mappedValue ) ) {
				$qualifiers[] = new GndQualifier( $this->propertyId, $mappedValue );
			}
			else {
				$qualifiers[] = new GndQualifier(
					self::INVALID_VALUE_PROPERTY_ID,
					$this->propertyId . ' (qualifier): ' . $value
				);
			}
		}

		return $qualifiers;
	}

}
