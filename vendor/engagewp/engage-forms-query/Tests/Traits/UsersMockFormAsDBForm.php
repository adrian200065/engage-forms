<?php


namespace engagewp\EngageFormsQuery\Tests\Traits;

trait UsersMockFormAsDBForm
{

	use \Engage_Forms_Has_Data, HasFactories;
	/** @inheritdoc */
	protected $mock_form_id;
	/** @inheritdoc */
	protected $mock_form;
	/** @inheritdoc */

	public function setUp()
	{
		global $wpdb;
		$tables = new \Engage_Forms_DB_Tables($wpdb);
		$tables->add_if_needed();
		$this->set_mock_form();
		$this->mock_form_id = \Engage_Forms_Forms::import_form($this->mock_form);
		$this->mock_form = \Engage_Forms_Forms::get_form($this->mock_form_id);
		parent::setUp();
	}
}
