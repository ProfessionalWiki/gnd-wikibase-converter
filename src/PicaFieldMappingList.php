<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PicaFieldMappingList {

	private array $fields = [];

	public function __construct( PicaFieldMapping ...$fieldMappings ) {
		foreach ( $fieldMappings as $mapping ) {
			$this->fields[$mapping->getName()] = $mapping;
		}
	}

	public function get( string $fieldName ): PicaFieldMapping {
		if ( $this->has( $fieldName ) ) {
			return $this->fields[$fieldName];
		}

		return new PicaFieldMapping( '', [] );
	}

	private function has( string $fieldName ): bool {
		return array_key_exists( $fieldName, $this->fields );
	}

}
