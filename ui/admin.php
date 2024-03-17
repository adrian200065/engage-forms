<?php

// Just some basics.
$per_page_limit = 20;

Engage_Forms::check_tables();
// get all forms
$orderby = isset( $_GET[ Engage_Forms_Admin::ORDERBY_KEY ] ) && 'name' == $_GET[ Engage_Forms_Admin::ORDERBY_KEY ] ? 'name' : false;
$forms = Engage_Forms_Forms::get_forms( true, false, $orderby );
$forms = apply_filters( 'engage_forms_admin_forms', $forms );


$style_includes = get_option( '_engage_forms_styleincludes' );
if(empty($style_includes)){
	$style_includes = array(
		'alert'	=>	true,
		'form'	=>	true,
		'grid'	=>	true,
	);
	update_option( '_engage_forms_styleincludes', $style_includes);
}



// create user modal buttons
$modal_new_form = esc_html__('Create Form', 'engage-forms').'|{"class": "ajax-trigger button", "onClick": "baldrickTriggers()", "data-action" : "create_form", "data-active-class": "disabled", "data-load-class": "disabled", "data-callback": "new_form_redirect", "data-before" : "serialize_modal_form", "data-modal-autoclose" : "new_form" }|right';

?><div class="engage-editor-header">

        <ul class="engage-editor-header-nav">
            <li class="engage-editor-logo">
                <span class="engage-forms-name">Engage Forms</span>
            </li>
            <?php $deprecated = Engage_Forms_Admin_PHP::is_version_deprecated( PHP_VERSION );
            if ( ! $deprecated ): ?>
                <li class="engage-forms-version">
                    <?php echo EFCORE_VER; ?>
                </li>
                <li class="engage-forms-toolbar-item">
                    <button class="button button-primary ajax-trigger ef-new-form-button"
							onClick="baldrickTriggers()"
                            data-request="start_new_form"
                            data-modal-no-buttons='<?php echo esc_attr( $modal_new_form ); ?>'
                            data-modal-width="70%"
                            data-modal-height="80%"
                            data-load-class="none" data-modal="new_form"
                            data-nonce="<?php echo wp_create_nonce('ef_create_form'); ?>"
                            data-modal-title="<?php esc_attr_e('Create New Form', 'engage-forms' ); ?>"
                            data-template="#new-form-tmpl">
                        <?php  esc_html_e('New Form', 'engage-forms'); ?>
                    </button>
                </li>
                <li class="engage-forms-toolbar-item">
                    <button class="button ajax-trigger"
							onClick="baldrickTriggers()"
                            data-request="start_new_form"
                            data-modal-width="400"
                            data-modal-height="270"
                            data-modal-element="div"
                            data-load-class="none"
                            data-modal="import_form"
                            data-template="#import-form-tmpl"
                            data-modal-title="<?php esc_attr_e('Import Form', 'engage-forms' ); ?>">
                        <?php  esc_html_e('Import', 'engage-forms'); ?>
                    </button>
                </li>
                <li class="engage-forms-toolbar-item separator">&nbsp;&nbsp;</li>
                <li class="engage-forms-toolbar-item" id="ef-email-settings-item">
                    <?php
                    printf('<button class="button" id="ef-email-settings" title="%s">%s</button>',
                        esc_attr__('Click to modify Engage Forms email settings', 'engage-forms'),
                        esc_html__('Email Settings', 'engage-forms')
                    );
                    ?>
                </li>
                <li class="engage-forms-toolbar-item separator">&nbsp;&nbsp;</li>
                <li class="engage-forms-toolbar-item">
                    <button class="button ajax-trigger ef-general-settings" data-request="toggle_front_end_settings"
                            data-modal-width="400" data-modal-height="405" data-modal-element="div" data-load-class="none"
                            data-modal="front_settings" data-template="#front-settings-tmpl"
                            data-callback="toggle_front_end_settings"
                            data-modal-title="<?php esc_attr_e('General Settings', 'engage-forms'); ?>"
                            title="<?php esc_attr_e('General Settings', 'engage-forms'); ?>">
                        <?php
                        printf('<span title="%s">%s</span>',
                            esc_attr__('Click to modify Engage Forms general settings', 'engage-forms'),
                            esc_html__('General Settings', 'engage-forms')
                        );
                        ?>
                    </button>
                </li>
                <li class="engage-forms-toolbar-item separator">&nbsp;&nbsp;</li>
                <li class="engage-forms-toolbar-item" id="ef-form-order-item">
                    <?php
                    if ('name' === $orderby) {
                        $text = __('Order Forms By ID', 'engage-forms');
                        $url = Engage_Forms_Admin::main_admin_page_url();
                    } else {
                        $text = __('Order Forms By Name', 'engage-forms');
                        $url = Engage_Forms_Admin::main_admin_page_url('name');
                    }
                    printf('<a  class="button" id="ef-form-order" title="%s" href="%s">%s</a>',
                        esc_attr__('Click to change order of the forms', 'engage-forms'),
                        esc_url($url),
                        esc_html__($text)
                    );
                    ?>
                </li>
                <li class="engage-forms-toolbar-item separator engage-forms-hide-when-entry-viewer-closed">&nbsp;&nbsp;</li>
                <li class="engage-forms-toolbar-item engage-forms-hide-when-entry-viewer-closed" id="engage-forms-close-entry-viewer-wrap" style="display: none;visibility: hidden" aria-hidden="true">
                    <?php
                    printf('<button title="%s" class="button" id="engage-forms-close-entry-viewer">%s</button>',
                        esc_attr__('Click to close entry Viewer', 'engage-forms'),
                        esc_html__('Close Entry Viewer', 'engage-forms')
                    );
                    ?>
                </li>
                <?php if (isset($_GET['message_resent'])) { ?>
                    <li class="engage-forms-toolbar-item separator">&nbsp;&nbsp;</li>
                    <li class="engage-forms-toolbar-item success">
                        <?php esc_html_e('Message Resent', 'engage-forms'); ?>
                    </li>
            <?php } ?>
            <?php else : //is deprecated
                echo '<li id="ef-deprecated-notice-wrap ef-alert-wrap"><span class="ef-alert ef-alert-error">' . Engage_Forms_Admin_PHP::get_deprecated_notice() . '</span></li>';
            endif; //php version depreacted ?>
        </ul>

