<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class GndQualifier {

	private string $propertyId;
	private string $value;

	public function __construct( string $propertyId, string $value ) {
		$this->propertyId = $propertyId;
		$this->value = $value;
	}

	public function getPropertyId(): string {
		return $this->propertyId;
	}

	public function getValue(): string {
		return $this->value;
	}

}
