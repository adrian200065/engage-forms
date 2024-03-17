<?php

/**
 * Interface that CRM add-ons should implement
 *
 * @package   Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
interface Engage_Forms_Processor_Interface_CRM extends Engage_Forms_Processor_Interface_Process {

	/**
	 * Update CRM Records using an Email Address as the lookup key
	 *
	 * @since 1.5.1
	 *
	 * @param string $email The e-mail address to lookup in the CRM
	 * @param array $form_data An array containing the fields to be updated
	 *
	 * @return mixed
	 */
	public function update_by_email( $email, array $form_data );

}
