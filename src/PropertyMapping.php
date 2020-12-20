<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyMapping {

	public function __construct(
		private /** @readonly */ string $propertyId,
		private /** @readonly string[] */ array $subfields,
		private /** @readonly */ ?int $position = null,
		private /** @readonly */ ?SubfieldCondition $condition = null,
		private /** @readonly string[] */ array $valueMap = []
	) {}

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
			$subfieldMap = $this->getSubfieldsAsMap( $subfields );

			if ( array_key_exists( $this->condition->subfieldName(), $subfieldMap ) ) {
				return $subfieldMap[$this->condition->subfieldName()] === $this->condition->subfieldValue();
			}

			return false;
		}

		return true;
	}

	private function getSubfieldsAsMap( array $subfields ): array {
		$map = [];

		foreach ( $subfields as $subfield ) {
			$map[$subfield['name']] = $subfield['value'];
		}

		return $map;
	}

	private function getSubfieldValue( string $subfieldValue ): ?string {
		$subfieldValue = $this->extractFromSubfieldValue( $subfieldValue );

		if ( $this->valueMap === [] ) {
			return $subfieldValue;
		}

		if ( array_key_exists( $subfieldValue, $this->valueMap ) ) {
			return $this->valueMap[$subfieldValue];
		}

		return null;
	}

	private function extractFromSubfieldValue( string $subfieldValue ): string {
		if ( $this->position === null ) {
			return $subfieldValue;
		}

		return substr( $subfieldValue, $this->position -1, 1 );
	}

}
