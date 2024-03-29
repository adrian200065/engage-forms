<?php
/**
 * Sets the defaults specified by a shortcode, unique for each shortcode usage
 *
 * Solves problem where two shortcodes of same form have different defaults field sets, see: https://github.com/EngageWP/Engage-Forms/issues/1499
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
class Engage_Forms_Shortcode_Defaults {

	/**
	 * Form ID this object acts on
	 *
	 * @since 1.5.0.9
	 *
	 * @var string
	 */
	protected $form_id;

	/**
	 * Defaults to add to fields
	 *
	 * @since 1.5.0.9
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Engage_Forms_Shortcode_Defaults constructor.
	 *
	 * @since 1.5.0.9
	 *
	 * @param string $form_id ID of form
	 * @param array $defaults Defaults
	 */
	public function __construct( $form_id, $defaults ){
		$this->form_id = $form_id;
		$this->defaults = $defaults;

	}

	/**
	 * Add the filter
	 *
	 * @since 1.5.0.9
	 */
	public function add_hooks(){
		add_filter( 'engage_forms_render_get_field', array( $this, 'set_default' ), 19, 2 );
	}

	/**
	 * Remove the filter
	 *
	 * @since 1.5.0.9
	 */
	public function remove_hooks(){
		remove_filter( 'engage_forms_render_get_field', array( $this, 'set_default' ), 19 );
	}

	/**
	 * Set field default
	 *
	 * @since 1.5.0.9
	 *
	 * @uses "engage_forms_render_get_field" filter
	 *
	 * @param array $field Field config
	 * @param array $form Form config
	 *
	 * @return array
	 */
	public function set_default( $field, $form ){
		if( array_key_exists( $field[ 'ID' ], $this->defaults ) && $form[ 'ID' ] == $this->form_id ){
			$field[ 'config' ][ 'default' ] = $this->defaults[ $field[ 'ID' ] ];
		}

		return $field;

	}

}