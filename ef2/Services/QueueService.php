<?php


namespace engagewp\engageforms\ef2\Services;


use engagewp\engageforms\ef2\EngageFormsV2Contract;
use engagewp\engageforms\ef2\Jobs\DatabaseConnection;
use WP_Queue\Queue;

class QueueService extends Service
{


	public function isSingleton()
	{
		return true;
	}

	public function register(EngageFormsV2Contract $container)
	{
		return new Queue(new DatabaseConnection($container->getWpdb()));
	}

}