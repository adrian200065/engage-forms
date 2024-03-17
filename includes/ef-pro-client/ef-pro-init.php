<?php

/**
 * Start Engage Forms Pro API client
 *
 * NOTE: This file is included directly and MUST be PHP5.2 compatible. Hence why boostrap-ef-pro.php is a separate, non-PHP 5.2 compatible file.
 */
add_action('engage_forms_includes_complete', 'engage_forms_pro_client_init', 1);
remove_action('engage_forms_includes_complete', 'engage_forms_pro_init', 2);

/**
 * Initialize Engage Forms Pro API client if possible
 *
 * @since 1.5.8
 */
function engage_forms_pro_client_init()
{
	if ( !version_compare(PHP_VERSION, '8.0.0', '>=') ) {
		$admin = new Engage_Forms_Admin_Pro();
		$admin->add_hooks();
		define('EF_PRO_NOT_LOADED', true);
	} else {
		if ( !defined('EF_PRO_VER') ) {
			define('EF_PRO_LOADED', true);
			/**
			 * Define Plugin basename for updater
			 *
			 * @since 0.2.0
			 */
			define('EF_PRO_BASENAME', plugin_basename(__FILE__));

			/**
			 * Engage Forms Pro Client Version
			 */
			define('EF_PRO_VER', EFCORE_VER);

			include_once dirname(__FILE__) . '/bootstrap-ef-pro.php';

			register_activation_hook(__FILE__, 'engage_forms_pro_activation_hook_callback');

		}

	}

}
