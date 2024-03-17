<?php


namespace engagewp\EngageFormsQuery\Tests\Unit;

//Import PHP unit test case.
//Must be aliased to avoid having two classes of same name in scope.
use engagewp\EngageFormsQuery\SelectQueries;
use engagewp\EngageFormsQuery\Tests\Traits\HasFactories;
use PHPUnit\Framework\TestCase as FrameworkTestCase;

/**
 * Class TestCase
 *
 * Default test case for all unit tests
 * @package EngageLearn\RestSearch\Tests\Unit
 */
abstract class TestCase extends FrameworkTestCase
{
	//Factories go in this trait so they are share with integration tests
	use HasFactories;
}
