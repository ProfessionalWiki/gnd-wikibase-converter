<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		$properties = [];
		$fieldMappings = [];


		foreach ( $json as $picaField => $mappings ) {
			$propertyMappings = [];

			foreach ( $mappings as $propertyId => $propertyMapping ) {
				$propertyMappings[] = new PropertyMapping(
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

			$fieldMappings[] = new PicaFieldMapping(
				name: $picaField,
				propertyMappings: $propertyMappings
			);
		}

		return new Mapping(
			new PicaFieldMappingList( ...$fieldMappings ),
			new PropertyDefinitionList( ...$properties )
		);
	}

}
