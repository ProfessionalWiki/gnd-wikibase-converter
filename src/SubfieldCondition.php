<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class SubfieldCondition {

	private string $subfieldName;
	private string $subfieldValue;

	public function __construct( string $subfieldName, string $subfieldValue ) {
		$this->subfieldName = $subfieldName;
		$this->subfieldValue = $subfieldValue;
	}

	public function name(): string {
		return $this->subfieldName;
	}

	public function value(): string {
		return $this->subfieldValue;
	}

}
