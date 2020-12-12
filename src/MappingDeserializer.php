<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		return new Mapping(
			$this->fieldMappingsFromJsonArray( $json ),
			$this->propertyDefinitionsFromJsonArray( $json )
		);
	}

	private function fieldMappingsFromJsonArray( array $json ): PicaFieldMappingList {
		$fieldMappings = [];

		foreach ( $json as $picaField => $mappings ) {
			$fieldMappings[] = new PicaFieldMapping(
				name: $picaField,
				propertyMappings: $this->propertyMappingsFromJsonArray( $mappings )
			);
		}

		return new PicaFieldMappingList( ...$fieldMappings );
	}

	/**
	 * @return PropertyMapping[]
	 */
	private function propertyMappingsFromJsonArray( array $mappings ): array {
		$propertyMappings = [];

		foreach ( $mappings as $propertyId => $propertyMapping ) {
			$propertyMappings[] = new PropertyMapping(
				propertyId: $propertyId,
				subfields: $propertyMapping['subfields'] ?? [],
				useCondition: array_key_exists( 'conditions', $propertyMapping ), // TODO
			);
		}

		return $propertyMappings;
	}

	private function propertyDefinitionsFromJsonArray( array $json ): PropertyDefinitionList {
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
