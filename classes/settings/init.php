<?php

/**
 * Initialize core settings
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
class Engage_Forms_Settings_Init {

	/**
	 * Load core settings objects
	 *
	 * Called in Engage_Forms constructor
	 *
	 * @since 1.5.3
	 */
	public static function load(){
		//Call the Engage_Forms::setings() method to trigger "engage_forms_settings_registered" action
		add_action( 'engage_forms_core_init', array( 'Engage_Forms', 'settings' ) );
		add_action( 'engage_forms_settings_registered', array( __CLASS__, 'add_core_settings' ) );
		if( ! is_admin() ){
			add_action( 'engage_forms_settings_registered', array( 'Engage_Forms_CDN_Init', 'init' ), 15 );
		}
	}

	/**
	 * Register the core settings
	 *
	 * CDN
	 * Email (@todo)
	 * General (@todo)
	 *
	 * @uses "engage_forms_settings_registered" action
	 *
	 * @since 1.5.3
	 */
	public static function add_core_settings(){
		Engage_Forms::settings()->add( new Engage_Forms_CDN_Settings() );
	}


}