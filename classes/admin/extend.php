<?php

/**
 * Manage extend sub-menu
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Admin_Extend {


	/**
	 * Enqueue scripts for the admin extend sub menu
	 *
	 * @uses "admin_enqueue_scripts" action
	 *
	 * @since 1.4.2
	 */
	public static function scripts(){
		Engage_Forms_Render_Assets::register();
		Engage_Forms_Render_Assets::enqueue_script( 'handlebars' );
		Engage_Forms_Admin_Assets::enqueue_style( 'admin' );
	}
}