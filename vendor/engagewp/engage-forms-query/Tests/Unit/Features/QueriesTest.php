<?php
namespace engagewp\EngageFormsQuery\Tests\Unit\Features;

use engagewp\EngageFormsQuery\Delete\Entry;
use engagewp\EngageFormsQuery\Delete\EntryValues;
use engagewp\EngageFormsQuery\Tests\Unit\TestCase;

class QueriesTest extends TestCase
{
	/**
	 * Test getting entry delete SQL generator
	 *
	 * @covers Queries::entryDelete()
	 */
	public function testGetDeleteEntryGenerator()
	{
		$queries = $this->featureQueriesFactory();
		$this->assertTrue(is_a($queries->entryDelete(), Entry::class));
	}

	/**
	 * Test getting entry delete values SQL generator
	 *
	 * @covers Queries::entryValueDelete()
	 */
	public function testGetDeleteEntryValueGenerator()
	{
		$queries = $this->featureQueriesFactory();
		$this->assertTrue(is_a($queries->entryValueDelete(), EntryValues::class));
	}
	/**
	 * Test getting entry select SQL generator
	 *
	 * @covers Queries::entrySelect()
	 */
	public function testGetSelectEntryGenerator()
	{
		$queries = $this->featureQueriesFactory();
		$this->assertTrue(is_a($queries->entrySelect(), \engagewp\EngageFormsQuery\Select\Entry::class));
	}

	/**
	 * Test getting entry values  select SQL generator
	 *
	 * @covers Queries::entryValuesSelect()
	 */
	public function testGetSelectEntryValueGenerator()
	{
		$queries = $this->featureQueriesFactory();
		$this->assertTrue(is_a($queries->entryValuesSelect(), \engagewp\EngageFormsQuery\Select\EntryValues::class));
	}
}
