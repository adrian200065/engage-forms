<div class="engage-config-group">
	<label><?php echo __('Progress Bar', 'engage-forms'); ?></label>
	<div class="engage-config-field">
		<label><input type="checkbox" name="config[auto_progress]" value="1" <?php if(!empty($element['auto_progress'])){ echo 'checked="checked"'; } ?>> <?php echo __('Show Breadcrumbs', 'engage-forms'); ?></label>
		<p class="description"><?php echo __('ProTip: Use an HTML element to build a custom progress per page', 'engage-forms'); ?></p>
	</div>
</div>
<div id="page_name_bind">

</div>
<?php do_action( 'engage_forms_pages_config', $element ); ?>
<script type="text/html" id="page-name-tmpl">
<div class="engage-config-group">
	<label><?php echo __('Page', 'engage-forms'); ?> {{page_no}}</label>
	<div class="engage-config-field">
		<input type="text" class="field-config" name="config[page_names][]" value="" style="width:400px;">
	</div>
</div>
</script>
<script>
	jQuery(document).on('add.page remove.page load.page', function(){
		
		var pages = jQuery('.page-toggle.button'),
			wrap = jQuery('#page_name_bind'),
			template 	= jQuery('#page-name-tmpl').html();

		wrap.empty();

		pages.each(function(k,v){
			var page 		= jQuery(v),
				efg_tmpl	= jQuery(template.replace(/{{page_no}}/g, k+1));

			efg_tmpl.find('.field-config').val(page.data('name'));

			efg_tmpl.appendTo(wrap);
		});

	});
</script>