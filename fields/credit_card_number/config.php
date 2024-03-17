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
	<label for="{{_id}}_exp">
		<?php esc_html_e('Expiration Field', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		{{{_field slug="exp" type="credit_card_exp"  }}}
	</div>
	<p class="description">
		<?php esc_html_e( 'Link an expiration field for validation.', 'engage-forms' ); ?>
	</p>
</div>


<div class="engage-config-group">
	<label for="{{_id}}_cvc">
		<?php esc_html_e('Secret Code Field', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		{{{_field slug="exp" type="credit_card_cvc"  }}}
	</div>
	<p class="description">
		<?php esc_html_e( 'Link a secret code field for validation.', 'engage-forms' ); ?>
	</p>
</div>