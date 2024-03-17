var calders_forms_check_conditions, calders_forms_init_conditions;
jQuery(function($){

	/**
	 * Stores field values before hiding with conditional logic
	 *
	 * @since 1.5.0.7
	 *
	 * @type {{}}
     */
	var fieldVals = {};

    /**
	 * Tracks fields that are set to "unsync" and have been hidden
	 *
	 * @since 1.6.0
	 *
     * @type {{}}
     */
	var unsynced = {};

	// IE8 compatibility
	if (!Array.prototype.indexOf){
		Array.prototype.indexOf = function(elt /*, from*/){
			var len = this.length >>> 0;

			var from = Number(arguments[1]) || 0;
			from = (from < 0)
			? Math.ceil(from)
			: Math.floor(from);
			if (from < 0)
				from += len;

			for (; from < len; from++){
				if (from in this &&
					this[from] === elt)
					return from;
			}
			return -1;
		};
	}
	ef_debounce = function(func, wait, immediate) {
		var timeout;		
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	};

	calders_forms_check_conditions = function( inst_id ){

		if( typeof engage_conditionals === "undefined" || typeof engage_conditionals[inst_id] === "undefined"){
			return;
		}


		var $form = $( document.getElementById( inst_id ) );
		var state = getStateObj( inst_id );

		/**
		 * Reset field value after its unhidden
		 *
		 * @since 1.5.0.7
		 *
		 * @param field Field ID
		 * @param state {EFState} @since 1.5.3
		 */
		function resetValue( field, state ){
			var val = getSavedFieldValue( field );
			var $field;
			if( undefined != val ){
				if( 'object' == typeof  val  ){
					for( var id in val ){
						if( true === val[id] ){
							$field = $( document.getElementById( id ) );
							$field.prop( 'checked', true );
						}
					}
				}else{
					$field = $( '#' + field );
					$field.val( val );
				}
			}

			if( null !== state ){
				state.rebind(field);
				if( undefined === $field ){
                    $field = $( '#' + field );
				}

				if( unsynced.hasOwnProperty( field ) ){
                    $field.attr( 'data-unsync', '1' );
                    $field.removeAttr( 'data-sync' );
                    $field.removeAttr( 'data-binds' );
				}

                if ( undefined !== $field && $field.data( 'sync' ) ) {
                    new EngageFormsFieldSync($field, $field.data('binds'), $form, $, state);
                }
			}

		}


		/**
		 * Reset field value before its unhidden
		 *
		 * @since 1.5.0.7
		 *
		 * @param field Field ID
		 * @param state {EFState} @since 1.5.3
		 *
		 * @return mixed saved value @since 1.8.0
		 */
		function saveFieldValue(field,state) {
			var $field = $( document.getElementById( field ) );
			if( $field.length ){
				var val = $field.val();
				if( val ){
					fieldVals[ field ] = val;

				}

			}else{
				var $el;
				$field = $( '.' + field );
				fieldVals[ field ] = {};
				$field.each( function( i, el ){
					$el = $( el );
					if( $el.prop( 'checked' ) ){
						fieldVals[ field ][ $el.attr( 'id' ) ] = true;
					}else{
						fieldVals[ field ][ $el.attr( 'id' ) ] = false;
					}

				});
			}

			if( $field.data( 'unsync' ) ){
				unsynced[ field ] = true;
			}

			//remove from state
			if ( null !== state ) {
				state.unbind(field);
			}

			return fieldVals[ field ];

		}

		/**
		 * Get saved field value
		 *
		 * @since 1.5.0.7
		 *
		 * @param field Field ID
		 *
         * @returns {*}
         */
		function getSavedFieldValue( field ){
			if(fieldVals[ field ]  ){
				return fieldVals[ field ];
			}
		}

		for( var field in engage_conditionals[ inst_id ] ){
			// each conditional
			var fieldwrapper = jQuery('#conditional_' + field);
			if(!fieldwrapper.length){
				continue;
			}
			var type	=	engage_conditionals[ inst_id ][field].type,
			groups	=	engage_conditionals[ inst_id ][field].group,
			trues	=	[];
			
			// has a wrapper - bind conditions
			for(var id in groups){
				
				var truelines	= {},
				lines		= groups[id];						
				// go over each line in a group to find a false
				for(var lid in lines){					
					/// get field 

					var compareelement 	= $form.find('[data-field="' + lines[lid].field + '"]'),
					comparefield 	= [],
					comparevalue	= (typeof lines[lid].value === 'function' ? lines[lid].value() : lines[lid].value);
					
					if( typeof lines[lid].selectors !== 'undefined' ){
						for( var selector in lines[lid].selectors ){
							var re = new RegExp( selector ,"g");
							comparevalue = comparevalue.replace( re, $( lines[lid].selectors[ selector ] ).val() );
						}
					}

					truelines[lid] 	= false;
					if( compareelement.is(':radio,:checkbox') ){
						compareelement = compareelement.filter(':checked');
					}else if( compareelement.is('div')){
						compareelement = jQuery('<input>').val( compareelement.html() );
					}else if ( ! compareelement.length ){
						var _calc = $form.find('[data-calc-field="' + lines[lid].field + '"]');
						if( _calc.length ){
							compareelement 	= $form.find('[data-calc-field="' + lines[lid].field + '"]');
						}
					}
					
					if(!compareelement.length){
						comparefield.push(lines[lid].field);
					}else{
						for( var i = 0; i<compareelement.length; i++){							
							comparefield.push(compareelement[i].value);
						}
					}
					switch(lines[lid].compare) {
						case 'is':
						if(comparefield.length){
							if(comparefield.indexOf(comparevalue.toString()) >= 0){
								truelines[lid] = true;
							}
						}
						break;
						case 'isnot':
						if(comparefield.length){
							if(comparefield.indexOf(comparevalue) < 0){
								truelines[lid] = true;
							}
						}
						break;
						case '>':
						case 'greater':

							truelines[lid] = parseFloat( comparefield.reduce(function(a, b) {return a + b;}) ) > parseFloat( comparevalue );

						break;
						case '<':
						case 'smaller':

							truelines[lid] = parseFloat( comparefield.reduce(function(a, b) {return a + b;}) ) < parseFloat( comparevalue );

						break;
						case 'startswith':
						for( var i = 0; i<comparefield.length; i++){
							if( comparefield[i].toLowerCase().substr(0, comparevalue.toLowerCase().length ) === comparevalue.toLowerCase()){
								truelines[lid] = true;
							}
						}
						break;
						case 'endswith':
						for( var i = 0; i<comparefield.length; i++){
							if( comparefield[i].toLowerCase().substr(comparefield[i].toLowerCase().length - comparevalue.toLowerCase().length ) === comparevalue.toLowerCase()){
								truelines[lid] = true;
							}
						}
						break;
						case 'contains':
						for( var i = 0; i<comparefield.length; i++){
							if( comparefield[i].toLowerCase().indexOf( comparevalue ) >= 0 ){
								truelines[lid] = true;
							}
						}
						break;
					}
				}				
				// add result in
				istrue = true;
				for( var prop in truelines ){
					if(truelines[prop] === false){
						istrue = false;
						break;
					}
				}
				trues.push(istrue);

			}


			var template	=	jQuery('#conditional-' + field + '-tmpl').html(),
			target		=	jQuery('#conditional_' + field),
			target_field=	jQuery('[data-field="' + field + '"]'),
			action;
			if(trues.length && trues.indexOf(true) >= 0){					
				if(type === 'show'){
					action = 'show';
				}else if (type === 'hide'){
					action = 'hide';
				}else if (type === 'disable'){
					action = 'disable';
				}
			}else{
				if(type === 'show'){
					action = 'hide';
				}else if (type === 'disable'){
					action = 'enable';
				}else{
					action = 'show';
				}
			}


			if(action === 'show'){
				// show - get template and place it in.
				if(!target.html().length){

					target.html(template).trigger('ef.add', {
						field: field,
					});
					jQuery(document).trigger('ef.add',{
						field: field,
					});
					resetValue( field, state );

				}

				emitConditionalEvent('show', field, inst_id );

			}else if (action === 'hide'){
				if(target.html().length){
					saveFieldValue(  field, state  );
					target_field.val('').empty().prop('checked', false);
					target.empty().trigger('ef.remove',{
						field: field,
					});
						jQuery(document).trigger('ef.remove',{
						field: field,
					});
				}

				emitConditionalEvent('hide', field, inst_id );

			}else if ('enable' === action || 'disable' === action ){
				var dField = jQuery( '#' + field );
				if( 'enable' == action ){
					if(!target.html().length){
						target.html(template).trigger('ef.add',{
							field: field,
						});
						jQuery(document).trigger('ef.add').trigger('ef.enable', {
							field: field,
						});
						dField.prop('disabled', false);
					}else{
						dField.prop('disabled', false);
					}


					emitConditionalEvent('enable', field, inst_id );


				}else {
					if (!target.html().length) {
						target.html(template).trigger('ef.remove');
						jQuery(document).trigger('ef.remove',{
							field: field,
						})
						.trigger('ef.disable', {
							field: field,
						});
						dField.prop('disabled', 'disabled', {
							field: field,
						});
					} else {
						dField.prop('disabled', 'disabled',{
							field: field,
						});
					}
					emitConditionalEvent('disable', field, inst_id );


				}

			}

		}

		/**
		 * Get the EFState object by form ID
		 *
		 * @since 1.5.3
		 *
		 * @param {String} formId Form ID
		 * @returns {EFState|null}
		 */
		function getStateObj( formId ) {
			if( 'object' === typeof  window.efstate && window.efstate.hasOwnProperty(formId) ){
				return  window.efstate[formId];
			}

			return null;
		}

		function emitConditionalEvent(eventName,field,formId){
			function createEventName(){
				return 'ef.conditionals.' + eventName;
			}
			var state = getStateObj(formId);
			if( state ){
				state.events().emit(createEventName(), {
					fieldIdAttr: field,
					formIdAttr: formId,
					eventType: eventName,
					fieldValue: getSavedFieldValue(field)
				} );
			}
		}

	};

	calders_forms_init_conditions = function(){
		jQuery('.engage_forms_form').on('change keyup', '[data-field]', ef_debounce( function(e){
			var form 			= $(this).closest('.engage_forms_form').prop('id');
			calders_forms_check_conditions( form );
		}, 10 ) );	
	};

	if(typeof engage_conditionals !== 'undefined'){
		calders_forms_init_conditions();
		jQuery('.engage_forms_form').find('[data-field]').first().trigger('change');
	};
});