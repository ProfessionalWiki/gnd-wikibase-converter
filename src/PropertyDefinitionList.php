<?php

declare( strict_types = 1 );

namespace DNB\WikibaseConverter;

class PropertyDefinitionList {

	private array $definitions = [];

	public function __construct( PropertyDefinition ...$definitions ) {
		foreach ( $definitions as $definition ) {
			$this->definitions[$definition->propertyId] = $definition;
		}
	}

	/**
	 * @return PropertyDefinition[]
	 * @psalm-return array<string, PropertyDefinition>
	 */
	public function asArray(): array {
		return $this->definitions;
	}

//	public function get( string $id ): PropertyDefinition {
//		return $this->definitions[$id];
//	}

}
