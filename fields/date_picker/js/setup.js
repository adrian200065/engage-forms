
jQuery(function($){

	$('.engage-editor-body').on('keyup', '.efdatepicker-set-format', function(){
		var format_field	= $(this),
			default_field	= format_field.closest('.engage-config-field-setup').find('.is-efdatepicker');

		default_field.data('date-format', format_field.val());

		default_field.efdatepicker('remove');

	});

});









