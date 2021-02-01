<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\SubfieldCondition
 */
class SubfieldConditionTest extends TestCase {

	public function testConditionMatches(): void {
		$condition = new SubfieldCondition( 'a', 'gnd' );

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertTrue( $condition->matches( $subfields ) );
	}

	public function testConditionDoesNotMatch(): void {
		$condition = new SubfieldCondition( 'a', 'gnd' );

		$subfields = [
			[ 'name' => 'a', 'value' => 'not gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testDoesNotMatchWhenSubfieldIsMissing(): void {
		$condition = new SubfieldCondition( 'does not exist', 'gnd' );

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => '0', 'value' => '42' ],
		];

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testNullValueDoesNotMatchWhenSubfieldIsPresent(): void {
		$condition = new SubfieldCondition( 'a', null );

		$subfields = [
			[ 'name' => 'a', 'value' => 'gnd' ],
			[ 'name' => 'b', 'value' => 'gnd' ],
		];

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testNullValueMatchesWhenSubfieldIsMissing(): void {
		$condition = new SubfieldCondition( 'a', null );

		$subfields = [
			[ 'name' => 'b', 'value' => 'gnd' ],
		];

		$this->assertTrue( $condition->matches( $subfields ) );
	}

}
