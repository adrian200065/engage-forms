<div class="engage-config-group">
	<label for="{{_id}}_placeholder">
		<?php esc_html_e('Placeholder', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_placeholder" class="block-input field-config" name="{{_name}}[placeholder]" value="{{placeholder}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_default">
		<?php esc_html_e('Default', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_default" class="block-input field-config magic-tag-enabled" name="{{_name}}[default]" value="{{default}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_default">
		<?php esc_html_e('Credit Card Field', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		{{{_field slug="credit_card_field" type="credit_card_number"  }}}
	</div>
	<p class="description">
		<?php esc_html_e( 'If set, the type of card will be used for verification.', 'engage-forms' ); ?>
	</p>
</div>


