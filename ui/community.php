<div class="engage-editor-header">
	<ul class="engage-editor-header-nav">
		<li class="engage-editor-logo">
			<span class="dashicons-ef-logo"></span>
			<?php echo __('Engage Forms', 'engage-forms'); ?>
		</li>
		<li class="engage-element-type-label">
			<?php echo __('Community', 'engage-forms'); ?>
		</li>
	</ul>
</div>

<div class="engage-editor-header engage-editor-subnav">
	<ul class="engage-editor-header-nav ajax-trigger" data-load-class="spinner" data-request="<?php echo EFCORE_EXTEND_URL . 'channels/community/?version=' . EFCORE_VER; ?>" data-target="#main-cat-nav" data-target-insert="append" data-template="#nav-items-tmpl" data-event="loadchannels" data-autoload="true" id="main-cat-nav" >
	</ul>
</div>
<div class="form-extend-page-wrap" id="form-extend-viewer" style="visibility:visible;"></div>

<?php
	do_action('engage_forms_admin_templates');
?>

<script type="text/javascript">

function ef_clear_panel(el){
	jQuery(jQuery(el).data('target')).empty();
}
jQuery(function($){
	$('.engage-editor-header').on('click', '.engage-editor-header-nav a', function(e){
		e.preventDefault();

		var clicked = $(this);

		// remove active tab
		$('.engage-editor-header-nav li').removeClass('active');

		// hide all tabs
		$('.form-extend-page-wrap').hide();

		// show new tab
		$( clicked.attr('href') ).show();

		// set active tab
		clicked.parent().addClass('active');

	});

})

</script>