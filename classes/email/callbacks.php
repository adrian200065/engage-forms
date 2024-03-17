<?php

/**
 * Callbacks functions used to hook in and replace default mailer at "engage_forms_mailer"
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Email_Callbacks {

	/**
	 * Send email via SendGrid API
	 *
	 * @since 1.4.0
	 *
	 * @uses "engage_forms_mailer"
	 *
	 * @param $mail
	 * @param $data
	 * @param $form
	 *
	 * @return mixed
	 */
	public static function sendgrid( $mail, $data, $form ){
		$client = new Engage_Forms_Email_SendGrid( $mail );
		$key = Engage_Forms_Email_Settings::get_key( 'sendgrid' );
		if ( ! empty( $key ) ) {
			$client->set_api( array( $key ) );
			$response = $client->send();
			if( in_array( $response, array( 202, 201, 200 ) ) ){
				Engage_Forms_Save_Final::after_send_email( $form, $data, true, $mail[ 'csv' ], $mail, 'sendgrid' );
				//prevent send
				return null;
			}else{
				/**
				 * Action documented in Engage_Forms_Save_Final::after_send_email()
				 */
				do_action( 'engage_forms_mailer_failed', $mail, $data, $form, 'sendgrid' );
				//fallback to default
				return $mail;
			}
			
		}
		
	}
	
}
