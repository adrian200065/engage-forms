<?php
/**
 * Block: Engage Forms
 */
/**
 * This code is super-copypasta of Adrian Simpson Gutenberg Boilerplate: https://github.com/ahmadawais/Gutenberg-Boilerplate/tree/master/block/02-basic-esnext
 *
 * Hi Ahmad
 * https://AhmdA.ws/GutenbergBoilerplate
 */



/** Hooks for Gutenberg */
add_action( 'enqueue_block_editor_assets', 'engage_forms_enqueue_block_assets');
add_action( 'init', 'engage_forms_register_block');

/**
 * Enqueue the block's assets for the editor.
 *
 * @uses "enqueue_block_editor_assets" action
 * @since 1.5.8
 */
function engage_forms_enqueue_block_assets() {
	Engage_Forms_Render_Assets::enqueue_script( 'blocks', array( 'wp-blocks', 'wp-i18n', 'wp-element' ) );

    engage_forms_print_ef_forms_var('blocks');


}

/**
 * Print to DOM the EF_FORM variable
 *
 * @since 1.7.0
 *
 * @param string $script_handle Handle of script to use with wp_localize_script()
 */
function engage_forms_print_ef_forms_var($script_handle){
	$form_options = array();
	$forms = Engage_Forms_Forms::get_forms(true);
	$forms = array_reverse($forms);
	foreach ($forms as $form) {
		if( !empty( $form['form_draft'] ) ) {
			continue;
		}
		$form_options[] = array(
			'name' => esc_html($form['name']),
			'formId' => esc_attr($form['ID']),
			'ID' => esc_attr($form['ID'])
		);

	}

	wp_localize_script(
		Engage_Forms_Render_Assets::make_slug($script_handle),
		'EF_FORMS',
		array(
			'forms' => $form_options
		)
	);
}



/**
 * Render a Engage Forms block
 *
 * @since 1.5.8
 *
 * @param array $atts
 * @return string|void
 */
function engage_forms_render_eform_block($atts ) {
    if( ! empty( $atts[ 'formId' ] ) ){
		if ( ! empty( $atts[ 'className' ] ) ){
			add_filter( 'engage_forms_render_form_wrapper_classes', function( $wrapper_classes ) use ( $atts ){
				$wrapper_classes[] = $atts[ 'className' ];
				return $wrapper_classes;
			} );
		}
        return Engage_Forms::render_form(
            array(
                'ID' => engage_forms_very_safe_string( $atts[ 'formId' ] )
            )
        );
    }


}

/**
 * Register blocks
 *
 * @uses "init"
 *
 * @since 1.5.8
 */
function engage_forms_register_block(){
    if( ! function_exists( 'register_block_type' ) ){
        return;
    }
	$script = Engage_Forms_Render_Assets::make_slug('blocks');
	wp_register_script($script, Engage_Forms_Render_Assets::make_url('blocks'), [
		'wp-components',
		'wp-blocks',
		'wp-editor',
	], EFCORE_VER);
	register_block_type('engageforms/eform', [
		'render_callback' => 'engage_forms_render_eform_block',
		'attributes' => [
			'formId' => [
				'type' => 'string',
				'default' => '',
			],
		],
	]);
}




