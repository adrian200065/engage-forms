<?php


namespace engagewp\engageforms\ef2\Jobs;


class DatabaseConnection extends \WP_Queue\Connections\DatabaseConnection
{

	const QUEUED_JOBS_TABLE = 'ef_queue_jobs';
	const FAILED_JOBS_TABLE = 'ef_queue_failures';
	/**
	 * DatabaseQueue constructor.
	 *
	 * Creates database connection with ef prefixed tables
	 *
	 * @param \wpdb $wpdb
	 *
	 * @since 1.8.0
	 */
	public function __construct( $wpdb ) {

		$this->database       = $wpdb;
		$this->jobs_table     = $this->database->prefix . static::QUEUED_JOBS_TABLE;
		$this->failures_table = $this->database->prefix . static::FAILED_JOBS_TABLE;
	}
}