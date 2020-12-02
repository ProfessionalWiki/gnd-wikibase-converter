<?php

declare( strict_types = 1 );

namespace GND\WikibaseConverter;

class Converter {

	private const GND_ID = '007K';

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
