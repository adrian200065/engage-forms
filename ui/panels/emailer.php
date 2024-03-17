<?php

if(!isset($element['mailer']['sender_name'])){
	$element['mailer']['sender_name'] = __('Engage Forms Notification', 'engage-forms');
}
if(!isset($element['mailer']['sender_email'])){
	$element['mailer']['sender_email'] = Engage_Forms_Email_Fallback::get_fallback( $element );
}
if(!isset($element['mailer']['email_type'])){
	$element['mailer']['email_type'] = 'html';
}
if(!isset($element['mailer']['recipients'])){
	$element['mailer']['recipients'] = '';
}
if(!isset($element['mailer']['email_subject'])){
	$element['mailer']['email_subject'] = $element['name'];
}
if(!isset($element['mailer']['email_message'])){
	$element['mailer']['email_message'] = '{summary}';
}

// backwords-compat
if ( ! empty( $element['mailer']['enable_mailer'] ) ) {
	$element['mailer']['on_insert'] = 1;
}


?>
<div class="mailer-control-panel wrapper-instance-pane">

	<div class="engage-config-group">
		<label class="screen-reader-text"><?php esc_html_e('Use The Mailer', 'engage-forms'); ?> </label>
		<div class="engage-config-field">
			<div style="width:100%;text-align:center;" class="toggle_processor_event">

				<label style="width: 100%;" title="<?php echo esc_attr( __( 'Enable Or Disable Mailer', 'engage-forms') ); ?>" class="button button-small <?php if( !empty( $element['mailer']['on_insert'] ) ){ echo 'activated'; } ?>"><input type="checkbox" style="display:none;" value="1" name="config[mailer][on_insert]" <?php if( !empty( $element['mailer']['on_insert'] ) ){ echo 'checked="checked"'; } ?>>
				<span class="is_active" style="width: 100%;<?php if( empty( $element['mailer']['on_insert'] ) ){ ?> display:none;visibility: hidden;<?php } ?>"><?php esc_html_e( 'Disable Mailer', 'engage-forms' ); ?></span>
				<span class="not_active" style="width: 100%;<?php if( !empty( $element['mailer']['on_insert'] ) ){ ?> display:none;visibility: hidden;<?php } ?>"><?php esc_html_e( 'Enable Mailer', 'engage-forms' ); ?></span>
				</label>
			</div>
		</div>
	</div>

	<div class="mailer_config_panel engage-config-processor-notice" style="display:<?php if( empty( $element['mailer']['on_insert'] ) && empty( $element['mailer']['on_insert'] ) ){ ?> block;<?php }else{ ?>none;<?php }?>clear: both; padding: 20px 0px 0px;width:550px;">
		<p style="padding:12px; text-align:center;background:#e7e7e7;" class="description"><?php _e('Mailer is currently disabled', 'engage-forms'); ?></p>
	</div>

	<div class="mailer_config_panel engage-config-processor-setup" <?php if( empty( $element['mailer']['on_insert'] ) && empty( $element['mailer']['on_insert'] ) ){ echo 'style="display:none;"'; } ?>>
		<div class="engage-config-group">
			<label for="ef-email-from-name">
				<?php  esc_html_e( 'From Name', 'engage-forms' ); ?> 
			</label>
			<div class="engage-config-field">
				<input type="text" class="field-config magic-tag-enabled" name="config[mailer][sender_name]" value="<?php echo $element['mailer']['sender_name']; ?>" style="width:400px;" id="ef-email-from-name" aria-describedby="ef-email-from-name-description" >
				<p class="description" id="ef-email-from-name-description">
					<?php esc_html_e( 'Name for email sender', 'engage-forms'); ?>
				</p>
			</div>
		</div>


        <div class="engage-config-group">
            <label for="ef-email-from-email" class="no-pro-enhanced">
                <?php esc_html_e('From Email', 'engage-forms'); ?>
            </label>
            <label for="ef-email-from-email" class="pro-enhanced">
                <?php esc_html_e('Reply To Email', 'engage-forms'); ?>
            </label>

            <div class="engage-config-field">
                <input type="email" class="field-config" name="config[mailer][sender_email]" value="<?php echo $element['mailer']['sender_email']; ?>" style="width:400px;" id="ef-email-from-email" aria-describedby="ef-email-from-email-description">
                <p class="description no-pro-enhanced" id="ef-email-from-email-description">
                    <?php esc_html_e( 'Email Address for sender. If you want to use a form field use the "Reply To Email" setting below.', 'engage-forms'); ?>
                    <strong><?php esc_html_e( 'Do Not Use A Magic Tag', 'engage-forms' ); ?>.</strong>
                </p>
                <p class="description pro-enhanced" id="ef-email-from-email-description">
                    <?php esc_html_e('The email address of the person filling in the form. This will allow replies to the email to go to the sender.', 'engage-forms'); ?>
                </p>
            </div>
        </div>

        <div class="engage-config-group no-pro-enhanced">
            <label for="ef-email-from-replyto">
                <?php esc_html_e('Reply To Email', 'engage-forms'); ?>
            </label>
            <div class="engage-config-field">
                <input type="text" class="field-config magic-tag-enabled" name="config[mailer][reply_to]" value="<?php if(isset( $element['mailer']['reply_to'] ) ){ echo $element['mailer']['reply_to']; } ?>" style="width:400px;" id="ef-email-from-replyto" aria-describedby="ef-email-from-replyto-description">
                <p class="description" id="ef-email-from-replyto-description">
                    <?php esc_html_e('The email address of the person filling in the form. This will allow replies to the email to go to the sender.', 'engage-forms'); ?>
                </p>
            </div>
        </div>

        <div class="engage-config-group">
			<label for="ef-email-type">
				<?php esc_html_e('Email Type', 'engage-forms'); ?>
			</label>
			<div class="engage-config-field" id="ef-email-type">
				<select class="field-config" name="config[mailer][email_type]">
				<option value="html" <?php if($element['mailer']['email_type'] == 'html'){ echo 'selected="selected"'; } ?>>HTML</option>
				<option value="text" <?php if($element['mailer']['email_type'] == 'text'){ echo 'selected="selected"'; } ?>>Text</option>
				</select>
			</div>
		</div>
		<div class="engage-config-group">
			<label>
				<?php esc_html_e('CSV Include', 'engage-forms'); ?>
			</label>
			<div class="engage-config-field">
				<label>
					<input type="checkbox" class="field-config" name="config[mailer][csv_data]" value="1" <?php if(isset($element['mailer']['csv_data'])){ echo 'checked="checked";'; } ?>>
					<?php esc_html_e('Attach a CSV version of the submission', 'engage-forms'); ?>
				</label>
			</div>
		</div>


		<div class="engage-config-group">
			<label for="ef-email-recipients">
				<?php esc_html_e('Email Recipients', 'engage-forms'); ?> </label>
			<div class="engage-config-field">
				<input type="text" class="field-config magic-tag-enabled" name="config[mailer][recipients]" value="<?php echo $element['mailer']['recipients']; ?>" style="width:400px;" id="ef-email-recipients" aria-describedby="ef-email-recipients-description" />
				<p class="description" id="ef-email-recipients-description">
					<?php esc_html_e( 'Who to send email to? Use a comma separated list of email addresses to send to more than one address.', 'engage-forms'); ?>
				</p>
			</div>

		</div>
		<div class="engage-config-group">
			<label for="ef-email-bcc">
				<?php esc_html_e('BCC', 'engage-forms'); ?>
			</label>
			<div class="engage-config-field">
				<input type="text" class="field-config magic-tag-enabled" name="config[mailer][bcc_to]" value="<?php if(isset( $element['mailer']['bcc_to'] ) ){ echo $element['mailer']['bcc_to']; } ?>" style="width:400px;" id="ef-email-bcc" aria-describedby="ef-email-bcc-description" />
				<p class="description" id="ef-email-bcc-description">
					<?php esc_html_e('Comma separated list of email addresses to send a BCC to.', 'engage-forms'); ?>
				</p>
			</div>
		</div>

		<div class="engage-config-group">
			<label for="ef-email-subject">
				<?php esc_html_e('Email Subject', 'engage-forms'); ?>
			</label>
			<div class="engage-config-field">
				<input type="text" class="field-config magic-tag-enabled" name="config[mailer][email_subject]" value="<?php echo $element['mailer']['email_subject']; ?>" style="width:400px;" id="ef-email-subject" aria-describedby="ef-email-subject-description">
				<p class="description" id="ef-email-subject-description">
					<?php esc_html_e('Use %field_slug% to use a value from the form', 'engage-forms'); ?>
				</p>
			</div>
		</div>
		<div class="engage-config-group">
			<label for="mailer_email_message">
				<?php esc_html_e('Email Message', 'engage-forms'); ?> </label>
			<div class="engage-config-field" style="max-width: 600px;">
				<?php wp_editor( $element['mailer']['email_message'], "mailer_email_message", array(
					'textarea_name' => 'config[mailer][email_message]') );
				?>
				<p class="description">
					<?php esc_html_e('Magic tags, %field_slug% are replaced with submitted data. Use {summary} to build an automatic mail based on form content. Leaving the mailer blank, will create an automatic summary.', 'engage-forms'); ?>
				</p>
			</div>
		</div>


		<?php
		/**
		 * Runs below the mail message field in email notifciation tab
		 *
		 * @since unknown
		 *
		 * @param array $element Form config
		 */
		do_action( 'engage_forms_mailer_config', $element );
		?>
		

		<div class="engage-config-group">
			<label for="preview_email" id="preview_email-label">
				<?php esc_html_e( 'Save Preview', 'engage-forms'); ?>
			</label>
			<div class="engage-config-field">
				<label>
					<input type="checkbox" id="preview_email" class="field-config ef-email-preview-toggle" value="1" name="config[mailer][preview_email]"  aria-describedby="preview_email-description" aria-labelledby="preview_email-label" <?php if(!empty($element['mailer']['preview_email'])){ echo 'checked="checked";'; } ?>>
					<span id="preview_email-description">
						<?php esc_html_e( 'Allows you to preview the message and who the message is sent to, as well as the subject. You should turn this off when not testing.', 'engage-forms'); ?>
					</span>
				</label>
			</div>
		</div>

		<div class="engage-config-group">
			<label>
				<?php esc_html_e('Debug Mailer', 'engage-forms'); ?>
			</label>
			<div class="engage-config-field">
				<label><input type="checkbox" value="1" name="config[debug_mailer]" class="field-config"<?php if(isset($element['debug_mailer'])){ echo ' checked="checked"'; } ?>> <?php esc_html_e('Enable email send transaction log', 'engage-forms'); ?></label>
				<p class="description"><?php esc_html_e('If set, entries will have a "Mailer Debug" meta tab to see the transaction log. Do not keep this enabled on production as it sends two emails for tracking.', 'engage-forms'); ?></p>
				<p class="description">
					<?php echo sprintf( esc_html( 'If you are having email issues, we strongly recommend %sSendWP%s.', 'engage-forms' ), '<a href="https://sendwp.com?utm_source=Engage+Forms+Plugin&utm_medium=Forms_Edit+Forms_Email&utm_campaign=SendWP+banner+ad" target="_blank" rel="nofollow">', '</a>' ); ?>
				</p>

				<a href="https://sendwp.com?utm_source=Engage+Forms+Plugin&utm_medium=Forms_Edit+Forms_Email&utm_campaign=SendWP+banner+ad" target="_blank" rel="nofollow" style="text-decoration:none;">
					<div class="mailer_config_panel engage-config-processor-notice" style="clear: both; padding: 20px 0px 0px;width:550px;">
						<p style="padding:12px;text-align:center;color:white;background:#21394a;" class="description">
							<?php echo sprintf( esc_html__('%sSendWP%s - Fix Your WordPress Email%sThe easy solution to transactional email in WordPress', 'engage-forms'), '<strong>', '</strong>', '<br />' ); ?>
						</p>
					</div>
				</a>

			</div>
		</div>

	</div>
