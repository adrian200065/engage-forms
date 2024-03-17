<div class="engage-backdrop engage-forms-insert-modal" style="display: none;"></div>
<form id="engagef_forms_shortcode_modal" class="engage-modal-wrap engage-forms-insert-modal" style="display: none; width: 700px; max-height: 500px; margin-left: -350px;">
	<div class="engage-modal-title" id="engagef_forms_shortcode_modalTitle" style="display: block;">
		<a href="#close" class="engage-modal-closer" data-dismiss="modal" aria-hidden="true" id="engagef_forms_shortcode_modalCloser">×</a>
		<h3 class="modal-label" id="engagef_forms_shortcode_modalLable"><?php echo __('Insert Engage Form', 'engage-forms'); ?></h3>
	</div>
	<div class="engage-modal-body none" id="engagef_forms_shortcode_modalBody" style="width: 70%;">
		<div class="modal-body modal-forms-list">
		<?php

			$forms = Engage_Forms_Forms::get_forms( true );
			if(!empty($forms)){
				foreach($forms as $form_id=>$form){

					echo '<div class="modal-list-item"><label><input name="insert_form_id" autocomplete="off" class="selected-form-shortcode" value="' . $form_id . '" type="radio">' . $form['name'];
					if(!empty($form['description'])){
						echo '<p style="margin-left: 20px;" class="description"> '.$form['description'] .' </p>';
					}
					echo ' </label></div>';

				}
			}else{
				echo '<p>' . __('You don\'t have any forms to insert.', 'engage-forms') .'</p>';
			}

		?>
		</div>
	</div>
	<div class="engage-modal-body none" id="engagef_forms_shortcode_modalBody_options" style="left: 70%;">
		<div class="modal-body modal-shortcode-options">
			<h4><?php esc_html_e('Options', 'engage-forms'); ?></h4>
			<label><input type="checkbox" value="1" class="set_ef_option set_ef_modal"> <?php esc_html_e('Set as Modal', 'engage-forms'); ?></label>
			<div class="modal-forms-setup" style="display:none;">
				<label><?php esc_html_e('Open Modal Trigger Type', 'engage-forms'); ?></label>
				<select name="modal_button_type" class="modal_trigger_type" style="width: 100%;">
					<option value="link"><?php esc_html_e('Link', 'engage-forms'); ?></option>
					<option value="button"><?php esc_html_e('Button', 'engage-forms'); ?></option>					
				</select>
				<label><?php esc_html_e('Open Modal Text', 'engage-forms'); ?></label>
				<input type="text" name="modal_button_text" class="modal_trigger" style="width: 100%;">
				<label><?php esc_html_e('Modal Width', 'engage-forms'); ?></label>
				<input type="number" name="modal_width" class="modal_width" style="width: 60px;">px

			</div>

		</div>

	</div>
	<div class="engage-modal-footer" id="engagef_forms_shortcode_modalFooter" style="display: block;">
	<?php if(!empty($forms)){ ?>		
		<button class="button engage-form-shortcode-insert" style="float:right;"><?php esc_html_e('Insert Form', 'engage-forms'); ?></button>
	<?php }else{ ?>
		<button class="button engage-modal-closer"><?php echo __('Close', 'engage-forms'); ?></button>
	<?php } ?>
	</div>
</form>
