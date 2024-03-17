<?php


/**
 * Class Engage_Forms_Admin_Resend
 */
class Engage_Forms_Admin_Resend {

	/**
	 * Nonce action for resend links
	 *
	 * @since 1.5.2
	 *
	 * @var string
	 */
	protected static $nonce_action = '_ef_resend';

	/**
	 * Generate nonce for resend links
	 *
	 * @since 1.5.2
	 *
	 * @return string
	 */
	public static function resend_nonce(){
		return wp_create_nonce( self::$nonce_action );
	}

	/**
	 * Verify the resend nonce
	 *
	 * @since 1.5.2
	 *
	 * @param string $nonce Nonce to check
	 *
	 * @return false|int
	 */
	public static function verify_nonce( $nonce ){
		return wp_verify_nonce( $nonce, self::$nonce_action );

	}

	/**
	 * Watch for resend requests and process if so and authorized, etc.
	 *
	 * @uses "init" action
	 *
	 * @since 1.5.2
	 *
	 */
	public static function watch_for_resend(){
		if( isset( $_GET[ '_ef_resend' ], $_GET[ 'e' ], $_GET[ 'f' ] ) && self::verify_nonce($_GET[ '_ef_resend' ]  ) ){
			if ( ! current_user_can( Engage_Forms::get_manage_cap( 'resend-email' ) ) ) {
				return;
			}
			$form = Engage_Forms_Forms::get_form( $_GET[ 'f' ] );
			if( is_array( $form ) ){
				$resender = new Engage_Forms_Email_Resend( absint( $_GET[ 'e' ] ), $form  );

				$resender->resend();
				ef_redirect( add_query_arg( 'message_resent', 'true', Engage_Forms_Admin::main_admin_page_url()  ) );
				exit;

			}


		}

 	}


}