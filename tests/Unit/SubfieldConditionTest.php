<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\SubfieldCondition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\SubfieldCondition
 */
class SubfieldConditionTest extends TestCase {

	public function testEqualityConditionMatches() {
		$condition = new SubfieldCondition( 'a', 'gnd' );

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertTrue( $condition->matches( $subfields ) );
	}

	public function testEqualityConditionDoesNotMatch() {
		$condition = new SubfieldCondition( 'a', 'gnd' );

		$subfields = [
			[ 'name' => 'a', 'value' => 'not gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testEqualityConditionWithoutMatchingSubfield() {
		$condition = new SubfieldCondition( 'does not exist', 'gnd' );

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertFalse( $condition->matches( $subfields ) );
	}

}
