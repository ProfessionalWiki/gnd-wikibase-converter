<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class GndStatement {

	private string $propertyId;
	private string $value;
	private array $qualifiers;

	/**
	 * @param GndQualifier[] $qualifiers
	 */
	public function __construct( string $propertyId, string $value, array $qualifiers = [] ) {
		$this->propertyId = $propertyId;
		$this->value = $value;
		$this->qualifiers = $qualifiers;
	}

	public function getValue(): string {
		return $this->value;
	}

	public function getPropertyId(): string {
		return $this->propertyId;
	}

}
