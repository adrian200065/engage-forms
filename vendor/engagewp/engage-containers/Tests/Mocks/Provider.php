<?php


namespace engagewp\EngageContainers\Tests\Mocks;


use engagewp\EngageContainers\Interfaces\ProvidesService;
use engagewp\EngageContainers\Interfaces\ServiceContainer;

class Provider implements ProvidesService
{

	/** @inheritdoc */
	public function registerService(ServiceContainer $container)
	{
		$container->bind( $this->getAlias(), function (){
			return (object) [
				'Roy' => 'Sivan',
				'Mike' => 'Corkum'
			];
		} );
	}

	/** @inheritdoc */
	public function getAlias()
	{
		return 'SIVAN';
	}
}