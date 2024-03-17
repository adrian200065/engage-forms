<?php


namespace engagewp\engageforms\pro\admin;


/**
 * Class menu
 * @package engagewp\engageforms\pro\admin
 */
class menu
{

	protected $view_dir;

	/**
	 * @var scripts
	 */
	protected $scripts;

	protected $slug;

	public function __construct($view_dir, $slug, scripts $scripts)
	{
		$this->view_dir = $view_dir;
		$this->slug = $slug;
		$this->scripts = $scripts;
	}

	/**
	 * Create admin page view
	 *
	 * @since 0.1.0
	 */
	public function display()
	{
		add_submenu_page(
			\Engage_Forms::PLUGIN_SLUG,
			__('Engage Forms Pro', 'engage-forms'),
			'<span class="engage-forms-menu-dashicon"><span class="dashicons dashicons-star-filled"></span>' . __('Engage Forms Pro',
				'engage-forms') . '</span>',
			'manage_options',
			$this->slug,
			[ $this, 'render' ]
		);
	}

	/**
	 * Redner admin page view
	 *
	 * @since 0.1.0
	 */
	public function render()
	{
		echo $this->scripts->webpack($this->view_dir);

	}

}
