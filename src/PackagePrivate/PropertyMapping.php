<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\PropertyWithValues;

class PropertyMapping {

	private string $propertyId;
	private array $subfields;
	private ?int $position = null;
	private ?SubfieldCondition $condition = null;
	private array $valueMap = [];

	public function __construct(
		/** @readonly */ string $propertyId,
		/** @readonly string[] */ array $subfields,
		/** @readonly */ ?int $position = null,
		/** @readonly */ ?SubfieldCondition $condition = null,
		/** @readonly string[] */ array $valueMap = []
	) {
		$this->valueMap = $valueMap;
		$this->condition = $condition;
		$this->position = $position;
		$this->subfields = $subfields;
		$this->propertyId = $propertyId;
	}

	public function convert( array $subfields ): PropertyWithValues {
		$propertyWithValues = new PropertyWithValues( $this->propertyId );

		if ( $this->conditionMatches( $subfields ) ) {
			foreach ( $subfields as $subfield ) {
				if ( in_array( $subfield['name'], $this->subfields ) ) {
					$valueToAddOrNull = $this->getSubfieldValue( $subfield['value'] );

					if ( $valueToAddOrNull !== null ) {
						$propertyWithValues->addValue( $valueToAddOrNull );
					}
				}
			}
		}

		return $propertyWithValues;
	}

	private function conditionMatches( array $subfields ): bool {
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
