<?php




add_action('engage_forms_field_settings_template', 'ef_custom_field_classes');
add_filter('engage_forms_render_field_classes', 'ef_apply_field_classes', 10, 3);


function ef_apply_field_classes($classes, $field, $form){
	
	if(!empty($field['config']['custom_class'])){
		$classes['control_wrapper'][] = $field['config']['custom_class'];
	}
	return $classes;
}

function ef_custom_field_classes(){
?>
<div class="engage-config-group customclass-field">
	<label><?php echo __('Custom Class', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config" name="{{_name}}[custom_class]" value="{{custom_class}}">
	</div>
</div>
<?php
}


add_filter('engage_forms_get_field_types', 'ef_live_gravatar_field');

function ef_live_gravatar_field($fieldtypes){

	$fieldtypes['live_gravatar'] = array(
		"field"			=>	"Gravatar",
		"file"			=>	EFCORE_PATH . "fields/gravatar/field.php",
		"category"		=>	__( 'Special' , 'engage-forms' ),
		"description" 	=> __( 'A live gravatar preview', 'engage-forms' ),
		'icon'          => EFCORE_URL . 'assets/build/images/user.svg',
		"setup"			=>	array(
			"template"	=>	EFCORE_PATH . "fields/gravatar/config.php",
			"preview"	=>	EFCORE_PATH . "fields/gravatar/preview.php",
			"not_supported"	=>	array(
				'entry_list',
				'custom_class'
			)
		)
	);
	return $fieldtypes;
}


add_action( 'wp_ajax_ef_live_gravatar_get_gravatar', 		'ef_live_gravatar_get_gravatar' );
add_action( 'wp_ajax_nopriv_ef_live_gravatar_get_gravatar', 'ef_live_gravatar_get_gravatar' );


function ef_live_gravatar_get_gravatar(){
	$defaults = array(
		'email'	=> '',
		'generator' => 'mystery',
		'size' => 100
	);
	$defaults = array_merge( $defaults, $_POST );
	echo get_avatar( Engage_Forms::do_magic_tags( $defaults['email'] ), (int) $defaults['size'], $defaults['generator']);
	exit;
}

// field specific stuff.
add_filter( 'engage_forms_render_field_classes_type-file', 'engage_forms_file_field_class' );
function engage_forms_file_field_class($classes){
	$classes['field_wrapper'][] = "file-prevent-overflow";
	return $classes;
}
add_filter( 'engage_forms_render_field_classes_type-toggle_switch', 'engage_forms_toggle_switch_field_class' );
function engage_forms_toggle_switch_field_class($classes){
	$classes['control_wrapper'][] = "ef-toggle-switch";
	return $classes;
}
add_filter( 'engage_forms_render_field_classes_type-color_picker', 'engage_forms_color_picker_field_class' );
function engage_forms_color_picker_field_class($classes){
	$classes['field_wrapper'][] = "input-group";
	$classes['control_wrapper'][] = "minicolor-picker";
	return $classes;
}
