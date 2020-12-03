<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

use DNB\WikibaseConverter\Wikibase\ValuesPerProperty;

class Converter {

	private const GND_ID = '007K';

	public function __construct(
		private Mapping $mapping
	) {}

	public function picaToValuesPerProperty( Pica $pica ): ValuesPerProperty {
		$valuesPerProperty = new ValuesPerProperty();

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

	/**
	 * @psalm-pure
	 * @param array{fields: array{array{name: string, subfields: array{array{name: string, value: string}}}}} $pica
	 */
	public function getIdFromPica( array $pica ): string {
		foreach ( $pica['fields'] as $field ) {
			if ( $field['name'] === self::GND_ID ) {
				$properties = [];

				foreach ( $field['subfields'] as $subfield ) {
					$properties[$subfield['name']] = $subfield['value'];
				}

				if ( $properties['a'] === 'gnd' ) {
					/** @psalm-suppress InvalidArrayOffset */
					return $properties['0'];
				}
			}
		}

		return 'TODO';
	}

}
