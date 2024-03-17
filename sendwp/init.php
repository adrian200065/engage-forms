<?php

include_once EFCORE_PATH . 'sendwp/handler.php';

/**
 * Enqueue the SendWP JS SDK.
 */
function engage_forms_admin_enqueue_sendwp_installer() {
    wp_enqueue_script('engage_forms_sendwp_installer', plugins_url('installer.js', __FILE__));
    wp_localize_script('engage_forms_sendwp_installer', 'sendwp_vars', [
       'nonce'  =>  wp_create_nonce( 'sendwp_install_nonce' ),
       'security_failed_message'    =>  esc_html__( 'Security failed to check sendwp_install_nonce', 'engage-forms'),
       'user_capability_message'    =>  esc_html__( 'Ask an administrator for install_plugins capability', 'engage-forms'),
       'sendwp_connected_message'   =>  esc_html__( 'SendWP is already connected.', 'engage-forms'),
    ]);
}
add_action('engage_forms_admin_main_enqueue', 'engage_forms_admin_enqueue_sendwp_installer');