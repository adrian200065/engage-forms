<?php


namespace engagewp\engageforms\ef2\Fields;


interface RegisterFieldsContract
{


	/**
	 * Get path to main plugin file
	 *
	 * @since 1.8.9
	 *
	 * @return string
	 */
	public function getCoreDirPath();

	/**
	 * Add ef2 field types to an array of field types
	 *
	 * @since 1.8.0
	 *
	 * Designed to be hooked to "engage_forms_get_field_types"
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	public function filter($fields);


}
