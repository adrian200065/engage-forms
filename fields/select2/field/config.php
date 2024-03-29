<div class="engage-config-group">
	<label for="{{_id}}_placeholder">
		<?php esc_html_e( 'Placeholder', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_placeholder" class="block-input field-config" name="{{_name}}[placeholder]" value="{{placeholder}}">
	</div>
</div>
<div class="engage-config-group">
	<label for="{{_id}}_is_multi">
		<?php esc_html_e( 'Multiple', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<label>
			<input type="checkbox" id="{{_id}}_is_multi" class="field-config" name="{{_name}}[multi]" value="1" {{#if multi}}checked="checked"{{/if}}>
            <?php esc_html_e( 'Enable multiple selections', 'engage-forms'); ?>
        </label>
	</div>
</div>
<div class="engage-config-group">
	<label for="{{_id}}_color">
		<?php esc_html_e('Highlight', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input id="{{_id}}_color" type="text" class="color-field field-config" name="{{_name}}[color]" value="{{#if color}}{{color}}{{else}}#5b9dd9{{/if}}">		
	</div>
</div>
<div class="engage-config-group">
	<label for="{{_id}}_border">
		<?php esc_html_e( 'Border', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		<input id="{{_id}}_border" type="text" class="color-field field-config" name="{{_name}}[border]" value="{{#if border}}{{border}}{{else}}#4b8dc9{{/if}}">		
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_default_option">
		<?php esc_html_e( 'Default Option', 'engage-forms' ); ?>
	</label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_default_option" class="block-input field-config magic-tag-enabled" name="{{_name}}[default_option]" value="{{default_option}}" aria-describedby="{{_id}}_default_option-description"  />
		<p class="description" id="{{_id}}_default_option-description">
			<?php esc_html_e( 'Overwrite default option - useful for setting default with magic tags.', 'engage-forms' ); ?>
		</p>
	</div>
</div>