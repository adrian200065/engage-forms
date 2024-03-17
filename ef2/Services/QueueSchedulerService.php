<?php


namespace engagewp\engageforms\ef2\Services;


use engagewp\engageforms\ef2\EngageFormsV2Contract;
use engagewp\engageforms\ef2\Jobs\DatabaseConnection;
use engagewp\engageforms\ef2\Jobs\Scheduler;

use WP_Queue\Queue;
use WP_Queue\QueueManager;

class QueueSchedulerService extends Service
{

	/** @inheritdoc */
	public function isSingleton()
	{
		return true;
	}

	/** @inheritdoc */
	public function register(EngageFormsV2Contract $container)
	{
		return new Scheduler( new Queue(new DatabaseConnection($container->getWpdb())) );
	}


}