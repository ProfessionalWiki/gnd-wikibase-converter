<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

	public static function newEmpty(): self {
		return new self( new PicaFieldMappingList(), new PropertyDefinitionList() );
	}

	private PicaFieldMappingList $fieldMappings;
	private PropertyDefinitionList $properties;

	public function __construct( PicaFieldMappingList $fieldMappings, PropertyDefinitionList $properties ) {
		$this->fieldMappings = $fieldMappings;
		$this->properties = $properties;
	}

	/**
	 * @return PropertyMapping[]
	 */
	public function getPropertyMappings( string $picaFieldName ): array {
		return $this->fieldMappings->get( $picaFieldName )->getPropertyMappings();
	}

	public function getProperties(): PropertyDefinitionList {
		return $this->properties;
	}

}
