<?php
/**
 * Base class for object representations of database rows
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
abstract class Engage_Forms_Entry_Object extends Engage_Forms_Object {


	/**
	 * @inheritdoc
	 */
	protected function get_prefix(){
		return 'entry';
	}

}
