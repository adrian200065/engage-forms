<?php


namespace engagewp\engageforms\pro\api;


/**
 * Class log
 * @package engagewp\engageforms\pro\api
 */
class log extends api
{

	/**
	 * Send a log event
	 *
	 * @since 0.2.0
	 *
	 * @param string $message Log message
	 * @param array $data Optional log data
	 * @param string $level Log level
	 *
	 * @return array|\WP_Error
	 */
	public function send($message, $data = [], $level = '')
	{
		if ( !is_array($data) ) {
			$data = [];
		}

		global $wp_version;
		$data[ 'data' ] = $data;
		$data[ 'level' ] = $level;
		$data[ 'data' ][ 'versions' ] = [
			'php' => PHP_VERSION,
			'client' => EF_PRO_VER,
			'ef' => EFCORE_VER,
			'wp' => $wp_version,
			'url' => home_url(),
		];


		$data[ 'message' ] = $message;
		$r = $this->request('/log', $data, 'POST');
		return $r;
	}

	/**
	 * @inheritdoc
	 */
	protected function get_url_root()
	{
		return engage_forms_pro_log_url();
	}

	/**
	 * @inheritdoc
	 */
	protected function set_request_args($method)
	{
		$args = parent::set_request_args($method);
		$args[ 'blocking' ] = false;
		return $args;
	}

}
