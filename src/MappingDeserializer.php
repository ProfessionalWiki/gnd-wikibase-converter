<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		$properties = [];
		$propertyMappings = [];

		foreach ( $json as $picaField => $mappings ) {
			foreach ( $mappings as $propertyId => $propertyMapping ) {
				$propertyMappings[$picaField][] = new PropertyMapping(
					propertyId: $propertyId,
					subfields: $propertyMapping['subfields'] ?? [],
				);

				if ( array_key_exists( 'type', $propertyMapping ) ) {
					$properties[] = new PropertyDefinition(
						propertyId: $propertyId,
						propertyType: $propertyMapping['type'],
					);
				}
			}
		}

		$fieldMappings = [];

		foreach ( $propertyMappings as $picaField => $mappings ) {
			$fieldMappings[] = new PicaFieldMapping(
				name: $picaField,
				propertyMappings: $mappings
			);
		}

		return new Mapping(
			new PicaFieldMappingList( ...$fieldMappings ),
			new PropertyDefinitionList( ...$properties )
		);
	}

}
