<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class GndStatement {

	private string $propertyId;
	private string $value;

	/**
	 * @var array<int, GndQualifier>
	 */
	private array $qualifiers;

	/**
	 * @param array<int, GndQualifier>  $qualifiers
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

	/**
	 * @return array<int, GndQualifier>
	 */
	public function getQualifiers(): array {
		return $this->qualifiers;
	}

}
