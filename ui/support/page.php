<?php
/**
 * Support page -- main view
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */

?>
<div class="engage-editor-header">
	<ul class="engage-editor-header-nav">
		<li class="engage-editor-logo">
			<span class="engage-forms-name">
				<?php esc_html_e( 'Engage Forms: Support', 'engage-forms' ); ?>
			</span>

		</li>
		<li class="engage-forms-toolbar-link" id="support-nav-info">
			<a href="#info">
				<?php esc_html_e( 'How To Get Support', 'engage-forms' ); ?>
			</a>
		</li>
		<li class="engage-forms-toolbar-link" id="support-nav-debug">
			<a href="#debug">
				<?php esc_html_e( 'Debug Information', 'engage-forms' ); ?>
			</a>
		</li>
		<li class="engage-forms-toolbar-link" id="support-nav-beta">
			<a href="#beta">
				<?php esc_html_e( 'Get Latest Beta', 'engage-forms' ); ?>
			</a>
		</li>

	</ul>
</div>
<div class="support-admin-page-wrap" style="margin-top: 75px;">
	<div class="support-panel-wrap" id="panel-support-info" style="visibility: visible" aria-hidden="false">
		<?php include EFCORE_PATH  . 'ui/support/panels/support.php'; ?>
	</div>
	<div class="support-panel-wrap" id="panel-support-debug" style="visibility: hidden" aria-hidden="true">
		<?php include EFCORE_PATH  . 'ui/support/panels/debug.php'; ?>
	</div>
	<div class="support-panel-wrap" id="panel-support-beta" style="visibility: hidden" aria-hidden="true">
		<?php include EFCORE_PATH  . 'ui/support/panels/beta.php'; ?>
	</div>
</div>

