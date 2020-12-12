<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class WikibaseRecord {

	private array $map = [];

	public function __construct( PropertyWithValues ...$propertiesWithValues ) {
		$this->map = $propertiesWithValues;
	}

	// TODO: use DataModel
	public function addValuesOfOneProperty( PropertyWithValues $propertyWithValues ) {
		$this->map[$propertyWithValues->getPropertyId()] = new PropertyWithValues(
			$propertyWithValues->getPropertyId(),
			array_merge(
				$this->getValuesForProperty( $propertyWithValues->getPropertyId() ),
				$propertyWithValues->getValues()
			)
		);
	}

	/**
	 * @return string[]
	 */
	public function getPropertyIds(): array {
		return array_keys( $this->map );
	}

	/**
	 * @return string[]
	 */
	public function getValuesForProperty( string $propertyId ): array {
		if ( array_key_exists( $propertyId, $this->map ) ) {
			return $this->map[$propertyId]->getValues();
		}

		return [];
	}

}
