<div class="engage-config-group">
	<label><?php echo __('Name', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled required" name="{{_name}}[sender_name]" value="{{sender_name}}">
	</div>
</div>
<div class="engage-config-group">
	<label><?php echo __('Email', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled engage-field-bind required" id="{{_id}}_sender_email" name="{{_name}}[sender_email]" value="{{sender_email}}">
	</div>
</div>
<div class="engage-config-group">
	<label><?php echo __('URL', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled engage-field-bind" id="{{_id}}_url" name="{{_name}}[url]" value="{{url}}">
	</div>
</div>
<div class="engage-config-group">
	<label><?php echo __('Content', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled engage-field-bind" id="{{_id}}_content" name="{{_name}}[content]" value="{{content}}">
	</div>
</div>

<div class="engage-config-group">
	<label><?php echo __('Error Message', 'engage-forms'); ?> </label>
	<div class="engage-config-field">
		<input type="text" class="block-input field-config magic-tag-enabled engage-field-bind" id="{{_id}}_error" name="{{_name}}[error]" value="{{#if error}}{{error}}{{else}}<?php echo __('Sorry, that looked very spammy, try rephrasing things', 'engage-forms'); ?>{{/if}}">
	</div>
</div>
