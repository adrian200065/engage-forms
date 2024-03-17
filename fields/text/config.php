<div class="engage-config-group">
	<label for="{{_id}}_placeholder">
		<?php esc_html_e('Placeholder', 'engage-forms'); ?></label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_placeholder" class="block-input field-config" name="{{_name}}[placeholder]" value="{{placeholder}}">
	</div>
</div>
<div class="engage-config-group">
	<label for="{{_id}}_default">
		<?php esc_html_e('Default', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_default" class="block-input field-config magic-tag-enabled" name="{{_name}}[default]" value="{{default}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}-type_override">
		<?php esc_html_e( 'HTML5 Type', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<select class="field-config {{_id}}_type_override" name="{{_name}}[type_override]" id="{{_id}}-type_override" aria-describedby="{{_id}}-type_override-description">
			<option {{#is type_override value="text"}}selected="selected"{{/is}}value="text">text</option>
			<option {{#is type_override value="date"}}selected="selected"{{/is}}value="date">date</option>
			<option {{#is type_override value="month"}}selected="selected"{{/is}}value="month">month</option>
			<option {{#is type_override value="number"}}selected="selected"{{/is}}value="number">number</option>
			<option {{#is type_override value="search"}}selected="selected"{{/is}}value="search">search</option>
			<option {{#is type_override value="tel"}}selected="selected"{{/is}}value="tel">tel</option>
			<option {{#is type_override value="time"}}selected="selected"{{/is}}value="time">time</option>
			<option {{#is type_override value="url"}}selected="selected"{{/is}}value="url">url</option>
			<option {{#is type_override value="week"}}selected="selected"{{/is}}value="week">week</option>
		</select>
		<p class="description" id="{{_id}}-type_override-description">
			<?php esc_html_e('Change the field type.','engage-forms');?>
		</p>
	</div>
</div>

<div class="engage-config-group">
	<label><?php esc_html_e('Masked Input', 'engage-forms'); ?></label>
	<div class="engage-config-field">
		<label><input type="checkbox" class="field-config {{_id}}_masked" name="{{_name}}[masked]" value="1" {{#if masked}}checked="checked"{{/if}}> <?php _e('Enable input mask', 'engage-forms'); ?></label>
	</div>
</div>
<div id="{{_id}}_maskwrap">
	<div class="engage-config-group">
		<label><?php esc_html_e('Mask', 'engage-forms'); ?></label>
		<div class="engage-config-field">		
			<input type="text" id="{{_id}}_mask" class="block-input field-config" name="{{_name}}[mask]" value="{{mask}}">
		</div>
	</div>
	<div class="engage-config-group">
		<p class="description"><?php esc_html_e('e.g.', 'engage-forms' ); ?>: aaa-99-999-a9-9*</p>
		<ul>
			<li>9 : <?php esc_html_e('numeric', 'engage-forms'); ?></li>
			<li>a : <?php esc_html_e('alphabetical', 'engage-forms'); ?></li>
			<li>* : <?php esc_html_e('alphanumeric', 'engage-forms'); ?></li>
			<li>[9 | a | *] : <?php esc_html_e('optional', 'engage-forms'); ?></li>
			<li>{int | * | +} : <?php esc_html_e('length', 'engage-forms'); ?></li>
		</ul>
		<p class="description"><?php esc_html_e('Any length character only', 'engage-forms'); ?>: [a{*}]</p>
		<p class="description"><?php esc_html_e('Any length number only', 'engage-forms'); ?>: [9{*}]</p>
		<p class="description"><?php esc_html_e('email', 'engage-forms'); ?>: *{+}@*{2,}.*{2,}[.[a{2,}][.[a{2,}]]]</p>

	</div>
</div>

<?php
