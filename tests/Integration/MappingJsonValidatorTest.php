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

	public function testMappingIsValid(): void {
		$this->assertTrue( MappingJsonValidator::newInstance()->validate(
			file_get_contents( __DIR__ . '/../../src/mapping.json' )
		) );
	}

	public function testEmptyConfigIsValid(): void {
		$this->assertTrue( MappingJsonValidator::newInstance()->validate(
			'{}'
		) );
	}

	public function testMinimalConfigIsValid(): void {
		$this->assertTrue( MappingJsonValidator::newInstance()->validate(
			'{"P1": { "field": "032T", "subfield": "a" }}'
		) );
	}

	/**
	 * @dataProvider invalidConfigProvider
	 */
	public function testInvalidSchemas( string $invalidConfig ): void {
		$this->assertFalse( MappingJsonValidator::newInstance()->validate( $invalidConfig ) );
	}

	public function invalidConfigProvider(): iterable {
		yield 'Missing field' => [ '{"P1": { "subfield": "a" }}' ];
		yield 'Missing subfield' => [ '{"P1": { "field": "032T" }}' ];
		yield 'Unknown property' => [ '{"P1": { "WTF IS THIS" => "???", "field": "032T", "subfield": "a" }}' ];
		yield 'Invalid property ID' => [ '{"P1INVALID": { "field": "032T", "subfield": "a" }}' ];
	}

}
