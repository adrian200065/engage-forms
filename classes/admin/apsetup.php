<?php

/**
 * Base class for adding auto-populate options to select fields
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
abstract class Engage_Forms_Admin_APSetup implements  Engage_Forms_Admin_APSetupInterface {

	/**
	 * Engage_Forms_Admin_APSetup constructor.
	 *
	 * @since 1.4.3
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add hooks
	 *
	 * @since 1.4.3
	 */
	protected function add_hooks(){
		add_action( 'engage_forms_autopopulate_types', array( $this, 'add_type' ) );
		add_action( 'engage_forms_autopopulate_type_config', array( $this, 'add_options' ) );
	}

	/**
	 * Remove hooks
	 *
	 * @since 1.4.3
	 */
	public function remove_hooks(){
		remove_action( 'engage_forms_autopopulate_types', array( $this, 'add_type' ) );
		remove_action( 'engage_forms_autopopulate_type_config', array( $this, 'add_options' ) );
	}


}