<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use DNB\WikibaseConverter\PropertyWithValues;

/**
 * @internal
 */
class PropertyMapping {

	private string $propertyId;
	private SingleSubfieldSource $valueSource;
	private ?SubfieldCondition $condition;

	/** @var array<string, string> */
	private array $valueMap;

	/**
	 * @param array<string, string> $valueMap
	 */
	public function __construct(
		/** @readonly */ string $propertyId,
		/** @readonly */ string $subfield,
		/** @readonly */ ?int $position = null,
		/** @readonly */ ?SubfieldCondition $condition = null,
		/** @readonly */ array $valueMap = []
	) {
		$this->propertyId = $propertyId;
		$this->valueSource = new SingleSubfieldSource( $subfield, $position );
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
