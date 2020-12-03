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
				);
			}
		}

		return new Mapping( $propertyMappings );
	}

}
