<?php

declare( strict_types = 1 );

namespace DNB\Tests\Integration;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\GndStatement;
use DNB\WikibaseConverter\PackagePrivate\PropertyMapping;
use DNB\WikibaseConverter\PackagePrivate\QualifierMapping;
use DNB\WikibaseConverter\PackagePrivate\Subfields;
use DNB\WikibaseConverter\PackagePrivate\ValueMap;
use DNB\WikibaseConverter\PackagePrivate\ValueSource\SingleSubfieldSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\PropertyMapping
 * @covers \DNB\WikibaseConverter\PackagePrivate\QualifierMapping
 */
class PropertyMappingIntegrationTest extends TestCase {

	public function testQualifiers(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			null,
			null,
			[
				new QualifierMapping( 'P50', new SingleSubfieldSource( 'a' ) ),
				new QualifierMapping( 'P51', new SingleSubfieldSource( 'b' ) ),
				new QualifierMapping( 'P52', new SingleSubfieldSource( 'c' ) ),
			]
		);

		$this->assertEquals(
			[
				new GndStatement(
					'P1',
					'foo',
					[
						new GndQualifier( 'P50', 'AAA1' ),
						new GndQualifier( 'P50', 'AAA2' ),
						new GndQualifier( 'P52', 'CCC' ),
					]
				)
			],
			$mapping->convert( new Subfields( [
				'x' => [ 'foo' ],
				'c' => [ 'CCC' ],
				'a' => [ 'AAA1', 'AAA2' ],
			] ) )
		);
	}

	public function testQualifierValueMap(): void {
		$mapping = new PropertyMapping(
			'P1',
			new SingleSubfieldSource( 'x' ),
			null,
			null,
			[
				new QualifierMapping(
					'P50',
					new SingleSubfieldSource( 'a' ),
					new ValueMap( [ '|1' => 'one', '|3' => 'tree' ] )
				),
			]
		);

		$this->assertEquals(
			[
				new GndStatement(
					'P1',
					'foo',
					[
						new GndQualifier( 'P50', 'one' ),
						new GndQualifier( 'P645', 'P50 (qualifier): |2' ),
						new GndQualifier( 'P50', 'tree' ),
					]
				)
			],
			$mapping->convert( new Subfields( [
				'x' => [ 'foo' ],
				'a' => [ '|1', '|2', '|3' ],
			] ) )
		);
	}

}
