<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\GndStatement;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\ValueSource;

/**
 * @internal
 */
class PropertyMapping {

	private string $propertyId;
	private ValueSource $valueSource;
	private ?SubfieldCondition $condition;

	/** @var array<string, string> */
	private array $valueMap;
	/** @var array<string, string> */
	private array $qualifiers;

	/**
	 * @param array<string, string> $valueMap
	 * @param array<string, string> $qualifiers
	 */
	public function __construct(
		string $propertyId,
		ValueSource $valueSource,
		?SubfieldCondition $condition = null,
		array $valueMap = [],
		array $qualifiers = []
	) {
		$this->propertyId = $propertyId;
		$this->valueSource = $valueSource;
		$this->condition = $condition;
		$this->valueMap = $valueMap;
		$this->qualifiers = $qualifiers;
	}

	/**
	 * @return GndStatement[]
	 */
	public function convert( Subfields $subfields ): array {
		if ( !$this->conditionMatches( $subfields ) ) {
			return [];
		}

		$valueToAddOrNull = $this->valueSource->valueFromSubfields( $subfields );

		if ( $valueToAddOrNull === null ) {
			return [];
		}

		$mappedValueOrNull = $this->getMappedValue( $valueToAddOrNull );

		if ( $mappedValueOrNull === null ) {
			return [];
		}

		return [ new GndStatement(
			$this->propertyId,
			$mappedValueOrNull,
			$this->extractQualifiers( $subfields )
		) ];
	}

	private function conditionMatches( Subfields $subfields ): bool {
		if ( $this->condition instanceof SubfieldCondition ) {
			return $this->condition->matches( $subfields );
		}

		return true;
	}

	private function getMappedValue( string $subfieldValue ): ?string {
		if ( $this->valueMap === [] ) {
			return $subfieldValue;
		}

		if ( array_key_exists( $subfieldValue, $this->valueMap ) ) {
			return $this->valueMap[$subfieldValue];
		}

		return null;
	}

	/**
	 * @return array<int, GndQualifier>
	 */
	private function extractQualifiers( Subfields $subfields ): array {
		$qualifiers = [];

		foreach ( $this->qualifiers as $propertyId => $subfieldName ) {
			if ( array_key_exists( $subfieldName, $subfields->map ) ) {
				foreach ( $subfields->map[$subfieldName] as $subfieldValue ) {
					$qualifiers[] = new GndQualifier( $propertyId, $subfieldValue );
				}
			}
		}

		return $qualifiers;
	}

}
