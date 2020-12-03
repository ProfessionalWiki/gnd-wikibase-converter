<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyMapping {

	public function __construct(
		public /** @readonly */ string $propertyId,
		public /** @readonly */ string $propertyType,

//		private ?SubfieldMatcher $picaSubfield = null,
	) {}

	public function shouldUseSubfieldValue( string $subfieldName ): bool {
		return true;
	}

//	public string $picaFieldName;
//	public string $picaSubfieldName;
//	public string $picaSubfieldConditionName;
//	public string $picaSubfieldConditionValue;

}
