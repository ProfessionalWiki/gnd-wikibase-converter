<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

/**
 * @internal
 */
final class Mapping {

	/**
	 * @var array<string, PropertyMapping[]>
	 */
	private array $propertyMappingsPerField = [];

	public function addPropertyMapping( string $picaFieldName, PropertyMapping $propertyMapping ): void {
		$this->propertyMappingsPerField[$picaFieldName][] = $propertyMapping;
	}

	/**
	 * @return PropertyMapping[]
	 */
	public function getPropertyMappings( string $picaFieldName ): array {
		return $this->propertyMappingsPerField[$picaFieldName] ?? [];
	}

}
