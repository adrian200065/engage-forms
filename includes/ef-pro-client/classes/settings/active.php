<?php

namespace engagewp\engageforms\pro\settings;

use engagewp\engageforms\pro\container;


/**
 * Class active
 * @package engagewp\engageforms\pro\settings
 */
class active
{

	/**
	 * Check if EF Pro API is active for this site or not
	 *
	 * @since 0.2.0
	 *
	 * @return bool
	 */
	public static function get_status()
	{
		$active = container::get_instance()->get_settings()->get_api_keys()->get_public();
		if ( $active ) {
			$active = container::get_instance()->get_settings()->get_api_keys()->get_secret();
		}
		/**
		 * Override active status
		 *
		 * @since 0.2.0
		 *
		 * @param bool $status
		 */
		return (bool) apply_filters('engage_forms_pro_is_active', $active);

	}

}
