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
			$this->addPropertyMapping( $mapping, $propertyId, $propertyJson );
		}

		return $mapping;
	}

	private function addPropertyMapping( Mapping $mapping, string $propertyId, array $propertyJson ): void {
		if ( array_key_exists( 'field', $propertyJson ) ) {
			$mapping->addPropertyMapping(
				$propertyJson['field'],
				$this->buildPropertyMapping( $propertyId, $propertyJson )
			);
		}
		else if ( array_key_exists( 0, $propertyJson ) ) {
			foreach ( $propertyJson as $forOneField ) {
				$this->addPropertyMapping( $mapping, $propertyId, $forOneField );
			}
		}
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

	private function buildValueMap( array $propertyJson ): ValueMap {
		return new ValueMap( $propertyJson['valueMap'] ?? [] );
	}

	/**
	 * @return QualifierMapping[]
	 */
	private function buildQualifiers( array $propertyJson ): array {
		$qualifiers = [];

		foreach ( $propertyJson['qualifiers'] ?? [] as $propertyId => $qualifierJson ) {
			if ( is_string( $qualifierJson ) || array_key_exists( 'subfield', $qualifierJson ) ) {
				$qualifiers[] = new QualifierMapping(
					$propertyId,
					new SingleSubfieldSource(
						is_string( $qualifierJson ) ? $qualifierJson : $qualifierJson['subfield']
					),
					new ValueMap( is_string( $qualifierJson ) ? [] : ( $qualifierJson['valueMap'] ?? [] ) )
				);
			}
		}

		return $qualifiers;
	}

}
