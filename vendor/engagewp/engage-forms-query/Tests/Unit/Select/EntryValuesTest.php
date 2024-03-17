<?php


namespace engagewp\EngageFormsQuery\Tests\Unit\Select;

use engagewp\EngageFormsQuery\Tests\Unit\TestCase;

class EntryValuesTest extends TestCase
{

	/**
	 * Test query by field where field value equals a value
	 *
	 * @covers \engagewp\EngageFormsQuery\Select\EntryValues::queryByFieldValue()
	 */
	public function testQueryByFieldValueEquals()
	{
		$expectedSql = "SELECT `{$this->entryValueTableName()}`.* FROM `{$this->entryValueTableName()}` WHERE (`{$this->entryValueTableName()}`.`value` = 'Adrian@engagewp.com') AND (`{$this->entryValueTableName()}`.`slug` = 'email_address')";

		$entryValues = $this->entryValuesGeneratorFactory();
		$generator = $entryValues->queryByFieldValue('email_address', 'Adrian@engagewp.com');
		$this->assertTrue($this->isAEntryValues($generator));

		$actualSql = $entryValues->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * Test query by field where field value does not equals a value
	 *
	 * @covers \engagewp\EngageFormsQuery\Select\EntryValues::queryByFieldValue()
	 */
	public function testQueryByFieldValueNotEquals()
	{
		$expectedSql = "SELECT `{$this->entryValueTableName()}`.* FROM `{$this->entryValueTableName()}` WHERE (`{$this->entryValueTableName()}`.`value` <> 'Adrian@engagewp.com') AND (`{$this->entryValueTableName()}`.`slug` = 'email_address')";
		$entryValues = $this->entryValuesGeneratorFactory();
		$generator =$entryValues->queryByFieldValue('email_address', 'Adrian@engagewp.com', 'notEquals');
		$this->assertTrue($this->isAEntryValues($generator));

		$actualSql = $entryValues->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * Test query by field where field value is like a value
	 *
	 * @cover \engagewp\EngageFormsQuery\Select\EntryValues::$isLike
	 * @covers \engagewp\EngageFormsQuery\Select\EntryValues::queryByFieldValue()
	 */
	public function testQueryByFieldValueLike()
	{
		$expectedSql = "SELECT `{$this->entryValueTableName()}`.* FROM `{$this->entryValueTableName()}` WHERE (`{$this->entryValueTableName()}`.`value` LIKE '\%Adrian@engagewp.com\%')";
		
		$entryValues = $this->entryValuesGeneratorFactory();
		$generator = $entryValues->queryByFieldValue('email_address', 'Adrian@engagewp.com', 'like');
		$this->assertTrue($this->isAEntryValues($generator));

		$actualSql = $entryValues->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * Test query by entry id
	 *
	 * @covers \engagewp\EngageFormsQuery\Select\EntryValues::queryByFieldValue()
	 */
	public function testQueryByEntryId()
	{
		$expectedSql = "SELECT `{$this->entryValueTableName()}`.* FROM `{$this->entryValueTableName()}` WHERE (`{$this->entryValueTableName()}`.`entry_id` = 42)";
		$entryValues = $this->entryValuesGeneratorFactory();
		$generator = $entryValues->queryByEntryId(42);
		$this->assertTrue($this->isAEntryValues($generator));

		$actualSql = $entryValues->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * @param $generator
	 * @return bool
	 */
	protected function isAEntryValues($generator)
	{
		return is_a($generator, '\engagewp\EngageFormsQuery\Select\EntryValues');
	}
}
