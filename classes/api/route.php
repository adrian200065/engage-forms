<?php

/**
 * Interface all REST API routes should use
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
interface Engage_Forms_API_Route {

	/**
	 * Add the routes for this set of endpoints
	 *
	 * @since 1.4.4
	 *
	 * @param string $namespace API namespace
	 *
	 * @return void
	 */
	public function add_routes( $namespace );

}