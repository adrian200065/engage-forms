<?php
/**
 * Support page -- debug view
 *
 * @package Engage_Forms
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 EngageWP LLC
 */

?>
<div id="engage-config-group-short">
	<h3><?php esc_html_e( 'Short Debug Information', 'engage-forms' ); ?></h3>

	<?php echo Engage_Forms_Support::short_debug_info(); ?>

</div>

<div id="engage-config-group-full">
	<h3><?php esc_html_e( 'Full Debug Information', 'engage-forms' ); ?></h3>

	<?php echo Engage_Forms_Support::debug_info(); ?>

</div>
