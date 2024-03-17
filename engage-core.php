<?php
/*
  Plugin Name: Engage Forms
  Plugin URI: https://EngageForms.com
  Description: Easy to use, grid based responsive form builder for creating simple to complex forms.
  Author: Engage Forms
  Version: 3.0.0
  Author URI: https://EngageForms.com
  Text Domain: engage-forms
  GitHub Plugin URI: https://github.com/EngageWP/engage-forms
  Release Asset: true
*/

// If this file is called directly, abort.
if ( !defined('WPINC') ) {
	die;
}

//Check version minimums first
global $wp_version;
if ( !version_compare(PHP_VERSION, '8.0.0', '>=') ) {
	function engage_forms_php_version_nag()
	{
		?>
        <div class="notice notice-error">
            <p>
				<?php esc_html_e('Your version of PHP is incompatible with Engage Forms and can not be used.',
					'engage-forms'); ?>
				<?php printf(' <a href="https://engageforms.com/php?utm_source=wp-admin&utm_campaign=php_deprecated&utm_source=admin-nag" target="__blank">%s</a>',
					esc_html__('Learn More', 'engage-forms')
				) ?></p>
        </div>
		<?php
	}

	add_shortcode('engage-form', 'engage_forms_fallback_shortcode');
	add_shortcode('engage-form_modal', 'engage_forms_fallback_shortcode');
	add_action('admin_notices', 'engage_forms_php_version_nag');
} elseif ( !version_compare($wp_version, '6.0.0', '>=') ) {
	function engage_forms_wp_version_nag(){
		?>
		<div class="notice notice-error">
		    <p>
			<?php esc_html_e('Your version of WordPress is incompatible with Engage Forms and can not be used.', 'engage-forms'); ?>
		    </p>
		</div>
		<?php
	}

	add_shortcode('engage-form', 'engage_forms_fallback_shortcode');
	add_shortcode('engage-form_modal', 'engage_forms_fallback_shortcode');
	add_action('admin_notices', 'engage_forms_wp_version_nag');
} else {
	define('EFCORE_PATH', plugin_dir_path(__FILE__));
	define('EFCORE_URL', plugin_dir_url(__FILE__));
	define( 'EFCORE_VER', '3.0.0' );
	define('EFCORE_EXTEND_URL', 'https://api.engageforms.com/1.0/');
	define('EFCORE_BASENAME', plugin_basename(__FILE__));

	/**
	 * Engage Forms DB version
	 *
	 * @since 1.3.4
	 *
	 * PLEASE keep this an integer
	 */
	define('EF_DB', 8);

	// init internals of EF
	include_once EFCORE_PATH . 'classes/core.php';

	add_action('init', [ 'Engage_Forms', 'init_ef_internal' ]);
	// table builder
	register_activation_hook(__FILE__, [ 'Engage_Forms', 'activate_engage_forms' ]);


	// load system
	add_action('plugins_loaded', 'engage_forms_load', 0);
	function engage_forms_load()
	{
		include_once EFCORE_PATH . 'classes/autoloader.php';
		include_once EFCORE_PATH . 'classes/widget.php';
		Engage_Forms_Autoloader::add_root('Engage_Forms_DB', EFCORE_PATH . 'classes/db');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Entry', EFCORE_PATH . 'classes/entry');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Email', EFCORE_PATH . 'classes/email');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Admin', EFCORE_PATH . 'classes/admin');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Render', EFCORE_PATH . 'classes/render');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Sync', EFCORE_PATH . 'classes/sync');
		Engage_Forms_Autoloader::add_root('Engage_Forms_CSV', EFCORE_PATH . 'classes/csv');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Processor_Interface', EFCORE_PATH . 'processors/classes/interfaces');
		Engage_Forms_Autoloader::add_root('Engage_Forms_API', EFCORE_PATH . 'classes/api');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Field', EFCORE_PATH . 'classes/field');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Magic', EFCORE_PATH . 'classes/magic');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Processor', EFCORE_PATH . 'processors/classes');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Shortcode', EFCORE_PATH . 'classes/shortcode');
		Engage_Forms_Autoloader::add_root('Engage_Forms_CDN', EFCORE_PATH . 'classes/cdn');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Settings', EFCORE_PATH . 'classes/settings');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Import', EFCORE_PATH . 'classes/import');
		Engage_Forms_Autoloader::add_root('Engage_Forms_Query', EFCORE_PATH . 'classes/query');
		Engage_Forms_Autoloader::add_root('Engage_Forms', EFCORE_PATH . 'classes');
		Engage_Forms_Autoloader::register();


		// includes
		include_once EFCORE_PATH . 'includes/ajax.php';
		include_once EFCORE_PATH . 'includes/field_processors.php';
		include_once EFCORE_PATH . 'includes/custom_field_class.php';
		include_once EFCORE_PATH . 'includes/filter_addon_plugins.php';
		include_once EFCORE_PATH . 'includes/compat.php';
		include_once EFCORE_PATH . 'processors/functions.php';
		include_once EFCORE_PATH . 'includes/functions.php';
		include_once EFCORE_PATH . 'ui/blocks/init.php';
		include_once EFCORE_PATH . 'vendor/autoload.php';
		include_once EFCORE_PATH . 'includes/ef-pro-client/ef-pro-init.php';
		include_once EFCORE_PATH . 'sendwp/init.php';

		/**
		 * Runs after all of the includes and autoload setup is done in Engage Forms core
		 *
		 * @since 1.3.5.3
		 */
		do_action('engage_forms_includes_complete');

		add_filter( 'script_loader_src',
		 [Engage_Forms_Render_Assets::class,'maybe_remove_version_query_arg'],
		15, 2 );
		add_filter( 'style_loader_src',
		 [Engage_Forms_Render_Assets::class,'maybe_remove_version_query_arg'],
		15, 2 );

		add_action( 'init', [Engage_Forms_Render_Assets::class,'maybe_redirect_to_dist']);
		/**
		 * Start ef2 system
		 *
		 * @since 1.8.0
		 */
		add_action('engage_forms_v2_init', 'engage_forms_v2_container_setup' );
		engage_forms_get_v2_container();
	}

	add_action('plugins_loaded', [ 'Engage_Forms', 'get_instance' ]);


	// Admin & Admin Ajax stuff.
	if ( is_admin() || defined('DOING_AJAX') ) {
		add_action('plugins_loaded', [ 'Engage_Forms_Admin', 'get_instance' ]);
		add_action('plugins_loaded', [ 'Engage_Forms_Support', 'get_instance' ]);
		include_once EFCORE_PATH . 'includes/plugin-page-banner.php';
	}


	//@see https://github.com/EngageWP/Engage-Forms/issues/2855
	add_filter( 'engage_forms_pro_log_mode', '__return_false' );
	add_filter( 'engage_forms_pro_mail_debug', '__return_false' );
}

/**
 * Shortcode handler to be used when Engage Forms can not be loaded
 *
 * @since 1.7.0
 *
 * @return string
 */
function engage_forms_fallback_shortcode()
{
	if ( current_user_can('edit_posts') ) {
		return esc_html__('Your version of WordPress or PHP is incompatible with Engage Forms.', 'engage-forms');
	}

	return esc_html__('Form could not be loaded. Contact the site administrator.', 'engage-forms');

}