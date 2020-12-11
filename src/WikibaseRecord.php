<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class WikibaseRecord {

	private array $map = [];

	public function __construct() {
	}

	public function addPropertyValue( string $propertyId, string $value ) {
		$this->map[$propertyId][] = $value;
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
		return $this->map[$propertyId];
	}

}
