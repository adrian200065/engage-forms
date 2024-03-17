<?php


namespace engagewp\EngageFormsQuery\Tests\Traits;

use engagewp\EngageContainers\Service\Container;
use engagewp\EngageFormsQuery\DeleteQueries;
use engagewp\EngageFormsQuery\Features\FeatureContainer;
use engagewp\EngageFormsQuery\SelectQueries;
use engagewp\EngageFormsQuery\Tests\Unit\Features\QueriesTest;

trait HasFactories
{

	/**
	 * @return \engagewp\EngageFormsQuery\Select\Entry
	 */
	protected function entryGeneratorFactory()
	{
		return new \engagewp\EngageFormsQuery\Select\Entry(
			$this->mySqlBuilderFactory(),
			$this->entryTableName()
		);
	}

	/**
	 * @return \engagewp\EngageFormsQuery\Delete\Entry
	 */
	protected function entryDeleteGeneratorFactory()
	{
		return new \engagewp\EngageFormsQuery\Delete\Entry(
			$this->mySqlBuilderFactory(),
			$this->entryTableName()
		);
	}


	/**
	 * @return \engagewp\EngageFormsQuery\Select\EntryValues
	 */
	protected function entryValuesGeneratorFactory()
	{
		return new \engagewp\EngageFormsQuery\Select\EntryValues(
			$this->mySqlBuilderFactory(),
			$this->entryValueTableName()
		);
	}
	/**
	 * @return \engagewp\EngageFormsQuery\Delete\EntryValues
	 */
	protected function entryValuesDeleteGeneratorFactory()
	{
		return new \engagewp\EngageFormsQuery\Delete\EntryValues(
			$this->mySqlBuilderFactory(),
			$this->entryValueTableName()
		);
	}



	/**
	 * @return \engagewp\EngageFormsQuery\MySqlBuilder
	 */
	protected function mySqlBuilderFactory()
	{
		return new \engagewp\EngageFormsQuery\MySqlBuilder();
	}


	/**
	 * @return SelectQueries
	 */
	protected function selectQueriesFactory()
	{

		return new SelectQueries(
			$this->entryGeneratorFactory(),
			$this->entryValuesGeneratorFactory(),
			$this->getWPDB()
		);
	}

	/**
	 * @return DeleteQueries
	 */
	protected function deleteQueriesFactory()
	{

		return new DeleteQueries(
			$this->entryDeleteGeneratorFactory(),
			$this->entryValuesDeleteGeneratorFactory(),
			$this->getWPDB()
		);
	}

	/**
	 * @return \engagewp\EngageFormsQuery\Features\Queries
	 */
	protected function featureQueriesFactory()
	{
		return new \engagewp\EngageFormsQuery\Features\Queries(
			$this->selectQueriesFactory(),
			$this->deleteQueriesFactory()
		);
	}

	/**
	 * @return FeatureContainer
	 */
	protected function containerFactory()
	{
		return new FeatureContainer(
			new Container(),
			$this->getWPDB()
		);
	}

	/**
	 * Gets a WPDB instance
	 *
	 * @return \wpdb
	 */
	protected function getWPDB()
	{
		global $wpdb;
		if (! class_exists('\WP_User')) {
			include_once dirname(dirname(__FILE__)) . '/Mock/wpdb.php';
		}

		if (! $wpdb) {
			$wpdb = new \wpdb('', '', '', '');
		}
		return $wpdb;
	}

	/**
	 * @return string
	 */
	protected function entryValueTableName(): string
	{
		return "{$this->getWPDB()->prefix}ef_form_entry_values";
	}

	/**
	 * @return string
	 */
	protected function entryTableName(): string
	{
		return "{$this->getWPDB()->prefix}ef_form_entries";
	}
}
