<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class Converter {

	public static function fromArrayMapping( array $mapping ): self {
		return new self( ( new MappingDeserializer() )->jsonArrayToObject( $mapping ) );
	}

	public function __construct(
		private Mapping $mapping
	) {}

	public function picaToWikibase( PicaRecord $pica ): WikibaseRecord {
		$valuesPerProperty = new WikibaseRecord();

		foreach ( $pica->getFields() as $field ) {
			foreach ( $this->mapping->getFieldMapping( $field['name'] )->propertyMappings as $propertyMapping ) {
				foreach ( $field['subfields'] as $subfield ) {
					if ( $propertyMapping->shouldUseSubfieldValue( $subfield['name'] ) ) {
						$valuesPerProperty->addPropertyValue( $propertyMapping->propertyId, $subfield['value'] );
					}
				}
			}
		}

		return $valuesPerProperty;
	}

}
