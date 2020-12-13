<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyMapping {

	public function __construct(
		public /** @readonly */ string $propertyId,
		private /** @readonly string[] */ array $subfields,
		private /** @readonly */ ?SubfieldCondition $condition = null
	) {}

	public function convert( array $subfields ): PropertyWithValues {
		$propertyWithValues = new PropertyWithValues( $this->propertyId );

		foreach ( $subfields as $subfield ) {
			if ( $this->shouldUseSubfieldValue( $subfield['name'] ) ) {
				$propertyWithValues->addValue( $subfield['value'] );
			}
		}

		return $propertyWithValues;
	}

	private function shouldUseSubfieldValue( string $subfieldName ): bool {
		return $this->condition === null && in_array( $subfieldName, $this->subfields );
	}

}
