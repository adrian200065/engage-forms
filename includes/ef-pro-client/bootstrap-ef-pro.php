<?php

use \engagewp\engageforms\pro\container;
use \engagewp\engageforms\pro\admin\scripts;
use \engagewp\engageforms\pro\admin\menu;

/**
 * Load up Engage Forms Pro API client
 *
 * @since 0.0.1
 */
add_action('engage_forms_includes_complete', function () {
	$db_ver_option = 'ef_pro_db_v';
	//add database table if needed
	if ( 1 > get_option($db_ver_option, 0) ) {
		engage_forms_pro_drop_tables();
		engage_forms_pro_db_delta_1();
		//set to 2 to skip autoload disable on new installs
		update_option($db_ver_option, 2);
	}

	if ( 2 > get_option($db_ver_option, 0) ) {
		engage_forms_pro_db_delta_2();
		update_option($db_ver_option, 2);
	}

	//add menu page
	if ( is_admin() ) {
		$slug = 'ef-pro';
		$assets_url = plugin_dir_url(__FILE__) . 'dist/';
		$view_dir = __DIR__ . '/dist';
		$scripts = new scripts($assets_url, $slug, EF_PRO_VER);
		// Pro Tab removed @see https://github.com/EngageWP/Engage-Forms/issues/3413

		if( Engage_Forms_Admin::show_pro_ui() ){
			$menu = new menu($view_dir, $slug, $scripts);
			add_action('admin_menu', [ $menu, 'display' ]);
		}
	}

	//add hooks
	container::get_instance()->get_hooks()->add_hooks();

	/**
	 * Runs after Engage Forms Pro is loaded
	 *
	 * @since 0.5.0
	 */
	do_action('engage_forms_pro_loaded');

});


/**
 * Delete EF Pro DB Table
 */
function engage_forms_pro_drop_tables()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'ef_pro_messages';
	$sql = "DROP TABLE IF EXISTS $table_name";
	$wpdb->query($sql);
	delete_option('ef_pro_db_v');
}

/**
 * Database changes for Engage Forms Pro
 *
 * @since 0.0.1
 */
