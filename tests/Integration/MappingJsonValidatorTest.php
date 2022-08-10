<?php

declare( strict_types = 1 );

namespace DNB\Tests\Integration;

use DNB\WikibaseConverter\PackagePrivate\MappingJsonValidator;
use PHPUnit\Framework\TestCase;

class MappingJsonValidatorTest extends TestCase {

	public function testMappingExampleIsValid(): void {
		$this->assertTrue( MappingJsonValidator::newInstance()->validate(
			file_get_contents( __DIR__ . '/../../mapping-example.json' )
		) );
	}

	// TODO: uncomment test if and when mapping.json file gets fixed
//	public function testMappingIsValid(): void {
//		$this->assertTrue( MappingJsonValidator::newInstance()->validate(
//			file_get_contents( __DIR__ . '/../../src/mapping.json' )
//		) );
//	}



}
