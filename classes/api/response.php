<?php

/**
 * Response object all non-error REST API requests should return
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_API_Response extends \WP_REST_Response {

	/**
	 * @inheritdoc
	 *
	 * @since 1.4.4
	 */
	public function __construct( $data = null, $status = 200, $headers = array() ) {
		parent::__construct( $data, $status, $headers );
		if ( empty( $data ) ) {
			$this->set_status( 404 );
		}

	}

	public function set_total_header( $total ){
		$this->header( 'X-EF-API-TOTAL', (int) $total );
	}

	public function set_total_pages_header( $total_pages ){
		$this->header( 'X-EF-API-TOTAL-PAGES', (int) $total_pages );
	}


}