<?php


namespace engagewp\engageforms\pro\admin;

use engagewp\engageforms\pro\container;


/**
 * Class scripts
 * @package engagewp\engageforms\pro\admin
 */
class scripts
{

	/** @var string */
	protected $assets_url;

	/** @var string */
	protected $slug;

	/** @var string */
	protected $version;

	/**
	 * scripts constructor.
	 *
	 * @param string $assets_url Url for  assets dir
	 * @param string $slug Slug for script/css
	 * @param string $version Current version
	 */
	public function __construct($assets_url, $slug, $version)
	{
		$this->assets_url = $assets_url;
		$this->slug = $slug;
		$this->version = $version;

	}

	public function get_assets_url()
	{
		return $this->assets_url;
	}

	/**
	 * @param string $view_dir @deprecated
	 * @param null $context Default is for admin page. Pass "tab" for use in EF admin.
	 * @param bool $enqueue_admin
	 *
	 * @return string
	 */
	public function webpack($view_dir, $context = null, $enqueue_admin = true)
	{
		\Engage_Forms_Render_Assets::maybe_register();
		if ( $enqueue_admin ) {
			wp_enqueue_style(\Engage_Forms_Admin_Assets::slug('admin', false),
				\Engage_Forms_Render_Assets::make_url('admin', false));
			\Engage_Forms_Admin_Assets::set_ef_admin(\Engage_Forms_Render_Assets::make_slug('pro'));
		}
		\Engage_Forms_Render_Assets::enqueue_style('pro');
		\Engage_Forms_Render_Assets::enqueue_script('pro');
		wp_localize_script(\Engage_Forms_Render_Assets::make_slug('pro'), 'EF_PRO_ADMIN', $this->data());
		if ( 'tab' === $context ) {
			$id = 'ef-pro-app-tab';
		} else {
			$id = 'ef-pro-app';
		}
		return sprintf('<div id="%s"></div>', $id);
	}

	/**
	 * Data to localize
	 *
	 * @return array
	 */
	public function data()
	{
		$pro_url = admin_url('admin.php?page=ef-pro');

		$data = [
			'strings' => [
				'saved' => esc_html__('Settings Saved', 'engage-forms'),
				'notSaved' => esc_html__('Settings could not be saved', 'engage-forms'),
				'apiKeysViewText' => esc_html__('You must add your API keys to use Engage Forms Pro', 'engage-forms'),
				'apiKeysViewLink' => esc_url($pro_url),
				'minLogLevelTitle' => esc_html__('Minimum Log Level', 'engage-forms'),
				'minLogLevelInfo' => esc_html__('Setting a higher level than notice may affect performance, and should only be used when instructed by support.',
					'engage-forms'),
				'whatIsEFPro' => [
					'firstParagraph' => esc_html__("Engage Forms Pro is an app + plugin that makes forms easy.",
						'engage-forms'),
					'hTitle' => esc_html__('Benefits', 'engage-forms'),
					'firstLi' => esc_html__('Enhanced Email Delivery', 'engage-forms'),
					'secondLi' => esc_html__('Form To PDF', 'engage-forms'),
					'thirdLi' => esc_html__('Priority Support.', 'engage-forms'),
					'fourthLi' => esc_html__('Add-ons Included in Yearly Plans', 'engage-forms'),
				],
				'freeTrial' => [
					'firstParagraph' => esc_html__('Ready to try Engage Forms Pro? Plans start at just $14.99/ month with a 7 day free trial.',
						'engage-forms'),
					'buttonLeft' => esc_html__('View Documentation', 'engage-forms'),
					'buttonRight' => esc_html__('Start Free Trial', 'engage-forms'),
				],
				'notConnected' => esc_html__('Not Connected', 'engage-forms'),
				'connected' => esc_html__('Connected', 'engage-forms'),
				'tabNames' => [
					'account' => esc_html__('Account', 'engage-forms'),
					'formSettings' => esc_html__('Form Settings', 'engage-forms'),
					'settings' => esc_html__('Settings', 'engage-forms'),
					'whatIsEFPro' => esc_html__('What is Engage Forms Pro ?', 'engage-forms'),
					'freeTrial' => esc_html__('Free Trial', 'engage-forms'),
				],
			],
			'api' => [
				'ef' => [
					'url' => esc_url_raw(\Engage_Forms_API_Util::url('settings/pro')),
					'nonce' => wp_create_nonce('wp_rest'),
				],
				'efPro' => [
					'url' => esc_url_raw(engage_forms_pro_app_url()),
					'auth' => [],
				],
			],
			'settings' => container::get_instance()->get_settings()->toArray(),
			'logLevels' => container::get_instance()->get_settings()->log_levels(),
		];

		$data[ 'formScreen' ] = \Engage_Forms_Admin::is_edit() ? esc_attr($_GET[ \Engage_Forms_Admin::EDIT_KEY ]) : false;

		return $data;
	}
}
