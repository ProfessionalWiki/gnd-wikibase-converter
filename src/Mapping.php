<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

	private PicaFieldMappingList $fieldMappings;

	public function __construct( PicaFieldMappingList $fieldMappings ) {
		$this->fieldMappings = $fieldMappings;
	}

	/**
	 * @return PropertyMapping[]
	 */
	public function getPropertyMappings( string $picaFieldName ): array {
		return $this->fieldMappings->get( $picaFieldName )->getPropertyMappings();
	}

}
