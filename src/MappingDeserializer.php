<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		$propertyMappings = [];

		foreach ( $json as $picaField => $mappings ) {
			foreach ( $mappings as $propertyId => $propertyMapping ) {
				$propertyMappings[$picaField][$propertyId] = new PropertyMapping(
					propertyId: $propertyId,
					propertyType: $propertyMapping['type'],
					subfields: $propertyMapping['subfields'] ?? [],
				);
			}
		}

		$fieldMappings = [];

		foreach ( $propertyMappings as $picaField => $mappings ) {
			$fieldMappings[$picaField] = new PicaFieldMapping(
				name: $picaField,
				propertyMappings: $mappings
			);
		}

		return new Mapping( $fieldMappings );
	}

}
