<?php


namespace engagewp\EngageFormsQuery\Tests\Integration;

use engagewp\EngageFormsQuery\SelectQueries;
use engagewp\EngageFormsQuery\Select\Entry;
use engagewp\EngageFormsQuery\Select\EntryValues;

/**
 * Class TestsTest
 *
 * Tests to ensure integration test environment is working
 * @package engagewp\EngageFormsQuery\Tests\Integration
 */
class TestsTest extends IntegrationTestCase
{
	//Using this so we can test that EF's testing traits are available
	use \Engage_Forms_Has_Mock_Form;

	/**
	 * Check that Engage Forms is usable
	 */
	public function testEngageFormsIsInstalled()
	{
		$this->assertTrue( defined( 'EFCORE_VER' ) );
		$this->assertTrue( class_exists( '\Engage_Forms' ) );
	}

	/**
	 * Make sure the trait worked
	 */
	public function testMockForm()
	{
		$this->set_mock_form();
		$this->assertTrue( is_array( $this->mock_form  ) );
	}

	/**
	 * Test that factories work for integration tests
	 *
	 * @covers HasFactories::selectQueriesFactory()
	 * @covers HasFactories::entryValuesGeneratorFactory()
	 * @covers HasFactories::entryGeneratorFactory()
	 */
	public function testFactory()
	{
		$this->assertTrue(is_a($this->selectQueriesFactory(), SelectQueries::class));
		$this->assertTrue(is_a($this->entryValuesGeneratorFactory(), EntryValues::class));
		$this->assertTrue(is_a($this->entryGeneratorFactory(), Entry::class));

	}

}