<?php

/**
 * Interface for email API clients
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
interface Engage_Forms_Email_Interface {

	/**
	 * Set API keys
	 *
	 * @since 1.4.0
	 *
	 * @param array $keys
	 */
	public function set_api( array $keys );

	/**
	 * Send email
	 *
	 * @since 1.4.0
	 *
	 * @return array|int Array of errors or status code
	 */
	public function send();

	/**
	 * Include SDK for API
	 *
	 * @since 1.4.0
	 */
	public function include_sdk();

}
