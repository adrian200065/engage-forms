<?php


namespace engagewp\engageforms\ef2\Services;


abstract class Service implements ServiceContract
{



	final public function getIdentifier()
	{
		return static::class;
	}
}