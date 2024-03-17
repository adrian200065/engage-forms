<?php
if( ! defined( 'ABSPATH' ) ){
	exit;
}
?>
<div class="engage-editor-header">
	<ul class="engage-editor-header-nav">
		<li class="engage-editor-logo">
			<span class="engage-forms-name"><?php echo esc_html(  $form[ 'name' ] ); ?><span class="engage-forms-name">
		</li>
		<?php if( current_user_can( Engage_Forms::get_manage_cap( 'admin' ) ) && empty( $form['_external_form'] ) ){ ?>
			<li class="engage-forms-toolbar-item">
				<a class="button" href="admin.php?page=engage-forms&edit=<?php echo $form['ID']; ?>">
					<?php esc_html_e( 'Edit' ); ?>
				</a>
			</li>
		<?php } ?>
	</ul>
</div>
<div class="form-extend-page-wrap">
<?php
if( isset( $_GET[ 'ef-alt-viewer' ] ) ){
	$form = Engage_Forms_Forms::get_form( $_GET[ 'ef-alt-viewer' ] );
	echo Engage_Forms_Entry_Viewer::form_entry_viewer_2( $form );
}else{
	?>
	<span class="form_entry_row highlight">
	<?php echo Engage_Forms_Entry_Viewer::entry_trigger( $form[ 'ID' ] ); ?>
</span>
	<?php
	$is_pinned = true;
	include EFCORE_PATH . 'ui/entries/toolbar.php';
	?>

	<div id="form-entries-viewer"></div>
	<?php include EFCORE_PATH . 'ui/entries/pagination.php'; ?>
</div>

<?php
Engage_Forms_Entry_Viewer::print_scripts();
?>

<?php
}
?>
</div>





