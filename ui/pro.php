<?php
/**
 * This file is used to create ef-pro admin page WHEN EF PRO CAN NOT BE USED
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

?>
<div class="engage-editor-header">
	<ul class="engage-editor-header-nav">
		<li class="engage-editor-logo">
			<span class="engage-forms-name">
				Engage Forms Pro
			</span>
		</li>
	</ul>
</div>

<div class="postbox" style="margin-top: 75px;padding: 8px;">
	<?php
        $message = __( 'Engage Forms Pro could not be loaded because your site\'s version of PHP is out of date. Engage Forms Pro requires PHP 5.6 or later.', 'engage-forms' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( 'notice notice-error' ), esc_html( $message ) );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( 'notice notice-warning' ), __( 'For more information, please see: ', 'ef-pro' ) . ' <a href="https://engageforms.com/php?utm_source=wp-admin&utm_keyword=php_version">EngageForms.com/php</a>' );
    ?>
</div>
