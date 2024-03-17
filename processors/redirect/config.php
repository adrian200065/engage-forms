<div class="engage-config-group">
	<label><?php echo __('URL', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled required" name="{{_name}}[url]" value="{{url}}">
	</div>
</div>
<div class="engage-config-group">
	<label><?php echo __('Redirect Message', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled required" name="{{_name}}[message]" value="{{#if message}}{{message}}{{else}}<?php _e('Redirecting', 'engage-forms'); ?>{{/if}}">
		<p class="description"><?php _e('Message text shown when redirecting in Ajax mode.', 'engage-forms'); ?></p>
	</div>
</div>