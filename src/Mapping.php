<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

	private array $propertyMappingsPerField;

	/**
	 * @param array<string, PropertyMapping[]> $propertyMappingsPerField
	 */
	public function __construct( array $propertyMappingsPerField ) {
		$this->propertyMappingsPerField = $propertyMappingsPerField;
	}

	/**
	 * @return PropertyMapping[]
	 */
	public function getPropertyMappings( string $picaFieldName ): array {
		return $this->propertyMappingsPerField[$picaFieldName] ?? [];
	}

}
