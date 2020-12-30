<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

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
		$this->jsonArray = $jsonArray;
	}

	/**
	 * @return array{array{name: string, subfields: array{array{name: string, value: string}}}}
	 */
	public function getFields(): array {
		return $this->jsonArray['fields'];
	}

}
