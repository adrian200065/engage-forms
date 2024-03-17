<?php


namespace  engagewp\EngageFormsQuery\Tests\Unit\Select;

use engagewp\EngageFormsQuery\Select\Entry;
use engagewp\EngageFormsQuery\Tests\Unit\TestCase;

class EntryTest extends TestCase
{

	/**
	 * Test query by form ID
	 *
	 * @covers Entry::queryByFormsId()
	 */
	public function testQueryByFormsId()
	{
		$expectedSql = "SELECT `{$this->entryTableName()}`.* FROM `wp_ef_form_entries` WHERE (`{$this->entryTableName()}`.`form_id` = 'ef12345')";
		$entryGenerator = $this->entryGeneratorFactory();
		$generator = $entryGenerator->queryByFormsId('ef12345');
		$this->assertTrue($this->isAEntry($generator));

		$actualSql = $entryGenerator->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * Test query by form ID
	 *
	 * @covers Entry::queryByFormsId()
	 * @covers \engagewp\EngageFormsQuery\addPagination::()
	 */
	public function testQueryByFormsIdPaginated()
	{
		$expectedSql = "SELECT `{$this->entryTableName()}`.* FROM `wp_ef_form_entries` WHERE (`{$this->entryTableName()}`.`form_id` = 'ef12345') LIMIT 25, 25";
		$actualSql = $this
			->entryGeneratorFactory()
			->queryByFormsId('ef12345')
			->addPagination(2, 25)
			->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * Test query by entry ID
	 *
	 * @covers Entry::queryByEntryId()
	 */
	public function testQueryByEntryId()
	{
		$expectedSql = "SELECT `{$this->entryTableName()}`.* FROM `{$this->entryTableName()}` WHERE (`{$this->entryTableName()}`.`id` = 42)";
		$entryGenerator = $this->entryGeneratorFactory();
		$generator = $entryGenerator->queryByEntryId(42);
		$this->assertTrue($this->isAEntry($generator));

		$actualSql = $entryGenerator->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}

	/**
	 * Test query by user ID
	 *
	 * @covers Entry::queryByUserId()
	 */
	public function testQueryByUserId()
	{
		$expectedSql = "SELECT `{$this->entryTableName()}`.* FROM `{$this->entryTableName()}` WHERE (`{$this->entryTableName()}`.`user_id` = 42)";
		$entryGenerator = $this->entryGeneratorFactory();
		$generator = $entryGenerator->queryByUserId(42);
		$this->assertTrue($this->isAEntry($generator));

		$actualSql = $entryGenerator->getPreparedSql();
		$this->assertEquals($expectedSql, $actualSql);
	}




	/**
	 * @param $generator
	 * @return bool
	 */
	protected function isAEntry($generator)
	{
		return is_a($generator, '\engagewp\EngageFormsQuery\Select\Entry');
	}
}
