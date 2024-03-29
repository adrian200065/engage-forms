<div class="engage-config-group">
	<div class="engage-config-field">
		<label for="{{_id}}_inline">
            <input id="{{_id}}_inline" type="checkbox" class="field-config" name="{{_name}}[inline]" value="1" {{#if inline}}checked="checked"{{/if}}>
            <?php esc_html_e( 'Inline', 'engage-forms' ); ?>
        </label>
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