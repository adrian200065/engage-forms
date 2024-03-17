<?php


namespace engagewp\EngageFormsQuery;

use engagewp\EngageFormsQuery\Select\Entry;
use engagewp\EngageFormsQuery\Select\EntryValues;

/**
 * Interface QueriesEntries
 *
 * Interface that all classes that query for entries MUST implement
 */
interface CreatesSelectQueries extends GetsResults
{
	/**
	 * Get generator for entry values SQL
	 *
	 * @return EntryValues
	 */
	public function getEntryValueGenerator();
	/**
	 * Get generator for entry table SQL
	 *
	 * @return Entry
	 */
	public function getEntryGenerator();

	/**
	 * @param $sql
	 * @return \stdClass[]
	 */
	public function getResults($sql);
}
