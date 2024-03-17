<?php

/**
 * Get the ef2 container
 *
 * @since 1.8.0
 *
 * @return \engagewp\engageforms\ef2\EngageFormsV2Contract
 */
function engage_forms_get_v2_container()
{

	static $container;
	if ( !$container ) {
		$container = new \engagewp\engageforms\ef2\EngageFormsV2();
		do_action('engage_forms_v2_init', $container);
	}

	return $container;
}

/**
 * Setup Cf2 container
 *
 * @since 1.8.0
 *
 * @uses "engage_forms_v2_init" action
 *
 * @param \engagewp\engageforms\ef2\EngageFormsV2Contract $container
 */
function engage_forms_v2_container_setup(\engagewp\engageforms\ef2\EngageFormsV2Contract $container)
{
	$container
		//Set paths
		->setCoreDir(EFCORE_PATH)
		->setCoreUrl(EFCORE_URL)
		//Setup field types
		->getFieldTypeFactory()
		->add(new \engagewp\engageforms\ef2\Fields\FieldTypes\FileFieldType());

	//Add hooks
	$container->getHooks()->subscribe();

	//Register other services
	$container
		->registerService(new \engagewp\engageforms\ef2\Services\QueueService(), true)
		->registerService(new \engagewp\engageforms\ef2\Services\QueueSchedulerService(), true)
        ->registerService(new \engagewp\engageforms\ef2\Services\FormsService(), true )
        ->registerService(new \engagewp\engageforms\ef2\Services\ProcessorService(), true );


	//Run the scheduler with CRON
	/** @var \engagewp\engageforms\ef2\Jobs\Scheduler $scheduler */
	$scheduler = $container->getService(\engagewp\engageforms\ef2\Services\QueueSchedulerService::class);
	$running = $scheduler->runWithCron();
}

/**
 * Schedule delete with job manager
 *
 * @since 1.8.0
 *
 * @param \engagewp\engageforms\ef2\Jobs\Job $job Job to schedule
 * @param int $delay Optional. Minimum delay before job is run. Default is 0.
 */
function engage_forms_schedule_job(\engagewp\engageforms\ef2\Jobs\Job $job, $delay = 0)
{

	engage_forms_get_v2_container()
		->getService(\engagewp\engageforms\ef2\Services\QueueSchedulerService::class)
		->schedule($job, $delay);
}
