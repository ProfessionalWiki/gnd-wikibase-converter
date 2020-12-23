<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyDefinition {

	public string $propertyId;
	public string $propertyType;
	public array $labels = [];

	public function __construct(
		/** @readonly */ string $propertyId,
		/** @readonly */ string $propertyType,
		/** @readonly string[] */ array $labels = []
	) {
		$this->propertyId = $propertyId;
		$this->propertyType = $propertyType;
		$this->labels = $labels;
	}

}
