<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\PropertyWithValues;

/**
 * @internal
 */
class PropertyMapping {

	private string $propertyId;
	private string $subfield;
	private ?int $position;
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
		$this->subfield = $subfield;
		$this->valueMap = $valueMap;
		$this->condition = $condition;
		$this->position = $position;
	}

	public function convert( Subfields $subfields ): PropertyWithValues {
		$propertyWithValues = new PropertyWithValues( $this->propertyId );

		if ( $this->conditionMatches( $subfields ) ) {
			foreach ( $subfields->map as $subfieldName => $subfieldValue ) {
				if ( $subfieldName === $this->subfield ) {
					$valueToAddOrNull = $this->getSubfieldValue( $subfieldValue );

					if ( $valueToAddOrNull !== null ) {
						$propertyWithValues->addValue( $valueToAddOrNull );
					}
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

	private function getSubfieldValue( string $subfieldValue ): ?string {
		if ( $this->positionIsOutOfBounds( $subfieldValue ) ) {
			return null;
		}

		return $this->getMappedValue( $this->extractFromSubfieldValue( $subfieldValue ) );
	}

	private function positionIsOutOfBounds( string $subfieldValue ): bool {
		if ( $this->position === null ) {
			return false;
		}

		return $this->position < 1 || $this->position > strlen( $subfieldValue );
	}

	private function extractFromSubfieldValue( string $subfieldValue ): string {
		if ( $this->position === null ) {
			return $subfieldValue;
		}

		return substr( $subfieldValue, $this->position -1, 1 );
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
