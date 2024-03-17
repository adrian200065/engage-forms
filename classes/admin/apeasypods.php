<?php

/**
 * Add auto-populate option for Easy Pods to select fields
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
class Engage_Forms_Admin_APEasyPods extends Engage_Forms_Admin_APSetup {

	/**
	 * @inheritdoc
	 */
	public function add_type() {
		if( class_exists( 'Engage_Easy_Pods' ) ){
			printf( '<option value="easy-pod"{{#is auto_type value="easy-pod"}} selected="selected"{{/is}}>%s</option>', esc_html__( 'Easy Pod', 'engage-forms' ) );
		}
	}

	/**
	 * @inheritdoc
	 */
	public function add_options() {
		if( ! function_exists( 'cep_get_registry'  ) ){
			return;
		}
		$easy_pods = cep_get_registry();
		?>
		<div class="engage-config-group engage-config-group-auto-easy-pod auto-populate-type-panel" style="display:none;">
			<label for="ef-auto-populate-easy-pods">
				<?php esc_html_e( 'Easy Pod', 'engage-forms' ); ?>
			</label>
			<div class="engage-config-field">
				<select id="ef-auto-populate-easy-pods" class="block-input field-config" name="{{_name}}[easy-pod]">";
					<?php
					foreach($easy_pods as $easy_pod ){
						printf(  '<option value="%s" {{#is easy-pod value="%s"}}selected="selected"{{/is}}>%s</option>',
							esc_attr( $easy_pod[ 'id' ] ), esc_attr( $easy_pod[ 'id' ] ), esc_html__( $easy_pod[ 'name' ] ));
					}

					?></select>

			</div>
		</div>
		<?php
	}

}