<?php
/**
 * Update entry values
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */

/**
 * Class Engage_Forms_Entry_Update
 */
class Engage_Forms_Entry_Update {

	/**
	 * Update an entry's status
	 *
	 * @since 1.5.0
	 *
	 * @param string $new_status
	 * @param int|string $entry_id
	 *
	 * @return bool
	 */
	public static function update_entry_status(  $new_status, $entry_id ){
		$result = Engage_Forms_Entry_Bulk::change_status( array( $entry_id ), $new_status );

		return is_int( $result );

	}

	/**
	 * Update a saved field in the database.
	 *
	 * @since 1.5.0.7
	 *
	 * @param Engage_Forms_Entry_Field $field
	 *
	 * @return int
	 */
	public static function update_field( Engage_Forms_Entry_Field $field ){
		global $wpdb;

		$wpdb->update( $wpdb->prefix . 'ef_form_entry_values', $field->to_array(), array(
			'id' => $field->id
		) );

		return $wpdb->insert_id;
	}

	/**
	 * Update field value
	 *
	 * @since 1.5.0.7
	 *
	 * @param string $field_id Field ID
	 * @param int $entry_id Entry ID
	 * @param string $value Field value, prepared and sanitized for database.
	 */
	public static function update_field_value( $field_id, $entry_id, $value ){
		global $wpdb;

		$wpdb->update( $wpdb->prefix . 'ef_form_entry_values', array(
			'value' => $value
		), array(
			'field_id' => $field_id,
			'entry_id' => $entry_id
		) );

	}
}