function engage_forms_pro_db_delta_1()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	$charset_collate = '';

	if ( !empty($wpdb->charset) ) {
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	}

	if ( !empty($wpdb->collate) ) {
		$charset_collate .= " COLLATE $wpdb->collate";
	}
	$table = "CREATE TABLE `" . $wpdb->prefix . "ef_pro_messages` (
			`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`efp_id` bigint(20) unsigned DEFAULT NULL,
			`entry_id` bigint(20) unsigned DEFAULT NULL,
			`hash` varchar(255) DEFAULT NULL,
			`type` varchar(255) DEFAULT NULL,
			PRIMARY KEY ( `ID` )
			) " . $charset_collate . ";";

	dbDelta($table);


}


/**
 * Rewrite the options to not autoload
 *
 * @since 0.8.1
 */
function engage_forms_pro_db_delta_2()
{

	$forms = Engage_Forms_Forms::get_forms(false);
	if ( !empty($forms) ) {
		array_walk($forms, function ($id) {
			return '_ef_pro_' . engage_forms_very_safe_string($id);
		});
		//set options storage to be not autoloaded
		$where = '`option_name` = "' . implode('" OR `option_name` = "', array_keys($forms)) . '"';

		global $wpdb;
		$sql = sprintf("UPDATE `%s` SET `autoload`='no' WHERE %s", $wpdb->options, $where);
		$wpdb->get_results($sql);

	}

}

/**
 * Get the URL for the Engage Forms Pro DocSearchApp
 *
 * @since 0.0.1
 *
 * @return string
 */
function engage_forms_pro_app_url()
{

	if ( !defined('EF_PRO_APP_URL') ) {
		/**
		 * Default URL for EF Pro DocSearchApp
		 */
		define('EF_PRO_APP_URL', 'https://app.engageformspro.com');

	}

	/**
	 * Filter URL for Engage Forms Pro app
	 *
	 * Useful for local dev or running your own instance of app
	 *
	 * @since 0.0.1
	 *
	 * @param string $url The root URL for app
	 */
	return untrailingslashit(apply_filters('engage_forms_pro_app_url', EF_PRO_APP_URL));
}


/**
 * Get the URL for the Engage Forms Pro log app
 *
 * @since 0.8.0
 *
 * @return string
 */
function engage_forms_pro_log_url()
{

	/**
	 * Filter URL for Engage Forms Pro log app
	 *
	 * Useful for local dev or running your own instance of app
	 *
	 * @since 0.8.0
	 *
	 * @param string $url The root URL for app
	 */
	return untrailingslashit(apply_filters('engage_forms_pro_log_url', 'https://logger.engageformspro.com'));

}


/**
 * Create HTML for linl
 *
 * @param array $form Form config
 * @param string $link The actual link.
 *
 * @return string
 */
function engage_forms_pro_link_html($form, $link)
{

	/**
	 * Filter the classes for the generate PDF link HTML
	 *
	 * @param string $classes The classes as string.
	 * @param array $form Form config
	 */
	$classes = apply_filters('engage_forms_pro_link_classes', ' alert alert-success', $form);


	/**
	 * Filter the visible content for the generate PDF link HTML
	 *
	 * @param string $message Link message
	 * @param array $form Form config
	 */
	$message = apply_filters('engage_forms_pro_link_message', __('Download Form Entry As PDF', 'engage-forms', $form),
		$form);

	/**
	 * Filter the title attribute for the generate PDF link HTML
	 *
	 * @param string $title Title attribute.
	 * @param array $form Form config
	 */
	$title = apply_filters('engage_forms_pro_link_title', __('Download Form Entry As PDF', 'engage-forms'), $form);

	return sprintf('<div class="%s"><a href="%s" title="%s" target="_blank">%s</a></div>',
		esc_attr($classes),
		esc_url($link),
		esc_attr($title),
		esc_html($message)
	);
}


if ( !function_exists('engage_forms_safe_explode') ) {
	/**
	 * Safely exploded, what might be a string with a comma
	 * @since 1.5.8
	 *
	 * @param $string
	 *
	 * @return array
	 */
	function engage_forms_safe_explode($string)
	{
		if ( false === strpos($string, ',') ) {
			return [ $string ];
		}
		return explode(',', $string);
	}
}

/**
 * Compare public key and token to saved keys
 *
 * @since 1.5.8
 * @since 0.9.0
 *
 * @param string $public Public key to check
 * @param string $token Token to check
 *
 * @return bool
 */
function engage_forms_pro_compare_to_saved_keys($public, $token)
{
	$settings = container::get_instance()->get_settings();
	return hash_equals($public, $settings->get_api_keys()->get_public()) && hash_equals($settings->get_api_keys()->get_token(), $token);
}

/**
 * Create the URL for file request endpoints
 *
 * @since 1.5.8
 * @since 0.9.0
 *
 * @param string $path File path
 *
 * @return string
 */
function engage_forms_pro_file_request_url($path)
{
	return add_query_arg('file', urlencode($path), Engage_Forms_API_Util::url('pro/file'));
}

/**
 * Shim for boolval in PHP v5.5
 *
 * @since 0.3.1
 */
if ( !function_exists('boolval') ) {
	function boolval($val)
	{
		return (bool) $val;

	}

}

/**
 * Activation hook callback
 *
 * @since 0.11.0
 */
function engage_forms_pro_activation_hook_callback()
{
	//make sure we have autoloader
	include_once EFCORE_PATH . 'vendor/autoload.php';

	//delete old message tracking transient keys -- should only be one
	$past_versions = get_option('ef_pro_past_versions', []);
	if ( !empty($past_versions) ) {
		foreach ( $past_versions as $i => $version ) {
			Engage_Forms_Transient::delete_transient(engage_forms_pro_log_tracker_key($version));
			unset($past_versions[ $i ]);
		}

	}
	$past_versions[] = EF_PRO_VER;

	update_option('ef_pro_past_versions', $past_versions, 'no');

}

/**
 * Get the name of the EF transient (not actual transient) used to track repeat log messages
 *
 * @since 0.11.0
 *
 * @param string $version Version number
 *
 * @return string
 */
function engage_forms_pro_log_tracker_key($version)
{
	return md5(__FUNCTION__ . $version);
}

