<?php

/**
 * Filters shortcode attributes
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 EngageWP LLC
 */
class Engage_Forms_Shortcode_Atts {

	/**
	 * Setup field defaults form shortocde attributes
	 *
	 * @since 1.5.0.7
	 *
	 * @uses "shortcode_atts_engage-form" filter
	 * @uses "shortcode_atts_engage-form_modal" filter
	 *
	 * @param array $out
	 * @param array $pairs
	 * @param array $atts
	 * @param string $shortcode
	 *
	 * @return array
	 */
	public static function allow_default_set( $out, $pairs, $atts, $shortcode ){
		$form = array();
		if ( isset( $atts[ 'id' ] ) ) {
			$form = Engage_Forms_Forms::get_form( $atts[ 'id' ] );

		}

		if ( empty( $form ) && isset( $atts[ 'ID' ] ) ) {
			$form = Engage_Forms_Forms::get_form( $atts[ 'ID' ] );

		}

		if ( empty( $form ) && isset( $form[ 'name' ] ) ) {
			$form = Engage_Forms_Forms::get_form( $atts[ 'name' ] );
		}

		$defaults = array();

		if( ! empty( $form ) ){
			$fields = Engage_Forms_Forms::get_fields( $form );
			$field_ids = array_keys( $fields );

			if( ! empty( $field_ids ) ){
				foreach ( $atts as $att => $value ){
					if( in_array( $att, $field_ids ) ){
						$defaults[ $att ] = $value;
						$out[ $att ] = $value;
					}
				}
			}
		}

		if( ! empty( $defaults ) ){
			$obj = new Engage_Forms_Shortcode_Defaults( $form[ 'ID' ], $defaults );
			$obj->add_hooks();
			add_action( 'engage_forms_render_end', array( $obj, 'remove_hooks' ) );

		}

		return $out;

	}

	/**
	 * Whitleist revision shortcode arg if user has permissions
	 *
	 * @since 1.5.3
	 *
	 * @uses "shortcode_atts_engage-form" filter
	 * @uses "shortcode_atts_engage-form_modal" filter
	 *
	 * @param array $out
	 * @param array $pairs
	 * @param array $atts
	 * @param string $shortcode
	 *
	 * @return array
	 */
	public static  function  maybe_allow_revision(  $out, $pairs, $atts, $shortcode ){
		if( current_user_can( Engage_Forms::get_manage_cap( 'admin' ) ) ){
			if( isset( $atts[ 'revision' ] ) ){
				$out[  'revision' ] = $atts[ 'revision' ];
			}
		}

		return $out;
	}

}