</div>

<div class="form-admin-page-wrap">
	<div id="engage-forms-admin-page-left">
		<div class="form-panel-wrap">
	<?php

	// admin notices


	?>
	<div class="ef-notification" style="display:none;">
		<span class="dashicons dashicons-arrow-down ef-notice-toggle"></span>
		<span class="dashicons dashicons-arrow-up ef-notice-toggle" style="display:none;"></span>
		<div class="ef-notification-notice">
			<span class="dashicons dashicons-warning"></span>
			<span class="ef-notice-info-line"></span>
		</div>
		<div class="ef-notification-count"></div>
		<div class="ef-notification-panel"></div>
	</div>
	<?php if(! empty( $forms ) ){ ?>
		<table class="widefat fixed">
			<thead>
				<tr>
					<th><?php _e('Form', 'engage-forms'); ?></th>
					<th style="width:5em; text-align:center;"><?php _e('Entries', 'engage-forms'); ?></th>
				</tr>
			</thead>
			<tbody>
		<?php

			global $wpdb;

			$class = "alternate";
			foreach($forms as $form_id=>$form){
				if( !empty( $form['hidden'] ) ){
					continue;
				}

				if(!empty($form['db_support'])){
					$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(`id`) AS `total` FROM `" . $wpdb->prefix . "ef_form_entries` WHERE `form_id` = %s && `status` = 'active';", $form_id));
				}else{
					$total = __('Disabled', 'engage-forms');
				}

				?>

				<tr id="<?php  echo esc_attr( trim( 'form_row_' . $form_id ) ); ?>" class="<?php echo $class; ?> form_entry_row">
					<td class="<?php if( !empty( $form['form_draft'] ) ) { echo 'draft-form'; }else{ echo 'active-form'; } ?>">
						<span class="ef-form-name-preview"><?php esc_html_e( $form[ 'name' ] ); ?></span> <input readonly type="text" class="ef-shortcode-preview" value="<?php echo esc_attr( '[engage-form id="' . trim( $form[ 'ID' ] ) . '"]'); ?>"> <span class="ef-form-shortcode-preview"><?php echo esc_html__( 'Get Shortcode', 'engage-forms' ); ?></span>

						<?php if( !empty( $form['debug_mailer'] ) ) { ?>
						<span style="color: rgb(207, 0, 0);" class="description"><?php _e('Mailer Debug enabled.', 'engage-forms') ;?></span>
						<?php } ?>

						<div class="row-actions">
						<?php if( empty( $form['_external_form'] ) ){ ?><span class="edit"><a class="form-control" href="<?php echo esc_url( Engage_Forms_Admin::form_edit_link( $form_id ) ); ?>"><?php echo __('Edit'); ?></a> | </span>
						<span class="edit"><a class="form-control ajax-trigger" href="#entres"
						data-load-element="<?php echo esc_attr( '#form_row_' . trim( $form_id ) ); ?>"
						data-action="toggle_form_state"
                        data-nonce="<?php echo esc_attr( wp_create_nonce( 'toggle_form_state') ); ?>"
						data-active-element="<?php echo esc_attr( '#form_row_' . trim( $form_id ) ); ?>"
						data-callback="set_form_state"
						data-form="<?php echo esc_attr( trim( $form_id ) ); ?>"

						><?php if( !empty( $form['form_draft'] ) ) { echo __('Enable', 'engage-forms'); }else{ echo __('Disable', 'engage-forms'); } ?></a> | </span><?php } ?>

						<?php if(!empty($form['db_support'])) { ?>
							<span class="edit">
								<?php if (  ! version_compare( PHP_VERSION, '5.3.0', '>=' ) ) {
									echo '<span title="' . esc_attr( sprintf( __( 'Entry viewer is disabled due to your PHP version, see %s', 'engage-forms' ), 'https://engageforms.com/php?utm_source=wp-admin&utm_keyword=entry_viewer_php_version' ) ) .'" data-toggle="tooltip" data-placement="bottom" class="disabled">' . esc_html__( 'Entries', 'engage-forms' ) . '</span> |';
								} else { ?>
									<a class="form-control form-entry-trigger ajax-trigger ef-entry-viewer-link"
										onClick="baldrickTriggers()"
									   href="#entres"
									   data-nonce="<?php echo wp_create_nonce( 'view_entries' ); ?>"
									   data-action="browse_entries"
									   data-target="#form-entries-viewer"
									   data-form="<?php echo esc_attr( trim( $form_id ) ); ?>"
									   data-template="#forms-list-alt-tmpl"
									   data-active-element="<?php echo esc_attr( '#form_row_' . trim( $form_id ) ); ?>"
									   data-load-class="spinner"
									   data-active-class="highlight"
									   data-group="entry_nav"
									   data-callback="setup_pagination"
									   data-status="active"
									   data-page="1"
									><?php esc_html_e( 'Entries', 'engage-forms' ); ?></a> | </span><?php }
						}?>
						<input type="hidden" id="form-export-<?php echo $form_id; ?>" value='{ "formslug" : "<?php echo sanitize_title( $form['name'] ); ?>", "formid" : "<?php echo $form_id; ?>", "nonce" : "<?php echo wp_create_nonce( 'ef_del_frm' ); ?>" }'>
						<?php if( empty( $form['_external_form'] ) ){ ?><span class="export"><a class="form-control ajax-trigger"
							<?php
								// build exporter buttons
								$buttons = array(
									'data-request' => 'ef_build_export',
									'data-modal-autoclose' => 'export',
									'class'	=> 'ajax-trigger button'
								);
							?>
							data-modal="export"
							data-modal-height="400"
							data-modal-title="<?php echo esc_attr( __('Export Form', 'engage-forms') ); ?>"
							data-request="<?php echo esc_attr( '#form-export-' . trim( $form_id ) ); ?>"
							data-type="json"
							data-modal-buttons="<?php echo esc_attr( __( 'Export Form', 'engage-forms' ) ); ?>|<?php echo esc_attr( json_encode( $buttons ) ); ?>"
							data-template="#ef-export-template"
							href="#export"><?php esc_html_e('Export', 'engage-forms'); ?></a> | </span><?php } ?>
						<span>
                            <a
                                class="ajax-trigger clone-form-<?php esc_attr_e( $form_id ); ?>"
                                href="#clone"
                                data-request="start_new_form"
                                data-modal-buttons='<?php echo  $modal_new_form ; ?>'
                                data-clone="<?php esc_attr_e( $form_id ); ?>"
                                data-modal-width="600"
                                data-modal-height="160"
                                data-load-class="none"
                                data-modal="new_clone"
                                data-nonce="<?php esc_attr_e( wp_create_nonce( 'ef_create_form' ) ); ?>"
                                data-modal-title="<?php esc_attr_e('Clone Form', 'engage-forms'); ?>"
                                data-template="#new-form-tmpl"
                            >
                                <?php echo esc_html__('Clone', 'engage-forms'); ?>
                            </a>
                            <?php if( empty( $form['_external_form'] ) ){ ?> | </span>
						<span class="trash form-delete"><a class="form-control" data-confirm="<?php echo __('This will delete this form permanently. Continue?', 'engage-forms'); ?>" href="admin.php?page=engage-forms&delete=<?php echo trim( $form_id ); ?>&cal_del=<?php echo wp_create_nonce( 'ef_del_frm' ); ?>"><?php echo __('Delete'); ?></a></span><?php } ?>


						</div>
					</td>
					<td style="width:4em; text-align:center;" class="<?php echo esc_attr( 'entry_count_' . trim( $form_id ) ); ?><?php echo $form_id; ?>"><?php echo $total; ?></td>
				</tr>


				<?php
				if($class == 'alternate'){
					$class = '';
				}else{
					$class = "alternate";
				}

			}
		?></tbody>
		</table>
		<?php }else{ ?>
		<div id="ef-you-have-no-forms">
			<p style="margin: 24px;">
				<?php esc_html_e( 'You don\'t have any forms yet.', 'engage-forms'); ?>
			</p>

		</div>
		<div class="engage-forms-clippy-zone-inner-wrap">
			<div class="engage-forms-clippy">
				<h2>
					<?php esc_html_e( 'New To Engage Forms?', 'engage-forms' ); ?>
				</h2>
				<p>
					<?php esc_html_e( 'We have a complete getting started guide for new users.', 'engage-forms' ); ?>
				</p>

				<a href="https://engageforms.com/getting-started?utm-source=wp-admin&utm_campaign=clippy&utm_term=no-forms" target="_blank" class="bt-btn btn btn-orange">
					<?php esc_html_e( 'Read Now', 'engage-forms' ); ?>
				</a>
			</div>
		</div>

		<?php } ?>
	</div>
	</div>
	<div id="engage-forms-admin-page-right">
        <?php if ( 1===7 && ! is_ssl() ): ?>
            <div class="engage-forms-clippy-zone warn-clippy">
                <div class="engage-forms-clippy-zone-inner-wrap" style="background: white">
                    <div class="engage-forms-clippy"
                         style="background-color:white;border-left: 4px solid #dc3232;">
                        <h2>
                            <?php esc_html_e( 'Your Forms Might Be Marked Insecure', 'engage-forms' ); ?>
                        </h2>
                        <p>
                            <?php esc_html_e( 'WordPress reports that you are not using SSL. Your forms may be marked insecure by browsers if not loaded using HTTPS.', 'engage-forms' ); ?>
                        </p>
                        <a href="https://engageforms.com/docs/ssl?utm-source=wp-admin&utm_campaign=clippy&utm_term=support"
                           target="_blank" class="bt-btn btn btn-green" style="width: 80%;margin-left:5%;">
                            <?php esc_html_e( 'Learn More', 'engage-forms' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php //id is "clippy for historical reason
        ///do not change without accounting for other JS and CSS that assumes this id ?>
		<div id="engage-forms-clippy">

		</div>
		<?php echo Engage_Forms_Entry_Viewer::full_viewer(); ?>

	</div>
</div>

<?php
do_action('engage_forms_admin_templates');
?>
<script type="text/javascript">

function set_form_state( obj ){
	if( true === obj.data.success ){

		var row = jQuery('#form_row_' + obj.data.data.ID + '>td');
		row.first().attr('class', obj.data.data.state );
		obj.params.trigger.text( obj.data.data.label );

	}
}

function new_form_redirect(obj){
	if(typeof obj.data === 'string'){
		window.location = '<?php echo esc_url(add_query_arg( ['page' => 'engage-forms' ], admin_url( 'admin.php' ) ) ); ?>&edit=' + obj.data.trim();
	}else{
		alert(obj.data.error);
	}
}

function serialize_modal_form(el){

	var clicked	= jQuery(el),
		data 	= clicked.closest('.baldrick-modal-wrap'),
		name 	= data.find('.new-form-name');

	if( clicked.hasClass( 'ef-loading-form' ) ){
		return false;
	}
	//verify name is set
	if(name.val().length < 1){
		name.focus().addClass('has-error');
		return false;
	}


	clicked.data('data', data.serialize()).addClass('ef-loading-form').animate({width: 348}, 200);

	jQuery('.ef-change-template-button').animate({ marginLeft: -175, opacity: 0 }, 200);

	return true;
}

var ef_front_end_settings = {};
function update_setting_toggle(obj){
	ef_front_end_settings = obj.data;
	toggle_front_end_settings();
}
function toggle_front_end_settings(){

	for( var k in ef_front_end_settings){
		if(ef_front_end_settings[k] === true){
			jQuery('.setting_toggle_' + k).addClass('active');
		}else{
			jQuery('.setting_toggle_' + k).removeClass('active');
		}
	}
}

function get_front_end_settings( obj ){
	//ef_front_end_settings
	return ef_front_end_settings;
}

function extend_fail_notice(el){
	jQuery("#extend_ef_baldrickModalBody").html('<div class="alert error"><p><?php echo __('Looks like something is not working. Please try again a little later or post to the <a href="http://wordpress.org/support/plugin/engage-forms" target="_blank">support forum</a>.', 'engage-forms'); ?></p></div>');
}

function start_new_form(obj){
	if( obj.trigger.data('clone') ){
		return {clone: obj.trigger.data('clone') };
	}
	return {};
}

var ef_build_export;
jQuery( function( $ ){

	ef_build_export = function( el ){
		var export_object = jQuery('#export_baldrickModal').serialize();
		window.location = "<?php echo esc_attr( admin_url('admin.php?page=engage-forms' ) ); ?>&" + export_object;
	};

	var $notices = jQuery('.error,.notice,.notice-error');
	$notices.remove();

	jQuery( document ).on('submit', '#new_form_baldrickModal', function(e){
		e.preventDefault();
		var trigger = jQuery(this).find('button.ajax-trigger');
		trigger.trigger('click');
	});
	var form_toggle_state = false;
	jQuery( document ).on( 'click', '.hide-forms', function(){
		var clicked = jQuery(this),
			panel = jQuery('.form-admin-page-wrap'),
			forms = jQuery('.form-panel-wrap'),
			size = -35;

		if( true === form_toggle_state ){
			size = 430;
			clicked.find('span').css({transform: ''});
			form_toggle_state = false;
			forms.attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' ).show();
		}else{
			form_toggle_state = true;
			clicked.find('span').css({transform: 'rotate(180deg)'});
			forms.attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' ).hide();
		}
		panel.animate( {marginLeft: size }, 220);


	});

	jQuery( document ).on('change', '.ef-template-select', function(){
		var template = jQuery(this).parent(),
			create = jQuery('.ef-form-create'),
			name = jQuery('.new-form-name');

		if( create.find('.ef-loading-form').length ){
			return;
		}
		jQuery('.ef-template-title').html( template.find('small').html() );
		jQuery('.ef-form-template.selected').removeClass('selected');
		template.addClass('selected');
		jQuery('.ef-form-template.selected').animate( {opacity: 1}, 100 );
		//$('.ef-form-template:not(.selected)').animate( {opacity: 0.6}, 200 );
		// shift siding
		var box = jQuery('.ef-templates-wrapper');
		var relativeX = box.offset().left - template.offset().left;
		var boxwid = box.offset().left + box.innerWidth();
		var diffwid = template.offset().left + template.innerWidth();
		jQuery('.ef-form-template').css('overflow', 'hidden').find('.row,small').show();
		template.css('overflow', 'visible').find('.row,small').hide();
		if( boxwid - diffwid > template.outerWidth() ){
			create.css( { left : -2, right: '' } );
		}else{
			create.css( { right : -2, left: '' } );
		}

		create.appendTo(template).attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' ).fadeIn( 100 );

		name.focus();
	});
	jQuery( document ).on('click', '.ef-change-template-button', function(){
		jQuery('.ef-template-select:checked').prop('checked', false);
		jQuery('.ef-form-template').removeClass('selected');
		//$('.ef-form-template').animate( {opacity: 1}, 200 );
		jQuery('.ef-form-create').fadeOut(100, function(){
			jQuery('.ef-form-template').css('overflow', 'hidden').find('div,small').fadeIn(100);
			jQuery(this).attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' );
		})
	});



	//switch in and out of email settings
	var inEmailSettings = false;
	jQuery( '#ef-email-settings' ).on( 'click', function(e){
		e.preventDefault();
		var $mainUI = jQuery( '.form-panel-wrap, .form-entries-wrap' ),
			$emailSettingsUI = jQuery('#ef-email-settings-ui'),
			$otherButtons = jQuery('.engage-forms-toolbar-item a'),
			$toggles = jQuery('.toggle_option_preview, #render-with-label'),
			$clippy = jQuery('#engage-forms-clippy');

		if( inEmailSettings ){
			jQuery( this ).html( '<?php esc_html_e( 'Email Settings', 'engage-forms' ); ?>' );
			inEmailSettings = false;
			$otherButtons.removeClass( 'disabled' );
			$emailSettingsUI.hide().attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' );
			$mainUI.show().attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' );
			$toggles.show().attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' );
			$clippy.show().attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' );
		}else{
			inEmailSettings = true;
			jQuery( this ).html( '<?php esc_html_e( 'Close Email Settings', 'engage-forms' ); ?>' );
			$otherButtons.addClass( 'disabled' );
			$mainUI.hide().attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' );
			$emailSettingsUI.show().attr( 'aria-hidden', 'false' ).css( 'visibility', 'visible' );
			jQuery( this ).html = "<?php esc_html__( 'Email Settings', 'engage-forms' ); ?>";
			$clippy.hide().attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' );
			$toggles.hide().attr( 'aria-hidden', 'true' ).css( 'visibility', 'hidden' );
		}



	});

	//handle save of email settings
	jQuery( '#ef-email-settings-save' ).on( 'click', function( e ) {
		e.preventDefault( e );
		var data = {
			nonce: jQuery('#efemail').val(),
			action: 'ef_email_save',
			method: jQuery('#ef-emails-api').val(),
			sendgrid: jQuery('#ef-emails-sendgrid-key').val()
		};
		var $spinner = jQuery( '#ef-email-spinner' );
		$spinner.attr( 'aria-hidden', false ).css( 'visibility', 'visible' ).show();

		jQuery.post( ajaxurl, data ).done( function( r ) {
			$spinner.attr( 'aria-hidden', true ).css( 'visibility', 'hidden' ).hide(
				500, function(){
					document.getElementById( 'ef-email-settings' ).click();
				}
			);
		});

	});




	jQuery(document).on('click', '.ef-form-shortcode-preview', function(){
		var clicked = jQuery( this ),
			shortcode = clicked.prev(),
			name = shortcode.prev();
		name.hide();
		clicked.hide();
		shortcode.show().focus().select();
	});
	jQuery(document).on('blur', '.ef-shortcode-preview', function(){
		var clicked = jQuery( this ),
			form = clicked.prev(),
			name = clicked.next();
		clicked.hide();
		form.show();
		name.show();
	})

	jQuery( function() {
		var emailSettingsNotificationNudgeTimeout = setTimeout(function(){
			jQuery('#ef-email-settings').addClass('ef-email-settings-notification-nudge');
		}, 3000);
		jQuery('#ef-email-settings').on('click', function() {
			clearTimeout(emailSettingsNotificationNudgeTimeout);
			jQuery('#ef-email-settings').removeClass('ef-email-settings-notification-nudge');
		});
	});

});
</script>

<style>
.ef-email-settings-notification-nudge {
	position: relative;
}
.ef-email-settings-notification-nudge::after {
	content: " ";
	background-color: #ca4a1f;
	width: 10px;
	height: 10px;
	position: absolute;
	top: -5px;
	right: -5px;
	border-radius: 50%;
}
</style>

<?php

/**
 * Runs at the bottom of the main Engage Forms admin page
 *
 * @since unknown
 */
do_action('engage_forms_admin_footer');
?>
