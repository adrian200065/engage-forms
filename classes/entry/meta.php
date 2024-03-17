<?php

/**
 * Object representation of an entry meta - ef_entry_meta
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Entry_Meta extends Engage_Forms_Entry_Object {

	/** @var  string */
	protected $meta_id;

	/** @var  string */
	protected $entry_id;

	/** @var  string */
	protected $process_id;

	/** @var  string */
	protected $meta_key;

	/** @var  string|array */
	protected $meta_value;

	/**
	 * Apply deserialization/json_decoding if needed to meta_value column
	 *
	 * @since 1.4.0
	 *
	 * @param string $value Meta value
	 */
	protected function meta_value_set( $value ){
		if( is_array( $value ) ){
			$this->meta_value = $value;
		} elseif( is_serialized( $value  ) ){
			$this->meta_value = unserialize( $value );
		}elseif( 0 === strpos( $value, '{' ) && is_object( $_value = json_decode( $value ) ) ){
			$this->meta_value = (array) $_value;
		}else{
			$this->meta_value = $value;
		}

	}
}
