<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		$properties = [];
		$propertyMappings = [];

		foreach ( $json as $picaField => $mappings ) {
			foreach ( $mappings as $propertyId => $propertyMapping ) {
				$propertyMappings[$picaField][$propertyId] = new PropertyMapping(
					propertyId: $propertyId,
					propertyType: $propertyMapping['type'],
					subfields: $propertyMapping['subfields'] ?? [],
				);

				$properties[] = new PropertyDefinition(
					propertyId: $propertyId,
					propertyType: $propertyMapping['type'],
				);
			}
		}

		$fieldMappings = [];

		foreach ( $propertyMappings as $picaField => $mappings ) {
			$fieldMappings[] = new PicaFieldMapping(
				name: $picaField,
				propertyMappings: $mappings
			);
		}

		return new Mapping( $fieldMappings, new PropertyDefinitionList( ...$properties ) );
	}

}
