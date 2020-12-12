<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyDefinition {

	public function __construct(
		public /** @readonly */ string $propertyId,
		public /** @readonly */ string $propertyType,
		public /** @readonly string[] */ array $labels = [],
	) {}

}
