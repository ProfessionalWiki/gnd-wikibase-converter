<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

use DNB\WikibaseConverter\InvalidPica;

/**
 * @internal
 */
class PicaRecord {

	private array $jsonArray;

	public static function newEmpty(): self {
		return new self( [ 'fields' => [] ] );
	}

	/**
	 * @param array{array{name: string, subfields: array{array{name: string, value: string}}}} $fields
	 */
	public static function withFields( array $fields ): self {
		return new self( [ 'fields' => $fields ] );
	}

	/**
	 * @param array{fields: array{array{name: string, subfields: array{array{name: string, value: string}}}}} $jsonArray
	 */
	public function __construct( array $jsonArray ) {
		if ( !array_key_exists( 'fields', $jsonArray ) ) {
			throw new InvalidPica( 'fields key missing' );
		}

		if ( !is_array( $jsonArray['fields'] ) ) {
			throw new InvalidPica( 'fields is nto an array' );
		}

		$this->jsonArray = $jsonArray;
	}

	/**
	 * @return array{array{name: string, subfields: array{array{name: string, value: string}}}}
	 */
	public function getFields(): array {
		return $this->jsonArray['fields'];
	}

}
