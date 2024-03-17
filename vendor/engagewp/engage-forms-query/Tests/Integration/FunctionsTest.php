<?php


namespace engagewp\EngageFormsQuery\Tests\Integration;


use function engagewp\EngageFormsQueries\EngageFormsQueries;
use engagewp\EngageFormsQuery\Features\FeatureContainer;

class FunctionsTest extends IntegrationTestCase
{

	/**
	 * Ensure that accessor function returns the right class type
	 * @covers EngageFormsQueries()
	 */
	public function testGetMainInstance()
	{
		$this->assertSame( FeatureContainer::class, get_class(EngageFormsQueries()) );
	}
	/**
	 * Ensure that accessor function returns the same class instance
	 * @covers EngageFormsQueries()
	 */
	public function testIsSameInstance()
	{
		$this->assertSame( EngageFormsQueries(), EngageFormsQueries() );
		EngageFormsQueries()->set('sivan', 'roy' );
		$this->assertEquals( 'roy', EngageFormsQueries()->get('sivan') );
	}


}