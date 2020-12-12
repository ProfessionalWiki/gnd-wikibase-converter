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

	private array $fieldMappings = [];

	/**
	 * @param PicaFieldMapping[] $fieldMappings
	 */
	public function __construct( array $fieldMappings ) {
		foreach ( $fieldMappings as $fieldMapping ) {
			$this->fieldMappings[$fieldMapping->name] = $fieldMapping;
		}
	}

	/**
	 * @return PropertyMapping[]
	 */
	public function getPropertyMappings( string $picaFieldName ): array {
		if ( array_key_exists( $picaFieldName, $this->fieldMappings ) ) {
			return $this->fieldMappings[$picaFieldName]->propertyMappings;
		}

		return [];
	}

	/**
	 * @return PropertyDefinition[]
	 */
	public function getPropertyDefinitions(): array {
		$properties = [];

		foreach ( $this->fieldMappings as $fieldMapping ) {
			foreach ( $fieldMapping->propertyMappings as $propertyMapping ) {
				$properties[] = new PropertyDefinition(
					propertyId: $propertyMapping->propertyId,
					propertyType: $propertyMapping->propertyType,
				);
			}
		}

		return $properties;
	}

}
