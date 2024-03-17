<?php

/**
 * Creates an email preview
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Email_Preview extends Engage_Forms_Email_Save {

	/**
	 * @inheritdoc
	 */
	public function jsonSerialize() {
		return array(
			'headers' => $this->headers(),
			'message' => $this->body(),
			'content-type' => $this->content_type()

		);

	}
	
}