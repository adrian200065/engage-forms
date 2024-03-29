<?php


namespace engagewp\engageforms\ef2\Jobs;


class DeleteTransientJob extends Job
{

	/**
	 * ID of transient to delete
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	protected $transientId;

	/**
	 * DeleteTransientJob constructor.
	 *
	 * @since 1.8.0
	 *
	 * @param $transientId
	 */
	public function __construct($transientId)
	{
		$this->transientId = $transientId;
	}

	/** @inheritdoc */
	public function handle()
	{
		\Engage_Forms_Transient::delete_transient($this->transientId);
	}
}