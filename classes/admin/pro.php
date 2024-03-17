<?php

/**
 * Sets up Engage Forms Pro menu page when to create ef-pro admin page WHEN EF PRO CAN NOT BE USED
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
class Engage_Forms_Admin_Pro {

	/**
	 * Menu slug for page and slug for plugin in instller
	 *
	 * @since 1.5.1
	 *
	 * @var string
	 */
	protected $slug = 'ef-pro';

	/**
	 * Holds basefile, if found for EF Pro API client plugin
	 *
	 * @since 1.5.1
	 *
	 * @var string
	 */
	protected  $basefile;

	/**
	 * Add hooks
	 *
	 * @since 1.5.1
	 */
	public function add_hooks(){
	    //@see https://github.com/EngageWP/Engage-Forms/issues/3413
	    if( Engage_Forms_Admin::show_pro_ui() ){
            add_action( 'admin_menu', array( $this, 'maybe_add_menu_page' ) );
        }
	}

	/**
	 * Add the EF Pro menu page if EF Pro client is not usable
	 *
	 * @uses "admin_menu" action
	 *
	 * @since 1.5.1
	 */
	public function maybe_add_menu_page(  ){

		add_submenu_page(
			Engage_Forms::PLUGIN_SLUG,
			__( 'Engage Forms Pro', 'engage-forms'),
			'<span class="engage-forms-menu-dashicon"><span class="dashicons dashicons-star-filled"></span>' .__( 'Engage Forms Pro', 'engage-forms') . '</span>',
			Engage_Forms::get_manage_cap( 'admin' ),
			$this->slug,
			array( $this, 'render_page' )
		);

	}

	/**
	 * Render menu page
	 *
	 * @since 1.5.1
	 */
	public function render_page(){
        Engage_Forms_Admin_Assets::enqueue_style( 'admin' );

		include  EFCORE_PATH . '/ui/pro.php';

	}


}
