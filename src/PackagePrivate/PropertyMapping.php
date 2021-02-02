<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\PackagePrivate\ValueSource\ValueSource;
use DNB\WikibaseConverter\PropertyWithValues;

/**
 * @internal
 */
class PropertyMapping {

	private string $propertyId;
	private ValueSource $valueSource;
	private ?SubfieldCondition $condition;

	/** @var array<string, string> */
	private array $valueMap;

	/**
	 * @param array<string, string> $valueMap
	 */
	public function __construct(
		string $propertyId,
		ValueSource $valueSource,
		?SubfieldCondition $condition = null,
		array $valueMap = []
	) {
		$this->propertyId = $propertyId;
		$this->valueSource = $valueSource;
		$this->condition = $condition;
		$this->valueMap = $valueMap;
	}

	public function convert( Subfields $subfields ): PropertyWithValues {
		$propertyWithValues = new PropertyWithValues( $this->propertyId );

		if ( $this->conditionMatches( $subfields ) ) {
			$valueToAddOrNull = $this->valueSource->valueFromSubfields( $subfields );

			if ( $valueToAddOrNull !== null ) {
				$mappedValueOrNull = $this->getMappedValue( $valueToAddOrNull );

				if ( $mappedValueOrNull !== null ) {
					$propertyWithValues->addValue( $mappedValueOrNull );
				}
			}
		}

		return $propertyWithValues;
	}

	private function conditionMatches( Subfields $subfields ): bool {
		if ( $this->condition instanceof SubfieldCondition ) {
			return $this->condition->matches( $subfields );
		}

		return true;
	}

	private function getMappedValue( string $subfieldValue ): ?string {
		if ( $this->valueMap === [] ) {
			return $subfieldValue;
		}

		if ( array_key_exists( $subfieldValue, $this->valueMap ) ) {
			return $this->valueMap[$subfieldValue];
		}

		return null;
	}

}
