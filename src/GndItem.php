<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

/**
 * Information to construct a single Wikibase Item from.
 */
class GndItem {

	public const GND_ID = 'P360';
	public const GND_IDN_PID = self::GND_ID;
	public const INTERNAL_ID_PID = 'P360';
	private const NAME_PID_LIST = [ 'P58', 'P87', 'P90', 'P91', 'P94' ];

	/** @var array<string, array<int, GndStatement>> */
	private array $map = [];

	public function __construct( GndStatement ...$gndStatements ) {
		$this->addGndStatements( $gndStatements );
	}

	/**
	 * @param GndStatement[] $gndStatements
	 */
	public function addGndStatements( array $gndStatements ): void {
		foreach ( $gndStatements as $statement ) {
			$this->addGndStatement( $statement );
		}
	}

	public function addGndStatement( GndStatement $gndStatement ): void {
		$this->map[$gndStatement->getPropertyId()][] = $gndStatement;
	}

	/**
	 * @return array<int, string>
	 */
	public function getPropertyIds(): array {
		return array_keys( $this->map );
	}

	/**
	 * @return array<int, string>
	 */
	public function getMainValuesForProperty( string $propertyId ): array {
		$mainValues = [];

		foreach ( $this->map[$propertyId] ?? [] as $gndStatement ) {
			$mainValues[] = $gndStatement->getValue();
		}

		return $mainValues;
	}

	/**
	 * @return array<int, GndStatement>
	 */
	public function getStatementsForProperty( string $propertyId ): array {
		if ( array_key_exists( $propertyId, $this->map ) ) {
			return $this->map[$propertyId];
		}

		return [];
	}

	public function getNumericId(): ?int {
		foreach ( $this->getMainValuesForProperty( self::GND_IDN_PID ) as $gndId ) {
			return ( new IdConverter() )->gndToNumericId( $gndId );
		}

		return null;
	}

	public function getGermanLabel(): ?string {
		foreach ( self::NAME_PID_LIST as $pId ) {
			foreach ( $this->getMainValuesForProperty( $pId ) as $label ) {
				return $label;
			}
		}

		return null;
	}

	/**
	 * @return array<int, string>
	 */
	public function getGermanAliases(): array {
		return $this->getMainValuesForProperty( self::INTERNAL_ID_PID );
	}

}
