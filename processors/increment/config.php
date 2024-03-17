<div class="engage-config-group">
	<label for="{{_id}}_increment-start">
        <?php esc_html_e( 'Increment Start', 'engage-forms' ); ?>
    </label>
	<div class="engage-config-field">
		{{#unless start}}
		<input id="{{_id}}_increment-start" class="block-input field-config required" type="number" name="{{_name}}[start]" value="{{start}}" aria-describedby="{{_id}}_increment-start-description">
		<p id="{{_id}}_increment-start-description" class="description">
            <?php esc_html_e('Number to start increment at.', 'engage-forms'); ?>
        </p>
		{{else}}
		<p class="notice">
            <?php esc_html_e('Increment started at {{start}}. To reset, delete this and insert a new incremental value processor.', 'engage-forms'); ?>
        </p>
		<input type="hidden" name="{{_name}}[start]" value="{{start}}">
		{{/unless}}
	</div>
</div>
<div class="engage-config-group">
	<label for="{{_id}}_increment-field">
        <?php echo __( 'Increment Field', 'engage-forms' ); ?>
    </label>
	<div class="engage-config-field">
		{{{_field slug="field" type="hidden" exclude="system,variables" }}}
		<p class="description">
			<?php esc_html_e( 'If you want to show the incremented value in the entries, select a hidden field in form to capture the value in.', 'engage-forms' ); ?>
		</p>
	</div>
</div>