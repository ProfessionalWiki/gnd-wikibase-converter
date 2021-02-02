<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\WikibaseRecord;

/**
 * @internal
 */
class Converter {

	private Mapping $mapping;

	public static function fromArrayMapping( array $mapping ): self {
		return new self( ( new MappingDeserializer() )->jsonArrayToObject( $mapping ) );
	}

	public function __construct( Mapping $mapping ) {
		$this->mapping = $mapping;
	}

	public function picaToWikibase( PicaRecord $pica ): WikibaseRecord {
		$wikibaseRecord = new WikibaseRecord();

		foreach ( $pica->getFields() as $field ) {
			$propertyMappings = $this->mapping->getPropertyMappings( $field['name'] );

			if ( $propertyMappings !== [] ) {
				$subfieldsAsMap = $this->getSubfieldsAsMap( $field['subfields'] );

				foreach ( $propertyMappings as $propertyMapping ) {
					$wikibaseRecord->addValuesOfOneProperty( $propertyMapping->convert( $subfieldsAsMap ) );
				}
			}
		}

		return $wikibaseRecord;
	}

	/**
	 * TODO: move to PicaRecord
	 *
	 * @param array{array{name: string, value: string}} $subfields
	 */
	private function getSubfieldsAsMap( array $subfields ): Subfields {
		$map = [];

		foreach ( $subfields as $subfield ) {
			$map[$subfield['name']] = $subfield['value'];
		}

		return Subfields::newFromMap( $map );
	}

}
