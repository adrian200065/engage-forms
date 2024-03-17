<?php

/**
 * REST API route for verification tokens
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_API_Tokens implements Engage_Forms_API_Route {

	/**
	 * @since 1.5.0
	 * @deprecated 1.6.2
	 * @inheritdoc
	 */
	public function add_routes( $namespace ) {
		_deprecated_function( __FUNCTION__, '1.6.2', '');
		register_rest_route( $namespace, '/tokens/form',
			array(
				'methods'         => 'post',
				'callback'        => array( $this, 'get_new_nonce' ),
			        'permission_callback' => TRUE,
				'args'            => array(
					'form_id' => array(
						'required'          => 'true',
						'type'              => 'string',
						'validate_callback' => array( $this, 'form_exists' )
					)
				)
			)
		);
	}

	/**
	 * Check that form exists, by ID
	 *
	 * @since 1.5.0
	 * @deprecated 1.6.2
	 * @param string $form_id
	 *
	 * @return bool
	 */
	public function form_exists( $form_id ){
		_deprecated_function( __FUNCTION__, '1.6.2', '');
		$form = Engage_Forms_Forms::get_form( $form_id );
		return ( is_array( $form ) && ! empty( $form ) );
	}

	/**
	 * Get a new form nonce
	 *
	 * @since 1.5.0
	 * @deprecated 1.6.2
	 *
	 * @param WP_REST_Request $request REST API request object
	 *
	 * @return Engage_Forms_API_Response
	 */
	public function get_new_nonce( WP_REST_Request $request ){
		_deprecated_function( __FUNCTION__, '1.6.2', '');
		$form_id = $request[ 'form_id' ];
		$nonce = Engage_Forms_Render_Nonce::create_verify_nonce( $form_id );
		$response = new Engage_Forms_API_Response( array(
			'nonce' => $nonce,
		) );
		return $response;

	}
}
