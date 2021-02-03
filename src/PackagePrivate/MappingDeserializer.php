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
				$this->buildPropertyMapping( $propertyId, $propertyJson )
			);
		}

		return $mapping;
	}

	private function buildPropertyMapping( string $propertyId, array $propertyJson ): PropertyMapping {
		return new PropertyMapping(
			$propertyId,
			$this->buildValueSource( $propertyJson ),
			$this->buildSubfieldCondition( $propertyJson ),
			$this->buildValueMap( $propertyJson ),
			$this->buildQualifiers( $propertyJson )
		);
	}

	private function buildValueSource( array $propertyMapping ): ValueSource {
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

	private function buildSubfieldCondition( array $propertyMapping ): ?SubfieldCondition {
		if ( array_key_exists( 'condition', $propertyMapping ) ) {
			return new SubfieldCondition(
				$propertyMapping['condition']['subfield'],
				$propertyMapping['condition']['equalTo']
			);
		}

		return null;
	}

	/**
	 * @return array<string, string>
	 */
	private function buildValueMap( array $propertyJson ): array {
		return $propertyJson['valueMap'] ?? [];
	}

	/**
	 * @return array<string, string>
	 */
	private function buildQualifiers( array $propertyJson ): array {
		return $propertyJson['qualifiers'] ?? [];
	}

}
