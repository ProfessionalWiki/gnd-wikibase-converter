<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		return new Mapping(
			$this->fieldMappingsFromJsonArray( $json )
		);
	}

	private function fieldMappingsFromJsonArray( array $json ): PicaFieldMappingList {
		$fieldMappings = [];

		foreach ( $json as $picaField => $mappings ) {
			$fieldMappings[] = new PicaFieldMapping(
				$picaField,
				$this->propertyMappingsFromJsonArray( $mappings )
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
				$propertyId,
				$propertyMapping['subfields'] ?? [],
				$propertyMapping['position'] ?? null,
				$this->getSubfieldConditionFromPropertyMappingArray( $propertyMapping ),
				$this->getValueMapFromPropertyMappingArray( $propertyMapping )
			);
		}

		return $propertyMappings;
	}

	private function getSubfieldConditionFromPropertyMappingArray( array $propertyMapping ): ?SubfieldCondition {
		if ( array_key_exists( 'conditions', $propertyMapping ) && array_key_exists( 0, $propertyMapping['conditions'] ) ) {
			$conditionArray = $propertyMapping['conditions'][0];
			return new SubfieldCondition( $conditionArray['subfield'], $conditionArray['equalTo'] );
		}

		return null;
	}

	private function getValueMapFromPropertyMappingArray( array $propertyMapping ): array {
		if ( array_key_exists( 'valueMap', $propertyMapping ) ) {
			return $this->jsonValueMapToMap( $propertyMapping['valueMap'] );
		}

		return [];
	}

	private function jsonValueMapToMap( array $jsonValueMap ): array {
		$valueMap = [];

		foreach ( $jsonValueMap as $picaValue => $wikibaseValue ) {
			$valueMap[$picaValue] = $wikibaseValue['id'];
		}

		return $valueMap;
	}

}
