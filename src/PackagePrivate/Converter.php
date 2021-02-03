<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\GndItem;

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

	public function picaToWikibase( PicaRecord $pica ): GndItem {
		$gndItem = new GndItem();

		foreach ( $pica->getFields() as $field ) {
			$propertyMappings = $this->mapping->getPropertyMappings( $field['name'] );

			if ( $propertyMappings !== [] ) {
				$subfieldsAsMap = $pica->getSubfieldsFromField( $field );

				foreach ( $propertyMappings as $propertyMapping ) {
					$gndItem->addGndStatements( $propertyMapping->convert( $subfieldsAsMap ) );
				}
			}
		}

		return $gndItem;
	}

}
