<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyMapping {

	public function __construct(
		private /** @readonly */ string $propertyId,
		private /** @readonly string[] */ array $subfields,
		private /** @readonly */ ?SubfieldCondition $condition = null,
		private /** @readonly string[] */ array $valueMap = []
	) {}

	public function convert( array $subfields ): PropertyWithValues {
		$propertyWithValues = new PropertyWithValues( $this->propertyId );

		if ( $this->conditionMatches( $subfields ) ) {
			foreach ( $subfields as $subfield ) {
				$valueToAddOrNull = $this->getSubfieldValue( $subfield );

				if ( $valueToAddOrNull !== null ) {
					$propertyWithValues->addValue( $valueToAddOrNull );
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

	private function getSubfieldValue( array $subfield ): ?string {
		if ( !in_array( $subfield['name'], $this->subfields ) ) {
			return null;
		}

		if ( $this->valueMap === [] ) {
			return $subfield['value'];
		}

		if ( array_key_exists( $subfield['value'], $this->valueMap ) ) {
			return $this->valueMap[$subfield['value']];
		}

		return null;
	}

	private function getSubfieldsAsMap( array $subfields ): array {
		$map = [];

		foreach ( $subfields as $subfield ) {
			$map[$subfield['name']] = $subfield['value'];
		}

		return $map;
	}

}
