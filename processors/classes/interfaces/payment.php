<?php

/**
 * Interface that payment processor add-ons should implement
 *
 * @package   Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
interface Engage_Forms_Processor_Interface_Payment {
	/**
	 * Do Payment
	 *
	 * @since 1.3.5.3
	 *
	 * @param array $config Processor config
	 * @param array $form Form config
	 * @param string $proccesid Unique ID for this instance of the processor
	 * @param Engage_Forms_Processor_Get_Data $data_object Processor data
	 *
	 * @return Engage_Forms_Processor_Get_Data
	 */
	public function do_payment( array $config, array $form, $proccesid, Engage_Forms_Processor_Get_Data $data_object );
}
