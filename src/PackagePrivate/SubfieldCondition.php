<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

/**
 * @internal
 */
class SubfieldCondition {

	private string $subfieldName;
	private string $subfieldValue;

	public function __construct( string $subfieldName, string $subfieldValue ) {
		$this->subfieldName = $subfieldName;
		$this->subfieldValue = $subfieldValue;
	}

	public function matches( array $subfields ): bool {
		return $this->subfieldValue === $this->getSubfieldValueOrNull( $subfields );
	}

	private function getSubfieldValueOrNull( array $subfields ): ?string {
		return $this->getSubfieldsAsMap( $subfields )[$this->subfieldName] ?? null;
	}

	private function getSubfieldsAsMap( array $subfields ): array {
		$map = [];

		foreach ( $subfields as $subfield ) {
			$map[$subfield['name']] = $subfield['value'];
		}

		return $map;
	}

}
