<?php


namespace engagewp\engageforms\ef2\RestApi\Queue;


use engagewp\engageforms\ef2\RestApi\AuthorizesRestApiRequestWithCfProKeys;
use engagewp\engageforms\ef2\RestApi\Endpoint;
use engagewp\engageforms\ef2\Services\QueueSchedulerService;


class RunQueue extends Endpoint
{
	use AuthorizesRestApiRequestWithCfProKeys;

	public function getUri()
	{
		return 'queue';
	}

	/** @inheritdoc */
    protected function getArgs()
    {
        return [

            'methods' => 'POST',
            'callback' => [$this, 'runQueue'],
            'permission_callback' => [$this, 'checkKeys' ],
            'args' => [
                'jobs' => [
                    'description' => __('Total jobs to run per back', 'engage-forms'),
					'required' => false,
					'default' => 10,
					'sanitize_callback' => 'absint'

				],
				'public' => [
					'type' => 'string',
					'required' => false,
					'default' => ''
				]
            ]
        ];
    }


    /**
     * Trigger queue manger from remote ping
     *
     * @since 1.8.0
     *
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */

    /**
     * @param \WP_REST_Request $request
     * @return mixed|null|\WP_REST_Response
     */
    public function runQueue(\WP_REST_Request $request)
    {
    	$totalJobs = engage_forms_get_v2_container()
		   ->getService(QueueSchedulerService::class )
		   ->runJobs($request['jobs']);

    	$statusCode = $totalJobs > 0 ? 201 : 200;
        $response =  rest_ensure_response(['totalJobs' => $totalJobs]);
		$response->set_status($statusCode);
        return $response;
    }



}
