<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PicaFieldMapping {

	public function __construct(
		public /** @readonly */ string $name,
		public /** @readonly PropertyMapping[] */ array $propertyMappings,
	) {}

}
