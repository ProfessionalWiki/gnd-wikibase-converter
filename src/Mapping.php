<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

final class Mapping {

	public static function newEmpty(): self {
		return new self( new PicaFieldMappingList(), new PropertyDefinitionList() );
	}

	public static function newFromArray( array $mappingInJsonFormat ): self {
		return ( new MappingDeserializer() )->jsonArrayToObject( $mappingInJsonFormat );
	}

	private PicaFieldMappingList $fieldMappings;
	private PropertyDefinitionList $properties;

	public function __construct( PicaFieldMappingList $fieldMappings, PropertyDefinitionList $properties ) {
		$this->fieldMappings = $fieldMappings;
		$this->properties = $properties;
	}

	public function getFieldMapping( string $picaFieldName ): PicaFieldMapping {
		return $this->fieldMappings->get( $picaFieldName );
	}

	public function getProperties(): PropertyDefinitionList {
		return $this->properties;
	}

}
