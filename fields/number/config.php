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
        <?php esc_html_e('Default', 'engage-forms'); ?>
    </label>
	<div class="engage-config-field">
		<input type="text" id="{{_id}}_default" class="block-input field-config magic-tag-enabled" name="{{_name}}[default]" value="{{default}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_min">
		<?php esc_html_e('Minimum', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="number" id="{{_id}}_min" class="block-input field-config" name="{{_name}}[min]" value="{{min}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_max">
		<?php esc_html_e('Maximum', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="number" id="{{_id}}_max" class="block-input field-config" name="{{_name}}[max]" value="{{max}}">
	</div>
</div>

<div class="engage-config-group">
	<label for="{{_id}}_step">
		<?php esc_html_e('Step (increment size)', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input type="number" id="{{_id}}_step" class="block-input field-config" name="{{_name}}[step]" value="{{step}}">
	</div>
</div>