</div>

<?php //Set Different From email and Reply-to text depending on Pro delivery status of the form
    if( engage_forms_pro_is_active() === true ) {

        $enhanced_delivery = \engagewp\engageforms\pro\container::get_instance()->get_settings()->get_enhanced_delivery();

        if( $enhanced_delivery === true ) {

            $send_local = \engagewp\engageforms\pro\container::get_instance()->get_settings()->get_form( $element['ID'] )->get_send_local();
            ?>
            <script type="text/javascript">
              var efId = "<?php echo $element['ID'] ?>";
              var $check = jQuery("<input id='ef-pro-send-local-" + efId + "' type='checkbox'/>" );
            </script>
            <?php
             if( $send_local === false ) { ?>
                <script type="text/javascript">
                    jQuery($check).prop('checked', false)
                </script>
            <?php } else if ( $send_local === true ) { ?>
                <script type="text/javascript">
                    jQuery($check).prop('checked', true);
                </script>
            <?php } ?>

            <script type="text/javascript">

                jQuery(function ($) {
                  var checkProStatus = function () {
                    if ( $check.prop("checked") === true) {
						$(".pro-enhanced").show().attr('aria-hidden', false);
						$(".no-pro-enhanced").hide().attr('aria-hidden', true);
                    } else {
						$(".pro-enhanced").hide().attr('aria-hidden', true);
						$(".no-pro-enhanced").show().attr('aria-hidden', false);
                    }
                  };

                  jQuery(function ($) {
                      $( 'body' ).on( 'change', $check, function(e) {
                        e.preventDefault();
                        if( $( $check ).prop('checked') !== true ){
                          $($check).prop('checked', true);
                        } else if( $( $check ).prop('checked') !== false ) {
                          $($check).prop('checked', false);
                        }
						checkProStatus();
                      });
                  });

                  $('.engage-forms-options-form').on('click', '#tab_mailer', function() {
                    checkProStatus();
                  });

                  checkProStatus();
                });

            </script>

        <?php } else { ?>
            <script type="text/javascript">
              jQuery(".pro-enhanced").hide().attr('aria-hidden', true);
              jQuery(".no-pro-enhanced").show().attr('aria-hidden', false);
            </script>
        <?php }

    } else { ?>

        <script type="text/javascript">
          jQuery(".pro-enhanced").hide().attr('aria-hidden', true);
          jQuery(".no-pro-enhanced").show().attr('aria-hidden', false);
        </script>
    <?php } ?>

<script type="text/javascript">
	
    jQuery('body').on('change', '#mailer_status_select', function(){
        var status = jQuery(this);

        if(status.val() === '0'){
            jQuery('.mailer_config_panel').slideUp(100);
        }else{
            jQuery('.mailer_config_panel').slideDown(100);
        }
    });

</script>






