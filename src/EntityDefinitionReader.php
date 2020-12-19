<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class EntityDefinitionReader {

	public function propertyDefinitionsFromJsonArray( array $json ): PropertyDefinitionList {
		$properties = [];

		foreach ( $json as $picaField => $mappings ) {
			foreach ( $mappings as $propertyId => $propertyMapping ) {
				if ( array_key_exists( 'type', $propertyMapping ) ) {
					$properties[] = new PropertyDefinition(
						propertyId: $propertyId,
						propertyType: $propertyMapping['type'],
						labels: $propertyMapping['labels'] ?? [],
					);
				}
			}
		}

		return new PropertyDefinitionList( ...$properties );
	}

}
