<?php
/**
 * Settings for Engage Forms emails
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
?>
<div id="ef-email-settings-ui" aria-hidden="true" style="visibility: hidden;">
	<div style="margin:20px;">
		<div class="engage-forms-clippy-zone-inner-wrap" style="background: white">
			<div class="engage-forms-clippy"
			     style="text-align:center;background-color:white;padding:20px;">
				<h2>
					<?php esc_html_e( 'Getting WordPress email into an inbox just got a lot easier!', 'engage-forms' ); ?>
				</h2>
				<p>
					<?php
					esc_html_e(
						'SendWP makes getting emails delivered as simple as a few clicks. So you can relax, knowing those important emails are being delivered on time.',
						'engage-forms'
					);
					?>
				</p>
				<button
					class="button button-primary"
					style="display:block;margin:20px auto;"
					onClick="engage_forms_sendwp_remote_install()"
					>
					<?php esc_html_e('Signup for SendWP', 'engage-forms'); ?>
				</button>
				<a href="https://sendwp.com?utm_source=Engage+Forms+Plugin&utm_medium=Forms_Email+Settings&utm_campaign=SendWP+banner+ad"
				   target="_blank" class="bt-btn btn btn-green" style="width: 80%;margin: auto;">
					<?php esc_html_e( 'Learn More', 'engage-forms' ); ?>
				</a>
			</div>
		</div>
	</div>
	<div class="ef-emails-field-group engage-config-group" id="ef-emails-api-wrap">
		<label for="ef-emails-api" id="ef-emails-api-label">
			<?php esc_html_e( 'Email System', 'engage-forms' ); ?>
		</label>
		<div class="ef-emails-field">
			<select class="ef-email-settings" id="ef-emails-api" aria-labelledby="ef-emails-api-label" aria-describedby="ef-emails-api-desc">
				<option value="wp" <?php if ( 'wp'  == Engage_Forms_Email_Settings::get_method() ) : echo 'selected'; endif; ?> >
					<?php esc_html_e( 'WordPress', 'engage-forms' ); ?>
				</option>
				<option value="sendgrid" <?php if ( 'sendgrid'  == Engage_Forms_Email_Settings::get_method() ) : echo 'selected'; endif; ?> >
					<?php esc_html_e( 'SendGrid', 'engage-forms' ); ?>
				</option>
				<option value="engage" <?php if ( 'engage'  == Engage_Forms_Email_Settings::get_method() ) : echo 'selected'; endif; ?> disabled >
					<?php esc_html_e( 'Engage (coming soon)', 'engage-forms' ); ?>
				</option>
			</select>
		</div>
		<p class="description" id="ef-emails-api-desc" style="max-width: 440px; margin-bottom: 12px;">
			<?php esc_html_e( 'By default Engage Forms uses WordPress to send emails. You can choose to use another method to increase reliability of emails and reduce server load.', 'engage-forms' ); ?>
		</p>
	</div>
	<div class="ef-emails-field-group engage-config-group" id="ef-emails-sendgrid-key-wrap">
		<label for="ef-emails-sendgrid-key" id="ef-emails-sendgrid-key-label">
			<?php esc_html_e( 'SendGrid API Key', 'engage-forms' ); ?>
		</label>
		<div class="ef-emails-field-group">
			<input type="text" class="ef-email-settings" id="ef-emails-sendgrid-key" name="ef-emails-sendgrid-key" value="<?php echo esc_attr( Engage_Forms_Email_Settings::get_key( 'sendgrid' ) ); ?>">
		</div>
		<p class="description" id="ef-emails-sendgrid-key-desc" style="max-width: 440px; margin-bottom: 12px;">
		<?php printf( '<span>%s</span> <span><a href="%s" target="_blank" rel="nofollow" title="%s">%s</a></span>',
					esc_html__( 'SendGrid API Key', 'engage-forms' ),
					'https://EngageWP.com/docs/configure-sendgrid',
					esc_attr__( 'Documentation for configuring SendGrid API', 'engage-forms' ),
					esc_html__( 'Learn More', 'engage-forms' )
				);
		?></p>

	</div>
	<?php echo Engage_Forms_Email_Settings::nonce_field(); ?>
	<br><br>
	<div class="field-group">
		<button type="button" id="ef-email-settings-save" class="button button-primary">
			<?php esc_html_e( 'Save Email Settings', 'engage-forms' ); ?>
		</button>
		<span class="spinner" style="float:none;" id="ef-email-spinner" aria-hidden="true"></span>
	</div>
</div>
