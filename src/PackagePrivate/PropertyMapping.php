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

	/** @var QualifierMapping[] */
	private array $qualifierMappings;

	/**
	 * @param QualifierMapping[] $qualifierMappings
	 */
	public function __construct(
		string $propertyId,
		ValueSource $valueSource,
		?SubfieldCondition $condition = null,
		?ValueMap $valueMap = null,
		array $qualifierMappings = []
	) {
		$this->propertyId = $propertyId;
		$this->valueSource = $valueSource;
		$this->condition = $condition;
		$this->valueMap = $valueMap ?? new ValueMap( [] );
		$this->qualifierMappings = $qualifierMappings;
	}

	/**
	 * @return GndStatement[]
	 */
	public function convert( Subfields $subfields ): array {
		if ( !$this->conditionMatches( $subfields ) ) {
			return [];
		}

		$valuesToAdd = $this->valueSource->valueFromSubfields( $subfields );

		if ( $valuesToAdd === [] ) {
			return [];
		}

		$mappedValueOrNull = $this->valueMap->map( $valuesToAdd[0] );

		if ( $mappedValueOrNull === null ) {
			return [];
		}

		return [
			new GndStatement(
				$this->propertyId,
				$mappedValueOrNull,
				$this->extractQualifiers( $subfields )
			)
		];
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

		foreach ( $this->qualifierMappings as $qualifierMapping ) {
			foreach ( $qualifierMapping->qualifiersFromSubfields( $subfields ) as $qualifier ) {
				$qualifiers[] = $qualifier;
			}
		}

		return $qualifiers;
	}

}
