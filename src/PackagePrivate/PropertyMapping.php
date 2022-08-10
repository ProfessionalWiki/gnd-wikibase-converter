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
	private ValueMap $valueMap;

	/** @var array<string, string> */
	private array $qualifiers;

	/**
	 * @param array<string, string> $qualifiers
	 */
	public function __construct(
		string $propertyId,
		ValueSource $valueSource,
		?SubfieldCondition $condition = null,
		?ValueMap $valueMap = null,
		array $qualifiers = []
	) {
		$this->propertyId = $propertyId;
		$this->valueSource = $valueSource;
		$this->condition = $condition;
		$this->valueMap = $valueMap ?? new ValueMap( [] );
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

		$mappedValueOrNull = $this->valueMap->map( $valueToAddOrNull );

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
