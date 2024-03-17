<?php


/**
 * Methods for creating Engage Forms in modals
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
class Engage_Forms_Render_Modals {

	/**
	 * Holds modal HTML to be loaded in footer
	 *
	 * @since 1.5.0.7
	 * since 1.4.2 in Engage_Forms
	 *
	 * @var string
	 */
	protected static $footer_modals;

	/**
	 * Load a Engage Form in a modal.
	 *
	 * @since 1.5.0.7
	 * since unknown in Calder_Forms
	 *
	 * @param string|array $atts Shortcode atts or form ID
	 * @param string $content Content to use in trigger link.
	 *
	 * @return string
	 */
	public static function modal_form( $atts, $content, $is_revision = false ) {

		if ( empty( $atts[ 'id' ] ) ) {
			return $content;
		}
		if ( ! $is_revision ) {
			$form = Engage_Forms_Forms::get_form( $atts[ 'id' ] );
		}else{
			$form = $atts;
		}

		if ( empty( $form[ 'ID' ] ) || $form[ 'ID' ] != $atts[ 'id' ] ) {
			return $content;
		}

		$modal_id = self::modal_id( $form );

		$out = self::modal_button( $atts, $content, $form, $modal_id );

		if(!empty($_GET['ef_er'])){
			$transdata = Engage_Forms_Transient::get_transient( $_GET[ 'ef_er' ] );
			if($transdata['transient'] == $_GET['ef_er']){
				$current_state = 'style="display:block;"';
			}
		}
		if ( ! empty( $_GET[ 'ef_su' ] ) ) {
			// disable notices
			unset( $_GET[ 'ef_su' ] );
		}

		$form_html =  Engage_Forms::render_form( $atts );

		self::add_to_footer( self::modal_body( $form_html, $modal_id, $atts[ 'id' ] ) );

		return $out;
	}

	/**
	 * Create modal body
	 *
	 * @since 1.5.0.7
	 *
	 * @param string $form_html HTML for modal body
	 * @param string $modal_id ID attribute for modal Should be created with self::modal_id()
	 * @param string $classes Optional. Additional classes for the modal. "remodal engage-front-modal-container" are always applied
	 *
	 * @return string
	 */
	public static function modal_body( $form_html, $modal_id, $form_id, $classes = '' ){
		$class_attr = 'remodal engage-front-modal-container';
		if( ! empty( $classes ) ){
			$class_attr .= $classes;
		}

		ob_start();
		?>
		<div data-remodal-id="<?php echo esc_attr( $modal_id ); ?>" id="<?php echo esc_attr( $modal_id ); ?>" class="<?php echo esc_attr( $class_attr ); ?>" data-form-id="<?php echo esc_attr( $form_id ); ?>"  data-remodal-options="hashTracking: true, closeOnOutsideClick: false">
			<button data-remodal-action="close" class="remodal-close"></button>
			<div class="engage-modal-body engage-front-modal-body" id="<?php echo $modal_id; ?>_modal_body">
				<?php echo $form_html ?>
			</div>
		</div>

		<?php
		Engage_Forms_Render_Assets::enqueue_modals();

		return ob_get_clean();
	}

	/**
	 * Add modal to footer
	 *
	 * @since 1.5.0.7
	 *
	 * @param $html
	 */
	public static function add_to_footer( $html ){
		self::$footer_modals .= $html;
	}

	/**
	 * Print modal content in footer.
	 *
	 * @since 1.5.0.7
	 * since unknown in Engage_Forms
	 *
	 * @uses "wp_footer"
	 */
	public static function render_footer_modals() {
		$footer_modals = self::$footer_modals;
		if ( ! empty( $footer_modals ) && is_string( $footer_modals ) ) {
			echo $footer_modals;
		}

	}


	/**
	 * Create a modal button's HTML
	 *
	 * @since 1.5.0.7
	 * since 1.5.0.4 in Engage_Forms
	 *
	 * @param array $atts Form atts Form atts for Engage_Forms::render_form()
	 * @param string $content Content for opener
	 * @param array $form Form config
	 * @param string|null $modal_id Optional, modal ID. self::modal_id() will be used if empty.
	 *
	 * @return string
	 */
	public static function modal_button( $atts, $content, $form, $modal_id = null ){
		if( ! $modal_id ){
			$modal_id = self::modal_id( $form );
		}
		if ( empty( $content ) ) {
			$content = $form[ 'name' ];
		}

		$tag_atts = sprintf( 'data-form="%1s"', $form[ 'ID' ] );

		if ( ! empty( $atts[ 'width' ] ) ) {
			$tag_atts .= sprintf( ' data-width="%1s"', $atts[ 'width' ] );
		}
		if ( ! empty( $atts[ 'height' ] ) ) {
			$tag_atts .= sprintf( ' data-height="%1s"', $atts[ 'height' ] );
		}


		$title = sprintf( __( 'Click to open the form %s in a modal', 'engage-forms' ), $form[ 'name' ] );
		if ( ! empty( $atts[ 'type' ] ) && $atts[ 'type' ] == 'button' ) {
			$tag_atts .= sprintf( 'data-remodal-target="%1s"', $modal_id );
			return sprintf( '<button class="engage-forms-modal" %s title="%s">%s</button>', $tag_atts, $title, $content );
		} else {
			return sprintf( '<a href="%s" class="engage-forms-modal" %s title="%s">%s</a>', '#' . $modal_id, $tag_atts, esc_attr( $title ), $content );
		}
	}

	/**
	 * Create a modal ID
	 *
	 * @since 1.5.0.7
	 *
	 * @param $form
	 *
	 * @return string
	 */
	public static function modal_id( $form ){
		$modal_id = 'ef-modal-' . uniqid( $form[ 'ID' ] );

		return $modal_id;
	}

}