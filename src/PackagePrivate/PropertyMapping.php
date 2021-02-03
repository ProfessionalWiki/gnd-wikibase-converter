<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\PackagePrivate\ValueSource\ValueSource;
use DNB\WikibaseConverter\GndStatement;

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
		if ( $this->conditionMatches( $subfields ) ) {
			$valueToAddOrNull = $this->valueSource->valueFromSubfields( $subfields );

			if ( $valueToAddOrNull !== null ) {
				$mappedValueOrNull = $this->getMappedValue( $valueToAddOrNull );

				if ( $mappedValueOrNull !== null ) {
					return [ new GndStatement( $this->propertyId, $mappedValueOrNull ) ];
				}
			}
		}

		return [];
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

}
