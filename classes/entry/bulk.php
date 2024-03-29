<?php

/**
 * Bulk actions for entries
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Entry_Bulk {

	/**
	 * Delete all entry data for an array of entries
	 *
	 * @since 1.4.0
	 *
	 * @param array $entry_ids Array of entries to delete
	 *
	 * @return false|int
	 */
	public static function delete_entries( $entry_ids ){

		global $wpdb;
		$result = $wpdb->query( "DELETE FROM `" . $wpdb->prefix . "ef_form_entries` WHERE `id` IN (" . implode( ',', $entry_ids ) . ");" );
		$result = $wpdb->query( "DELETE FROM `" . $wpdb->prefix . "ef_form_entry_values` WHERE `entry_id` IN (" . implode( ',', $entry_ids ) . ");" );
		$result = $wpdb->query( "DELETE FROM `" . $wpdb->prefix . "ef_form_entry_meta` WHERE `entry_id` IN (" . implode( ',', $entry_ids ) . ");" );

		/**
		 * Fires after Engage Forms entries are deleted
		 *
		 * @since 1.5.0.9
		 *
		 * @param array $entry_ids Array of entries that were deleted
		 */
		do_action( 'engage_forms_delete_entries', $entry_ids );

		return $result;
		
	}

	/**
	 * Update status for an array of entries
	 *
	 * @since 1.4.0
	 *
	 * @param array $entry_ids Array of entries to change
	 * @param string $status New status
	 *
	 * @return false|int
	 */
	public static function change_status( $entry_ids, $status ){
		global $wpdb;
		$result = $wpdb->query( $wpdb->prepare( "UPDATE `" . $wpdb->prefix . "ef_form_entries` SET `status` = %s WHERE `id` IN (" . implode( ',', $entry_ids ) . ");", $status ) );

		/**
		 * Fires after selected entries' status is updated
		 *
		 * @since 1.5.0.9
		 *
		 * @param array $entry_ids Array of entries changed
		 * @param string $status New status
		 */
		do_action( 'engage_forms_change_entry_status', $entry_ids, $status );

		return $result;

	}

	/**
	 * Get entry count by status
	 *
	 * @since 1.4.0
	 *
	 * @param string $form_id Form ID
	 * @param string $status Status
	 *
	 * @return int
	 */
	public static function count( $form_id, $status = 'active' ){
		global $wpdb;
		if ( false == $status ) {
			$sql = $wpdb->prepare( "SELECT COUNT(`id`) AS `total` FROM `" . $wpdb->prefix . "ef_form_entries` WHERE `form_id` = %s", $form_id );
		}else{
			$sql = $wpdb->prepare( "SELECT COUNT(`id`) AS `total` FROM `" . $wpdb->prefix . "ef_form_entries` WHERE `form_id` = %s AND `status` = %s;", $form_id, $status );
		}
		$total = $wpdb->get_var( $sql );

		return (int) $total;
	}

}
