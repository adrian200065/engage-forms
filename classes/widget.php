<?php


class Engage_Forms_Widget extends WP_Widget {

	/**
	 * Create widget
	 *
	 * @since unknown
	 */
	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, __('Engage Form', 'engage-forms' ) );

		/**
		 * Runs after Engage Forms widget is initialized
		 *
		 * @since 1.4.0
		 */
		do_action( 'engage_forms_widget_init' );
	}

	/**
	 * Widget output
	 *
	 * @since unknown
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		if(!empty($instance['form'])){

			extract($args, EXTR_SKIP);



			echo $before_widget;
			$title = empty($instance['title']) ? ' ' : apply_filters( 'widget_title', $instance['title']);
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

				echo Engage_Forms::render_form( $instance[ 'form' ] );
			
			echo $after_widget;

				
		}
	}

	/**
	 * Update widget settings
	 *
	 * @since unknown
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		// Save widget options
		return $new_instance;
	}

	/**
	 * Widget UI form
	 *
	 * @since unknown
	 *
	 * @param array $instance
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
		
		do_action( 'engage_forms_widget_form_start', $instance );

		echo "<p><label for=\" " . $this->get_field_id('title') . "\">" . __('Title', 'engage-forms') . ": <input class=\"widefat\" id=\"" . $this->get_field_id('title') . "\" name=\"" . $this->get_field_name('title') . "\" type=\"text\" value=\"" . esc_attr($title). "\" /></label></p>\r\n";
		// get forms
		$forms = Engage_Forms_Forms::get_forms( true );

		echo "<p><label for=\" " . $this->get_field_id('title') . "\">" . __('Form', 'engage-forms') . ": </label><select style=\"width:100%;\" name=\"" . $this->get_field_name('form') . "\">\r\n";
		echo "<option value=\"\"></option>\r\n";
		if(!empty($forms)){

			foreach($forms as $formid=>$form){
				$sel = "";
				if(!empty($instance['form'])){
					if($instance['form'] == $formid){
						$sel = ' selected="selected"';
					}
				}
				echo "<option value=\"" . $formid . "\"".$sel.">" . $form['name'] ."</option>\r\n";
			}
		}

		echo "</select></p>\r\n";
		do_action( 'engage_forms_widget_form_end', $instance, $this );
	}
}

function engage_forms_register_widget() {
	if( ! did_action( 'engage_forms_widget_init' ) ){
		register_widget( 'Engage_Forms_Widget' );
	}

}

add_action( 'widgets_init', 'engage_forms_register_widget' );
