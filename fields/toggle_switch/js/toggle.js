jQuery(function($){
	$('body').on('click', '.ef-toggle-group-buttons a', function(){

		var clicked = $(this),
			parent = clicked.closest('.engage-config-field'),
			input = parent.find('[data-ref="'+clicked.attr('id')+'"]');


		parent.find('.btn').removeClass(clicked.data('active')).addClass(clicked.data('default'));
		clicked.addClass(clicked.data('active')).removeClass(clicked.data('default'));
		input.prop('checked', true).trigger('change');
	});
});

function toggle_button_init(id, el){	

	var field 		= jQuery(el),
		checked		= field.find('.ef-toggle-group-radio:checked');

	if(checked.length){
		jQuery('#' + checked.data('ref') ).trigger('click');
	}
	
}