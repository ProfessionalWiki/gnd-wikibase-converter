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

//	public function get( string $id ): PropertyDefinition {
//		return $this->definitions[$id];
//	}

}
