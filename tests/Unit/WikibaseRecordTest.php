<?php

declare( strict_types = 1 );

namespace DNB\Tests\Unit;

use DNB\WikibaseConverter\PropertyWithValues;
use DNB\WikibaseConverter\WikibaseRecord;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DNB\WikibaseConverter\WikibaseRecord
 */
class WikibaseRecordTest extends TestCase {

	public function testEmptyRecordHasNoPropertyIds() {
		$record = new WikibaseRecord();
		$this->assertSame( [], $record->getPropertyIds() );
	}

	public function testEmptyRecordHasNoValuesForProperty() {
		$record = new WikibaseRecord();
		$this->assertSame( [], $record->getValuesForProperty( 'P1' ) );
	}

	public function testCanAddAndGetPropertyValues() {
		$record = new WikibaseRecord();
		$record->addValuesOfOneProperty( new PropertyWithValues(
			'P1',
			[ 'foo', 'bar' ]
		) );

		$this->assertSame(
			[ 'foo', 'bar' ],
			$record->getValuesForProperty( 'P1' )
		);
	}

	public function testValuesOfOtherPropertiesAreNotReturned() {
		$record = new WikibaseRecord();
		$record->addValuesOfOneProperty( new PropertyWithValues(
			'P2',
			[ 'foo', 'bar' ]
		) );

		$this->assertSame( [], $record->getValuesForProperty( 'P1' ) );
	}

	public function testCanAddForOnePropertyMultipleTimes() {
		$record = new WikibaseRecord();
		$record->addValuesOfOneProperty( new PropertyWithValues(
			'P1',
			[ 'foo', 'bar' ]
		) );
		$record->addValuesOfOneProperty( new PropertyWithValues(
			'P1',
			[ 'baz', 'bah' ]
		) );

		$this->assertSame(
			[ 'foo', 'bar', 'baz', 'bah' ],
			$record->getValuesForProperty( 'P1' )
		);
	}

}
