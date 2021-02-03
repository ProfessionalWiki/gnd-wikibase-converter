<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\InvalidMapping;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\ConcatValueSource;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\ValueSource;

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
			$this->getValueSourceFromPropertyMappingArray( $propertyJson ),
			$this->getSubfieldConditionFromPropertyMappingArray( $propertyJson ),
			$propertyJson['valueMap'] ?? []
		);
	}

	private function getValueSourceFromPropertyMappingArray( array $propertyMapping ): ValueSource {
		if ( is_array( $propertyMapping['subfield'] ) ) {
			if ( array_key_exists( 'position', $propertyMapping ) ) {
				throw new InvalidMapping( 'Cannot have both "position" and a "subfield concatenation map"' );
			}

			return new ConcatValueSource( $propertyMapping['subfield'] );
		}

		return new SingleSubfieldSource(
			$propertyMapping['subfield'],
			array_key_exists( 'position', $propertyMapping ) ? (int)$propertyMapping['position'] : null
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
