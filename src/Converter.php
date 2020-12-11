<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class Converter {

	public function __construct(
		private Mapping $mapping
	) {}

	public function picaToWikibase( PicaRecord $pica ): WikibaseRecord {
		$valuesPerProperty = new WikibaseRecord();

		foreach ( $pica->getFields() as $field ) {
			foreach ( $this->mapping->getPropertyMappings( $field['name'] ) as $propertyMapping ) {
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
