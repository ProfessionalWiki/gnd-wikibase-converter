<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

/**
 * @internal
 */
class MappingDeserializer {

	public function jsonArrayToObject( array $json ): Mapping {
		$mapping = new Mapping();

		foreach ( $json as $propertyId => $propertyJson ) {
			$mapping->addPropertyMapping(
				$propertyJson['field'],
				$this->propertyMappingFromJsonArray( $propertyId, $propertyJson )
			);
		}

		return $mapping;
	}

	private function propertyMappingFromJsonArray( string $propertyId, array $propertyJson ): PropertyMapping {
		return new PropertyMapping(
			$propertyId,
			$propertyJson['subfield'],
			$propertyJson['position'] ?? null,
			$this->getSubfieldConditionFromPropertyMappingArray( $propertyJson ),
			$propertyJson['valueMap'] ?? []
		);
	}

	private function getSubfieldConditionFromPropertyMappingArray( array $propertyMapping ): ?SubfieldCondition {
		if ( array_key_exists( 'condition', $propertyMapping ) ) {
			return new SubfieldCondition(
				$propertyMapping['condition']['subfield'],
				$propertyMapping['condition']['equalTo']
			);
		}

		return null;
	}

}
