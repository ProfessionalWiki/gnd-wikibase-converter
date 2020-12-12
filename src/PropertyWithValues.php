<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

/**
 * Converted values for a single Wikibase property.
 * Analogous to a "statement group" in the Wikibase DataModel.
 */
class PropertyWithValues {

	private string $propertyId;
	private array $values;

	public function __construct( string $propertyId, array $values = [] ) {
		$this->propertyId = $propertyId;
		$this->values = $values;
	}

	// TODO: use DataModel
	public function addValue( string $value ) {
		$this->values[] = $value;
	}

	/**
	 * @return string[]
	 */
	public function getValues(): array {
		return $this->values;
	}

	public function getPropertyId(): string {
		return $this->propertyId;
	}

}
