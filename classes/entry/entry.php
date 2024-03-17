<?php

/**
 * Object representation of an entry (basic info, no values) - ef_form_entries
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Entry_Entry extends Engage_Forms_Entry_Object {

	/** @var  string */
	protected $id;

	/** @var  string */
	protected $form_id;

	/** @var  string */
	protected $user_id;

	/** @var  string */
	protected $datestamp;

	/** @var  string */
	protected $status;

}
