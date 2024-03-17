<div class="engage-config-group">
	<label for="ef-autoresponder-send-name-{{_id}}">
		<?php esc_html_e('From Name', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input
            id="ef-autoresponder-send-name-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled required" name="{{_name}}[sender_name]" value="{{sender_name}}"
        />
	</div>
</div>
<div class="engage-config-group">
	<label for="ef-autoresponder-from-email-{{_id}}">
        <?php esc_html_e('From Email', 'engage-forms'); ?>
    </label>
	<div class="engage-config-field">
		<input
            id="ef-autoresponder-from-email-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind required" name="{{_name}}[sender_email]" value="{{sender_email}}"
        />
	</div>
</div>

<div class="engage-config-group">
    <label for="ef-autoresponder-reply-to-{{_id}}">
        <?php esc_html_e( 'Reply To', 'engage-forms'); ?>
    </label>
    <div class="engage-config-field">
        <input
            id="ef-autoresponder-reply-to-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind" name="{{_name}}[reply_to]" value="{{reply_to}}"
        />
    </div>
</div>

<div class="engage-config-group">
    <label for="ef-autoresponder-cc-{{_id}}">
        <?php esc_html_e( 'CC', 'engage-forms'); ?>
    </label>
    <div class="engage-config-field">
        <input
            id="ef-autoresponder-cc-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind" name="{{_name}}[cc]" value="{{cc}}"
        />
    </div>
</div>

<div class="engage-config-group">
    <label for="ef-autoresponder-bcc-{{_id}}">
        <?php esc_html_e( 'BCC', 'engage-forms'); ?>
    </label>
    <div class="engage-config-field">
        <input
            id="ef-autoresponder-cc-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind" name="{{_name}}[bcc]" value="{{bcc}}"
        />
    </div>
</div>



<div class="engage-config-group">
	<label for="ef-autoresponder-subject-{{_id}}">
		<?php esc_html_e('Email Subject', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input
            id="ef-autoresponder-subject-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind required" name="{{_name}}[subject]" value="{{subject}}"
        />
	</div>
</div>
<div class="engage-config-group">
	<label for="ef-autoresponder-to-name-{{_id}}">
		<?php esc_html_e('Recipient Name', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input
            id="ef-autoresponder-to-name-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind required" name="{{_name}}[recipient_name]" value="{{recipient_name}}"
        />
	</div>
</div>
<div class="engage-config-group">
	<label for="ef-autoresponder-to-email-{{_id}}">
		<?php esc_html_e('Recipient Email', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<input
            id="ef-autoresponder-to-email-{{_id}}"
            type="text" class="block-input field-config magic-tag-enabled engage-field-bind required" name="{{_name}}[recipient_email]" value="{{recipient_email}}"
        />
	</div>
</div>
<div class="engage-config-group">
	<label for="ef-autoresponder-message-{{_id}}">
		<?php esc_html_e('Message', 'engage-forms'); ?>
	</label>
	<div class="engage-config-field">
		<textarea
            id="ef-autoresponder-message-{{_id}}"
            rows="6" class="block-input field-config required magic-tag-enabled" name="{{_name}}[message]">{{#if message}}{{message}}{{else}}Hi %recipient_name%.
Thanks for your email.
We'll get back to you as soon as possible!
{{/if}}</textarea>
	</div>
</div>
