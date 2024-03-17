<?php
/**
 * Anti-spam settings panel
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@EngageWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 EngageWP LLC
 */
$element = $form = Engage_Forms_Forms::get_form( esc_attr( $_GET[ 'edit' ] ) );
if (empty($element['antispam'])) {
    $element['antispam'] = array();
}
if (empty($element['antispam']['enable'])) {
    $element['antispam']['enable'] = '';
}
if (empty($element['antispam']['sender_email'])) {
    $element['antispam']['sender_email'] = '';
}
if (empty($element['antispam']['sender_name'])) {
    $element['antispam']['sender_name'] = '';
}
$ef_pro_active = engage_forms_pro_is_active();
?>
<div id="anti-spam-settings-panel">
    <h3>
        <?php esc_html_e('AntiSpam Settings', 'engage-forms'); ?>
    </h3>

    <div class="engage-config-group">
        <fieldset>
            <legend>
                <?php esc_html_e('Basic', 'engage-forms'); ?>
            </legend>
            <div class="engage-config-field">
                <input
                        id="ef-honey"
                        type="checkbox"
                        class="field-config"
                        name="config[check_honey]"
                        value="1" <?php if (!empty($element['check_honey'])){ ?>checked="checked"<?php } ?>
                        aria-describedby="ef-honey-desc"
                />
                <label for="ef-honey">
                    <?php esc_html_e('Enable', 'engage-forms'); ?>
                </label>

                <p class="description" id="ef-honey-desc">
                    <?php esc_html_e('Uses an anti-spam honeypot', 'engage-forms'); ?>
                </p>
            </div>
        </fieldset>
    </div>
    <div class="engage-config-group">
        <fieldset>
            <legend>
                <?php esc_html_e('Advanced', 'engage-forms'); ?>
            </legend>
            <div class="engage-config-field">
                <input
                        id="ef-pro-anti-spam"
                        type="checkbox"
                        class="field-config"
                        name="config[antispam][enable]"
                        value="1"
                        <?php if ($ef_pro_active && !empty($element['antispam']['enable'])){ ?>checked="checked"<?php } ?>
                        <?php if (!$ef_pro_active) { ?>disabled<?php } ?>
                />
                <label for="ef-pro-anti-spam">
                    <?php esc_html_e('Enable'); ?>
                </label>
                <p class="description" id="ef-pro-anti-spam-desc">
                    <?php
                    esc_html_e('Uses Engage Forms Pro for spam scan and email address blacklist check.',
                        'engage-forms');
                    if (!$ef_pro_active) {
                        esc_html_e('Requires Engage Forms Pro', 'engage-forms');
                    }
                    ?>
                </p>

            </div>
        </fieldset>
    </div>

    <div class="engage-config-group" id="engage-anti-spam-settings-wrap">
        <div class="engage-config-group">
            <label for="ef-pro-anti-spam-sender-name">
                <?php echo __('Sender Name', 'engage-forms'); ?>
            </label>
            <div class="engage-config-field">
                <input
                        type="text"
                        id="ef-pro-anti-spam-sender-name"
                        class=" field-config magic-tag-enabled"
                        name="config[antispam][sender_name]"
                        value="<?php echo esc_attr($element['antispam']['sender_name']); ?>"
                        aria-describedby="ef-pro-anti-spam-sender-name-desc"
                />

                <p
                        id="ef-pro-anti-spam-sender-name-desc"
                        class="description"
                >
                    <?php esc_html_e('Field with the form submitter\'s name.', 'engage-forms'); ?>
                </p>
            </div>
        </div>
        <div class="engage-config-group">
            <label for="ef-pro-anti-spam-sender-name-email">
                <?php echo __('Email', 'engage-forms'); ?>
            </label>
            <div class="engage-config-field">
                <input
                        type="text"
                        id="ef-pro-anti-spam-sender-name-email"
                        class="field-config magic-tag-enabled engage-field-bind"
                        name="config[antispam][sender_email]"
                        value="<?php echo esc_attr($element['antispam']['sender_email']); ?>"
                        aria-describedby="ef-pro-anti-spam-sender-name-email-desc" ,
                />
                <p
                        id="ef-pro-anti-spam-sender-name-email-desc"

                        class="description"
                >
                    <?php esc_html_e('Field with the form submitter\'s email address', 'engage-forms'); ?>
                </p>
            </div>
        </div>
    </div>

</div>

<script>
    jQuery(function ($) {
        var $wrap = $('#engage-anti-spam-settings-wrap');
        var $enable = $('#ef-pro-anti-spam');
        var $inputs = $wrap.find( 'input' );
        var hideShow = function () {
            if ($enable.prop('checked') && !$enable.prop('disabled')) {
                $wrap
                    .show()
                    .attr('aria-hidden', false);
                $inputs.prop('required', true)
                    .addClass('required');

            } else {
                $wrap
                    .hide()
                    .attr('aria-hidden', true);
                $inputs
                    .prop('required', false)
                    .removeClass('required');
            }
        };

        $enable.change(function () {
            hideShow();
        });

        hideShow();
    });
</script>




