<?php


/**
 * Class init
 */
class Engage_Forms_CDN_Init {

	/**
	 * In use CDN implementation
	 *
	 * @since 1.5.3
	 *
	 * @var Engage_Forms_CDN_Jsdelivr|Engage_Forms_CDN
	 */
	protected static $cdn;

	/**
	 * Implement core settings
	 *
	 * @since 1.5.3
	 */
	public static function init(){
		$cdn_enabled = Engage_Forms::settings()->get_cdn()->enabled();
		if( $cdn_enabled ){
			self::$cdn = new Engage_Forms_CDN_Jsdelivr( EFCORE_URL, EFCORE_VER );
			self::$cdn->add_hooks();
		}
	}

	/**
	 * Get CDN implementation
	 *
	 * @since 1.5.3
	 *
	 * @return Engage_Forms_CDN_Jsdelivr|Engage_Forms_CDN
	 */
	public static function get_cdn(){
		return self::$cdn;
	}
}