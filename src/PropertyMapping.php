<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyMapping {

	public function __construct(
		public /** @readonly */ string $propertyId,
		private /** @readonly string[] */ array $subfields,
		private /** @readonly */ bool $useCondition = false // TODO
	) {}

	public function shouldUseSubfieldValue( string $subfieldName ): bool {
		return in_array( $subfieldName, $this->subfields );
	}

}
