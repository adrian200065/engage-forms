jQuery(function($){

	$('.color-field').wpColorPicker({
		mode : 'rgba'
	});

	$('body').on('click', '#engage-forms-form-insert,#wp_fs_engage-forms', function(e){
		e.preventDefault();
		var modal = $('.engage-forms-insert-modal')
	 		data = $(this).data('settings');

	 	if( data ){

	 		if( data.id ){
	 			$('.selected-form-shortcode[value="' + data.id + '"]').prop('checked', true );
	 		}
	 		if( data.modal ){
	 			$('.set_ef_modal').prop('checked', true );
	 			$('.modal-forms-setup').show();
	 		}
	 		if( data.type ){
	 			$('.modal_trigger_type').val(data.type);
	 		}
	 		if( data.content ){
	 			$('.modal_trigger').val(data.content);
	 		}
	 		if( data.width ){
	 			$('.modal_width').val(data.width);
	 		}
	 		$(this).data('settings', {} );
	 	}


		modal.fadeIn(100);

	});

	$('body').on('click', '.engage-modal-closer', function(e){
		e.preventDefault();
		var modal = $('.engage-forms-insert-modal');
		$('#engagef_forms_shortcode_modal')[0].reset();
		$('.modal-forms-setup').hide();
		modal.fadeOut(100);	

	});
	$('body').on('change', '.set_ef_modal', function(e){
		var clicked = $(this);

		if( clicked.is(':checked') ){
			$('.modal-forms-setup').show();
		}else{
			$('.modal-forms-setup').hide();			
		}
	});
	$('body').on('click', '.engage-form-shortcode-insert', function(e){
	 	
	 	e.preventDefault();
	 	var form = $('.selected-form-shortcode:checked'),
	 		is_modal = $('.set_ef_modal').prop('checked'),
	 		modal_trigger = $('.modal_trigger').val(),
	 		modal_trigger_type = $('.modal_trigger_type').val(),
	 		width = $('.modal_width').val(),
	 		code;

	 	if(!form.length){
	 		return;
	 	}

	 	var tag = 'engage-form';
	 	if( is_modal ){
	 		tag = 'engage-form_modal';
	 	}

	 	code = '[' + tag + ' id="' + form.val() + '"';
	 	if( is_modal === true ){
	 		//code += ' modal="true"';
	 		if( modal_trigger_type === 'button' ){
	 			code += ' type="' + modal_trigger_type + '"';
	 		}
	 	}
		if( width.length ){
			code += ' width="' + width + '"';	
		}
	 	code += ']';

	 	if( is_modal ){
	 		if( modal_trigger.length ){
	 			code += modal_trigger;
	 		}else{
				code += form.parent().text();
	 		}
	 		code += '[/engage-form_modal]';
	 	}
	 	$('#engagef_forms_shortcode_modal')[0].reset();
	 	$('.modal-forms-setup').hide();
	 	form.prop('checked', false);	 	
		window.send_to_editor(code);
		$('.engage-modal-closer').trigger('click');

	});
	
	if( typeof wp !== 'undefined' && typeof wp.media !== 'undefined' && $("#engage-forms-form-insert").length != 0 ){

		var media = wp.media;
		if( typeof wp.mce.views.register === "function"){
			wp.mce.views.register( 'engage-form', {
				template: media.template( 'editor-engage-forms' ),
				initialize: function() {					
					this.fetch();
				},
				setLoader: function() {
					this.setContent(
						'<div class="loading-placeholder">' +
							'<div class="dashicons dashicons-update" style="color:#a3be5f;"></div>' +
							'<div class="wpview-loading"><ins style="background-color:#a3be5f;"></ins></div>' +
						'</div>'
					);
				},
				fetch: function() {
					var self = this,
						data = {
						post_id: $('#post_ID').val(),
						content : self.shortcode.content,
						atts: self.shortcode.attrs
					};

					wp.ajax.post( 'ef_get_form_preview', data )
					.done( function( response ) {
						self.render( response.html );
					} )
					.fail( function( response ) {
						self.render( response.html );
					} );
				},
				edit: function( node ) {
                    var values = this.shortcode.attrs.named;
                    	values.content = this.shortcode.content;

					jQuery('#engage-forms-form-insert').data('settings', values ).trigger('click');
				}
			} );
		}
	}

});//
