<?php
/**
 * Form Settings panel
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */
?>
<div style="display: none;" class="engage-editor-body engage-config-editor-panel " id="settings-panel">
	<h3>
		<?php esc_html_e( 'Form Settings', 'engage-forms' ); ?>
	</h3>

	<input type="hidden" name="config[ef_version]" value="<?php echo esc_attr( EFCORE_VER ); ?>">

	<div class="engage-config-group">
		<label for="ef-form-name">
			<?php esc_html_e( 'Form Name', 'engage-forms' ); ?>
		</label>
		<div class="engage-config-field">
			<input id="ef-form-name"type="text" class="field-config required" name="config[name]" value="<?php esc_attr_e( $element[ 'name' ] ); ?>" style="width:500px;" required="required">
		</div>
	</div>

	<div class="engage-config-group">
		<label for="ef-shortcode-preview">
			<?php echo esc_html__( 'Shortcode', 'engage-forms' ); ?>
		</label>
		<div class="engage-config-field">
			<input type="text" id="ef-shortcode-preview" value="<?php echo esc_attr( '[engage-form id="' . $element[ 'ID' ] . '"]' ); ?>" style="width: 500px; background: #efefef; box-shadow: none; color: #8e8e8e;" readonly="readonly">
		</div>
	</div>

	<div class="engage-config-group">
		<fieldset>
			<legend>
				<?php esc_html_e( 'Scroll To Top On Submit', 'engage-forms' ); ?>
			</legend>
			<div class="engage-config-field">
				<label for="scroll_top-enable">
					<input id="scroll_top-enable" type="radio" class="field-config" name="config[scroll_top]" value="1" <?php if ( ! empty( $element[ 'scroll_top' ] ) ){ ?>checked="checked"<?php } ?> aria-describedby="scroll_top-disable-description">
					<?php esc_html_e( 'Enable', 'engage-forms' ); ?>
					<p class="description" id="scroll_top-disable-description">
						<?php esc_html_e( 'When form is submitted, scroll page to form message.', 'engage-forms' ); ?>
					</p>
				</label>
				<label for="scroll_top-disable">
					<input id="scroll_top-disable" type="radio" class="field-config" name="config[scroll_top]" value="0" <?php if ( empty( $element[ 'scroll_top' ] ) ){ ?>checked="checked"<?php } ?> aria-describedby="scroll_top-enable-description">
					<?php esc_html_e( 'Disable', 'engage-forms' ); ?>
					<p class="description" id="scroll_top-enable-description">
						<?php esc_html_e( 'When form is submitted, do not scroll page.', 'engage-forms' ); ?>
					</p>
				</label>
			</div>
		</fieldset>
	</div>

	<div class="engage-config-group" style="width:500px;">
		<label for="ef-success-message">
			<?php esc_html_e( 'Success Message', 'engage-forms' ); ?>
		</label>
		<div class="engage-config-field">
			<textarea id="ef-success-message" class="field-config block-input magic-tag-enabled required" name="config[success]" required="required" aria-describedby="ef-success-message-description"><?php if ( ! empty( $element[ 'success' ] ) ) {
					esc_html_e( $element[ 'success' ] );
				} else {
					esc_html_e( 'Form has been successfully submitted. Thank you.', 'engage-forms' );
				} ?>
			</textarea>
		</div>
		<p class="description" id="ef-success-message-description">
			<?php esc_html_e( 'Message to show after form is submitted.', 'engage-forms' ); ?>
		</p>
	</div>


	<div class="engage-config-group">
		<fieldset>
			<legend>
				<?php esc_html_e( 'Capture Entries', 'engage-forms' ); ?>
			</legend>
			<div class="engage-config-field">
				<label for="db_support-enable">
					<input id="db_support-enable" type="radio" class="field-config" name="config[db_support]" value="1" <?php if ( ! empty( $element[ 'db_support' ] ) ){ ?>checked="checked"<?php } ?>>
					<?php esc_html_e( 'Enable', 'engage-forms' ); ?>
				</label>
				<label for="db_support-disable">
					<input id="db_support-disable" type="radio" class="field-config" name="config[db_support]" value="0" <?php if ( empty( $element[ 'db_support' ] ) ){ ?>checked="checked"<?php } ?>>
					<?php esc_html_e( 'Disable', 'engage-forms' ); ?>
				</label>
			</div>
		</fieldset>
	</div>

    <div class="engage-config-group">
        <label id="engage-forms-label-delete-all-entries" for="engage-forms-delete-entries-field">
            <?php esc_html_e( 'Delete Saved Entries', 'engage-forms' ); ?>
        </label>
        <div
            id="engage-forms-delete-entries-field"
            class="engage-config-field"
        >
            <a
                href="#"
                class="button"
                id="engage-forms-delete-all-form-entries"
                aria-describedby="engage-forms-delete-entries-description"
                <?php //a used as button because that's the only way the JavaScript will work ?>
                role="button"
            >
                <?php esc_html_e('Delete All Saved Entries', 'engage-forms'); ?>
            </a>
            <div
                 id="engage-forms-confirm-delete-all-form-entries"
                 style="display: none;"
            >
                <p>
                    <?php esc_html_e('Are you sure you want to delete all the entries saved for this form ?', 'engage-forms'); ?>
                </p>
                <button
                    id="engage-forms-yes-confirm-delete-all-form-entries"
                    class="button"
                >
                    <?php esc_html_e('Yes', 'engage-forms'); ?>
                </button>
                <button
                        id="engage-forms-no-confirm-delete-all-form-entries"
                        class="button"
                >
                    <?php esc_html_e( 'No', 'engage-forms'); ?>
                </button>
                <span id="engage-forms-delete-entries-spinner" class="spinner"></span>
            </div>
            <p
                class="description"
                id="engage-forms-delete-entries-description"
            >
                <?php esc_html_e( 'Delete all the entries saved for this form. This can NOT be undone.', 'engage-forms' ); ?>
            </p>
        </div>
    </div>

	<div class="engage-config-group">
		<fieldset>
			<legend>
				<?php esc_html_e( 'Create sub-menu entry viewer', 'engage-forms' ); ?>
			</legend>
			<div class="engage-config-field">
				<label for="pin-toggle-roles-enable">
					<input  id="pin-toggle-roles-enable" type="radio" class="field-config pin-toggle-roles" name="config[pinned]" value="1"  <?php if ( ! empty( $element[ 'pinned' ] ) ){ ?>checked="checked"<?php } ?> aria-describedby="pin-toggle-roles-description">
					<?php esc_html_e( 'Enable', 'engage-forms' ); ?>
				</label>
				<label for="pin-toggle-roles-disable">
					<input id="pin-toggle-roles-disable" type="radio" class="field-config pin-toggle-roles" name="config[pinned]" value="0" <?php if ( empty( $element[ 'pinned' ] ) ){ ?>checked="checked"<?php } ?> aria-describedby="pin-toggle-roles-description">
					<?php  esc_html_e( 'Disable', 'engage-forms' ); ?>
				</label>
			</div>
			<p class="description" id="pin-toggle-roles-description">
				<?php esc_html_e( 'Creates a sub-menu item of the Engage Forms menu and a page to show entries for this form.', 'engage-forms' ); ?>
			</p>
		</fieldset>
	</div>



	<div id="engage-pin-rules" <?php if ( empty( $element[ 'pinned' ] ) ){ ?>style="display:none;"<?php } ?>>
		<div class="engage-config-group">
			<fieldset>
				<legend>
					<?php echo esc_html__( 'View Entries', 'engage-forms' ); ?>
				</legend>
				<div class="engage-config-field" style="max-width: 500px;">
					<label for="pin_role_all_roles">
						<input type="checkbox" id="pin_role_all_roles" class="field-config visible-all-roles"
						       data-set="form_role" value="1"
						       name="config[pin_roles][all_roles]" <?php if ( ! empty( $element[ 'pin_roles' ][ 'all_roles' ] ) ) {
							echo 'checked="checked"';
						} ?>>
						<?php esc_html_e( 'All', 'engage-forms' ); ?>
					</label>
					<hr>
					<?php

					$editable_roles = engage_forms_get_roles();

					foreach ( $editable_roles as $role => $role_details ) {
						if ( 'administrator' === $role ) {
							continue;
						}
						$id = 'ef-pin-role-' . $role;
						?>
						<label for="<?php echo esc_attr( $id ); ?>">
							<input id="<?php echo esc_attr( $id ); ?>" type="checkbox"
							       class="field-config form_role_role_check gen_role_check"
							       data-set="form_role" name="config[pin_roles][access_role][<?php echo $role; ?>]"
							       value="1" <?php if ( ! empty( $element[ 'pin_roles' ][ 'access_role' ][ $role ] ) ) {
								echo 'checked="checked"';
							} ?>>
							<?php echo esc_html( $role_details[ 'name' ] ); ?>
						</label>
						<?php
					}

					?>
				</div>
			</fieldset>
		</div>
	</div>

	<div class="engage-config-group">
		<fieldset>
			<legend>
				<?php esc_html_e( 'State', 'engage-forms' ); ?>
			</legend>
			<div class="engage-config-field">
				<label for="ef-forms-state">
					<input type="checkbox" id="ef-forms-state" class="field-config" name="config[form_draft]" value="1" <?php if ( ! empty( $element[ 'form_draft' ] ) ){ ?>checked="checked"<?php } ?>>
					<?php esc_html_e( 'Deactivate / Draft', 'engage-forms' ); ?>
				</label>
			</div>
		</fieldset>
	</div>

	<div class="engage-config-group">
		<fieldset>
			<legend>
				<?php esc_html_e( 'Hide Form', 'engage-forms' ); ?>
			</legend>
			<div class="engage-config-field">
				<label for="ef-hide-form">
					<input id="ef-hide-form" type="checkbox" class="field-config" name="config[hide_form]" value="1" <?php if ( ! empty( $element[ 'hide_form' ] ) ){ ?>checked="checked"<?php } ?>>
					<?php echo esc_html__( 'Enable', 'engage-forms' ); ?>
					: <?php echo esc_html__( 'Hide form after successful submission', 'engage-forms' ); ?>
				</label>
			</div>
		</fieldset>
	</div>

	<div class="engage-config-group">
		<fieldset>
			<legend>
				<?php esc_html_e( 'Honeypot', 'engage-forms' ); ?>
			</legend>
			<div class="engage-config-field">
				<label for="ef-honey">
					<input
							id="ef-honey"
							type="checkbox"
							class="field-config"
							name="config[check_honey]"
							value="1" <?php if (!empty($element['check_honey'])){ ?>checked="checked"<?php } ?>
							aria-describedby="ef-honey-desc"
					/>
				
					<?php esc_html_e('Enable', 'engage-forms'); ?>
					: <?php esc_html_e('Uses an anti-spam honeypot', 'engage-forms'); ?>
                </label>
			</div>
		</fieldset>
	</div>


	<div class="engage-config-group">
        <label for="ef-gravatar-field">
            <?php esc_html_e( 'Gravatar Field', 'engage-forms' ); ?>
        </label>
        <div class="engage-config-field">
            <select id="ef-gravatar-field" aria-describedby="ef-gravatar-field-description" style="width:500px;" class="field-config engage-field-bind" name="config[avatar_field]"
                    data-exclude="system" data-default="<?php if ( ! empty( $element[ 'avatar_field' ] ) ) {
                echo $element[ 'avatar_field' ];
            } ?>" data-type="email">
                <?php
                if ( ! empty( $element[ 'avatar_field' ] ) ) {
                    echo '<option value="' . $element[ 'avatar_field' ] . '"></option>';
                }
                ?>
            </select>
            <p class="description" id="ef-gravatar-field-description">
                <?php esc_html_e( 'Used when viewing an entry from a non-logged in user.', 'engage-forms' ); ?>
            </p>
        </div>
    </div>

	<?php

	/**
	 * Runs at the bottom of the general settings panel
	 *
	 * @since unknown
	 *
	 * @param array $element Form config
	 */
	do_action( 'engage_forms_general_settings_panel', $element );
	?>
</div>
