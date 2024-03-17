<?php

// Simple Filter for plugins page to help filter engage forms addons into its own lost.

	add_filter( 'views_plugins', 'ef_filter_addons_filter_addons' );
	add_filter( 'show_advanced_plugins', 'ef_filter_addons_do_filter_addons' );
	add_action( 'check_admin_referer', 'ef_filter_addons_prepare_filter_addons_referer', 10, 2 );

	function ef_filter_addons_prepare_filter_addons_referer($a, $b){
		global $status;
		if( !function_exists('get_current_screen')){
			return;
		}
		$screen = get_current_screen();
		if( is_object($screen) && $screen->base === 'plugins' && !empty($_REQUEST['plugin_status']) && $_REQUEST['plugin_status'] === 'engage_forms'){
			$status = 'engage_forms';
		}

	}
	function ef_filter_addons_do_filter_addons($a){
		global $plugins, $status;

		foreach($plugins['all'] as $plugin_slug=>$plugin_data){
			if( false !== strpos($plugin_data['Name'], 'Engage Forms') || false !== strpos($plugin_data['Description'], 'Engage Forms') ){
				$plugins['engage_forms'][$plugin_slug] = $plugins['all'][$plugin_slug];
				$plugins['engage_forms'][$plugin_slug]['plugin'] = $plugin_slug;
				// replicate the next step
				if ( current_user_can( 'update_plugins' ) ) {
					$current = get_site_transient( 'update_plugins' );
					if ( isset( $current->response[ $plugin_slug ] ) ) {
						$plugins['engage_forms'][$plugin_slug]['update'] = true;
					}
				}

			}
		}

		return $a;
	}


	function ef_filter_addons_filter_addons($views){
		global $status, $plugins;

		if( !empty( $plugins['engage_forms'] ) ){
			$class = "";
			if( $status == 'engage_forms' ){
				$class = 'current';
			}
			$views['engage_forms'] = '<a class="' . $class . '" href="plugins.php?plugin_status=engage_forms">' . __('Engage Forms', 'engage-forms') .' <span class="count">(' . count( $plugins['engage_forms'] ) . ')</span></a>';
		}
		return $views;
	}