<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\GndQualifier;
use DNB\WikibaseConverter\GndStatement;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\GndStatement
 * @covers \DNB\WikibaseConverter\GndQualifier
 */
class GndStatementTest extends TestCase {

	public function testConstructor(): void {
		$statement = new GndStatement(
			'P5',
			'testValue',
			[
				new GndQualifier( 'P11', 'qualifierOne' ),
				new GndQualifier( 'P12', 'qualifierTwo' ),
			]
		);

		$this->assertSame( 'P5', $statement->getPropertyId() );
		$this->assertSame( 'testValue', $statement->getValue() );

		$this->assertSame( 'P11', $statement->getQualifiers()[0]->getPropertyId() );
		$this->assertSame( 'qualifierOne', $statement->getQualifiers()[0]->getValue() );

		$this->assertSame( 'P12', $statement->getQualifiers()[1]->getPropertyId() );
		$this->assertSame( 'qualifierTwo', $statement->getQualifiers()[1]->getValue() );
	}

}
