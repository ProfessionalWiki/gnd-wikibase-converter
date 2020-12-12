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
		$wikibaseRecord = new WikibaseRecord();

		foreach ( $pica->getFields() as $field ) {
			foreach ( $this->mapping->getPropertyMappings( $field['name'] ) as $propertyMapping ) {
				$wikibaseRecord->addValuesOfOneProperty( $propertyMapping->convert( $field['subfields'] ) );
			}
		}

		return $wikibaseRecord;
	}

}
