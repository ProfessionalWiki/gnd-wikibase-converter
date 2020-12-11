<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PicaRecord {

	public static function newEmpty(): self {
		return new self( [ 'fields' => [] ] );
	}

	public static function withFields( array $fields ): self {
		return new self( [ 'fields' => $fields ] );
	}

	/**
	 * @param array{array{name: string, subfields: array{array{name: string, value: string}}}} $jsonArray
	 */
	public function __construct(
		private array $jsonArray
	) {}

	/**
	 * @return array{array{name: string, subfields: array{array{name: string, value: string}}}}
	 */
	public function getFields(): array {
		return $this->jsonArray['fields'];
	}

}
