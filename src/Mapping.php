<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

	public static function newEmpty(): self {
		return new self( [], new PropertyDefinitionList() );
	}

	public static function newFromArray( array $mappingInJsonFormat ): self {
		return ( new MappingDeserializer() )->jsonArrayToObject( $mappingInJsonFormat );
	}

	private array $fieldMappings = [];
	private PropertyDefinitionList $properties;

	/**
	 * @param PicaFieldMapping[] $fieldMappings
	 * @param PropertyDefinitionList $properties
	 */
	public function __construct( array $fieldMappings, PropertyDefinitionList $properties ) {
		foreach ( $fieldMappings as $fieldMapping ) {
			$this->fieldMappings[$fieldMapping->name] = $fieldMapping;
		}

		$this->properties = $properties;
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

	public function getProperties(): PropertyDefinitionList {
		return $this->properties;
	}

}
