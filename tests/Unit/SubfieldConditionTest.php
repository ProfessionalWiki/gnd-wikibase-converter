<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PackagePrivate\SubfieldCondition;
use DNB\WikibaseConverter\PackagePrivate\Subfields;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\PackagePrivate\SubfieldCondition
 */
class SubfieldConditionTest extends TestCase {

	public function testConditionMatches(): void {
		$condition = new SubfieldCondition( 'a', 'gnd' );

		$subfields = Subfields::newFromMap( [
			'a' => 'gnd',
			'z' => '42',
		] );

		$this->assertTrue( $condition->matches( $subfields ) );
	}

	public function testConditionDoesNotMatch(): void {
		$condition = new SubfieldCondition( 'a', 'gnd' );

		$subfields = Subfields::newFromMap( [
			'a' => 'not gnd',
			'z' => '42',
		] );

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testDoesNotMatchWhenSubfieldIsMissing(): void {
		$condition = new SubfieldCondition( 'does not exist', 'gnd' );

		$subfields = Subfields::newFromMap( [
			'a' => 'gnd',
			'z' => '42',
		] );

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testNullValueDoesNotMatchWhenSubfieldIsPresent(): void {
		$condition = new SubfieldCondition( 'a', null );

		$subfields = Subfields::newFromMap( [
			'a' => 'gnd',
			'b' => 'gnd',
		] );

		$this->assertFalse( $condition->matches( $subfields ) );
	}

	public function testNullValueMatchesWhenSubfieldIsMissing(): void {
		$condition = new SubfieldCondition( 'a', null );

		$subfields = Subfields::newFromMap( [
			'b' => 'gnd',
		] );

		$this->assertTrue( $condition->matches( $subfields ) );
	}

}
