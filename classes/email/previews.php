<?php

/**
 * Manages an email preview
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Email_Previews {

	/**
	 * Form ID
	 *
	 * @since 1.4.0
	 *
	 * @var null|string
	 */
	protected  $id;

	/**
	 * Engage_Forms_Email_Previews constructor.
	 *
	 * @param null|string $id Optional. Form ID
	 * @param bool $view Optional. Whether to view (true) or record (false) the preview. Default is false.
	 */
	public function __construct( $id = null, $view = false ) {
		if( $view && null != $id ){
			$this->id = $id;
			$this->view();
		}else{
			add_filter( 'engage_forms_mailer', array( $this, 'maybe_create_preview' ), 99, 3 );
		}



	}

	/**
	 * Display email preview
	 *
	 * @since 1.4.0
	 */
	public function view(){
		if ( isset( $this->id ) ) {
			$preview = $this->get_saved( $this->id );
			if ( is_object( $preview ) && ! empty( $preview ) ) {
				$headers = $this->format_headers( $preview->headers );
				$message = $preview->message;
				include EFCORE_PATH . 'ui/emails/email-preview.php';
				exit;
			}else{
				wp_die( esc_html__( 'There is no saved email preview. Please submit this form with email previewing on and then try again.', 'engage-forms' ) );
			}

		}

	}

	/**
	 * Format header view
	 *
	 * Create UL markup from array
	 *
	 * @since 1.4.0
	 *
	 * @param $headers
	 *
	 * @return string
	 */
	public function format_headers( $headers ){
		$view = array();
		$pattern = '<li><pre>%s</pre>: <span>%s</span></li>';
		foreach ( $headers as $header => $value ){
			if ( is_string( $value ) ){
				$view[ $header ] = sprintf( $pattern, ucwords( $header ), htmlentities( $value ) );
			}else{
				$view[ $header ] = sprintf( $pattern, ucwords( $header ), $this->format_headers( $value ) );
			}

		}

		return sprintf( '<ul>%s</ul>', implode( $view ) );
	}

	/**
	 * If preview should be recorded, record
	 *
	 * @since 1.4.0
	 *
	 * @uses "engage_forms_mailer" filter
	 *
	 * @param array $mail
	 * @param array $data
	 * @param array $form
	 *
	 * @return array
	 */
	public function maybe_create_preview( $mail, $data, $form ){
		if( ! empty( $form[ 'mailer' ][ 'preview_email' ] ) ){
			$this->id = $form[ 'ID' ];
			$preview = new Engage_Forms_Email_Preview( $mail  );
			$this->record( $preview );
		}

		return $mail;
	}

	/**
	 * Get saved preview as stdClass
	 * 
	 * @param null|string $id Optional. Form ID. By default $this->id is used. Pass ID here to reset that property.
	 *
	 * @return bool|object
	 */
	public function get_saved( $id = null ){
		if( is_string( $id ) ){
			$this->id = $id;
		}

		$_preview = get_option( $this->key() );
		if( ! empty( $_preview ) && is_object( $preview = json_decode( $_preview ) ) ){
			return $preview;
		}else{
			return false;
		}

	}

	/**
	 * Save preview
	 * 
	 * @since 1.4.0
	 * 
	 * @param Engage_Forms_Email_Preview $preview Preview object
	 */
	protected function record( Engage_Forms_Email_Preview $preview ){
		if ( false == get_option( $this->key( ) ) ){
			add_option( $this->key(), wp_json_encode( $preview ), false );
		}else{
			update_option( $this->key(), wp_json_encode( $preview ), false );
		}

	}

	/**
	 * Option key
	 * 
	 * @since 1.4.0
	 * 
	 * @return string
	 */
	protected function key( ){
		return '__ef_email_preview_' . $this->id;
		
	}
	
}