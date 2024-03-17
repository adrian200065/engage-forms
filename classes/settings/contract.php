<?php

/**
 * Interface settings objects must implement
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
interface Engage_Forms_Settings_Contract {

	/**
	 * Get name for setting
	 *
	 * @since 1.5.3
	 *
	 * @return string
	 */
	public function get_name();

}