<?php

/**
 * Interface that all CDN integrations must impliment
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
interface Engage_Forms_CDN_Contract {

	/**
	 * The URL for CDN to replace site URL with
	 *
	 * NOTE: Do NOT add protocol. start with //
	 *
	 * @since 1.5.3
	 *
	 * @return string
	 */
	public function cdn_url();

}