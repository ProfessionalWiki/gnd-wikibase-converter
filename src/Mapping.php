<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

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
		return [];
	}

}
