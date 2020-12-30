<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter\PackagePrivate;

/**
 * @internal
 */
class PicaFieldMapping {

	private string $name;
	private array $propertyMappings;

	/**
	 * @param string $name
	 * @param PropertyMapping[] $propertyMappings
	 */
	public function __construct( string $name, array $propertyMappings ) {
		$this->name = $name;
		$this->propertyMappings = $propertyMappings;
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return PropertyMapping[]
	 */
	public function getPropertyMappings(): array {
		return $this->propertyMappings;
	}

}
