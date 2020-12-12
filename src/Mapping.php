<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

	public static function newEmpty(): self {
		return new self( [] );
	}

	public static function newFromArray( array $mappingInJsonFormat ): self {
		return ( new MappingDeserializer() )->jsonArrayToObject( $mappingInJsonFormat );
	}

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

	/**
	 * @return PropertyDefinition[]
	 */
	public function getPropertyDefinitions(): array {
		$properties = [];

		foreach ( $this->propertyMappingsPerField as $propertyMappings ) {
			foreach ( $propertyMappings as $propertyMapping ) {
				$properties[] = new PropertyDefinition(
					propertyId: $propertyMapping->propertyId,
					propertyType: $propertyMapping->propertyType,
				);
			}
		}

		return $properties;
	}

}
