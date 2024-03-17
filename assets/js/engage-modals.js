(function($){
 	
 	var engageBackdrop = null,
 		engageModals 	= {},
 		activeModals 	= [],
 		activeSticky	= [],
		pageHTML		= $('html'),
		pageBody		= $('body'),
 		mainWindow 		= $(window);

	var positionModals = function(){

		if( !activeModals.length && !activeSticky.length ){
			return;
		}
		var modalId	 = ( activeModals.length ? activeModals[ ( activeModals.length - 1 ) ] : activeSticky[ ( activeSticky.length - 1 ) ] ),
			windowWidth  = mainWindow.width(),
			windowHeight = mainWindow.height(),
			modalHeight  = engageModals[ modalId ].config.height,
			modalOuterHeight  = modalHeight,
			modalWidth  = engageModals[ modalId ].config.width,
			top 		 = 0,
			flickerBD	 = false,
			modalReduced = false;

		if( engageBackdrop ){ pageHTML.addClass('has-engage-modal'); }

		// top
		top = (windowHeight - engageModals[ modalId ].config.height ) / 2.2;

		if( top < 0 ){
			top = 0;
		}
		if( modalHeight + ( engageModals[ modalId ].config.padding * 2 ) > windowHeight && engageBackdrop ){
			modalHeight = windowHeight - ( engageModals[ modalId ].config.padding * 2 );
			modalOuterHeight = '100%';
			if( engageBackdrop ){ 
				engageBackdrop.css( {
					paddingTop: engageModals[ modalId ].config.padding,
					paddingBottom: engageModals[ modalId ].config.padding,
				});
			}
			modalReduced = true;
		}
		if( modalWidth + ( engageModals[ modalId ].config.padding * 2 ) >= windowWidth ){
			modalWidth = '100%';
			if( engageBackdrop ){ 
				engageBackdrop.css( {
					paddingLeft: engageModals[ modalId ].config.padding,
					paddingRight: engageModals[ modalId ].config.padding,
				});
			}
			modalReduced = true;
		}

		if( true === modalReduced ){
			if( windowWidth <= 700 && windowWidth > 600 ){
				if( engageBackdrop ){ modalHeight = windowHeight - ( engageModals[ modalId ].config.padding * 2 ); }
				modalWidth = windowWidth;
				modalOuterHeight = modalHeight - ( engageModals[ modalId ].config.padding * 2 );
				modalWidth = '100%';
				top = 0;
				if( engageBackdrop ){ engageBackdrop.css( { padding : engageModals[ modalId ].config.padding } ); }
			}else if( windowWidth <= 600 ){
				if( engageBackdrop ){ modalHeight = windowHeight; }
				modalWidth = windowWidth;
				modalOuterHeight = '100%';
				top = 0;
				if( engageBackdrop ){ engageBackdrop.css( { padding : 0 } ); }
			}
		}


		// set backdrop
		if( engageBackdrop && engageBackdrop.is(':hidden') ){
			flickerBD = true;
			engageBackdrop.show();
		}
		// title?
		if( engageModals[ modalId ].header ){
			if( engageBackdrop ){ engageBackdrop.show(); }
			modalHeight -= engageModals[ modalId ].header.outerHeight();
			engageModals[ modalId ].closer.css( { 
				padding		: ( engageModals[ modalId ].header.outerHeight() / 2 ) - 0.5
			} );
			engageModals[ modalId ].title.css({ paddingRight: engageModals[ modalId ].closer.outerWidth() } );
		}
		// footer?
		if( engageModals[ modalId ].footer ){
			if( engageBackdrop ){ engageBackdrop.show(); }
			modalHeight -= engageModals[ modalId ].footer.outerHeight();			
		}

		if( engageBackdrop && flickerBD === true ){
			engageBackdrop.hide();
			flickerBD = false;
		}

		// set final height
		if( modalHeight != modalOuterHeight ){
			engageModals[ modalId ].body.css( { 
				height		: modalHeight			
			} );
		}
		engageModals[ modalId ].modal.css( {
			width		: modalWidth	
		} );
		
		if( engageModals[ modalId ].config.sticky && engageModals[ modalId ].config.minimized ){
			var toggle = {},
				minimizedPosition = engageModals[ modalId ].title.outerHeight() - engageModals[ modalId ].modal.outerHeight();
			if( engageModals[ modalId ].config.sticky.indexOf( 'bottom' ) > -1 ){
				toggle['margin-bottom'] = minimizedPosition;
			}else if( engageModals[ modalId ].config.sticky.indexOf( 'top' ) > -1 ){
				toggle['margin-top'] = minimizedPosition;
			}
			engageModals[ modalId ].modal.css( toggle );
			if( engageModals[ modalId ].config.sticky.length >= 3 ){
				pageBody.css( "margin-" + engageModals[ modalId ].config.sticky[0] , engageModals[ modalId ].title.outerHeight() );
				if( modalReduced ){
					engageModals[ modalId ].modal.css( engageModals[ modalId ].config.sticky[1] , 0 );
				}else{
					engageModals[ modalId ].modal.css( engageModals[ modalId ].config.sticky[1] , parseFloat( engageModals[ modalId ].config.sticky[2] ) );
				}
			}
		}
		if( engageBackdrop ){
			engageModals[ modalId ].modal.css( {
				marginTop 	: top,
				height		: modalOuterHeight
			} );
			setTimeout( function(){
				engageModals[ modalId ].modal.addClass( 'engage-animate' );
			}, 10);

			engageBackdrop.fadeIn( engageModals[ modalId ].config.speed );
		}
		engageModals[ modalId ].resize = positionModals;
		return engageModals; 
	}

	var closeModal = function( obj ){	
		var modalId = $(obj).data('modal'),
			position = 0,
			toggle = {};

		if( obj && engageModals[ modalId ].config.sticky ){

			if( engageModals[ modalId ].config.minimized ){
				engageModals[ modalId ].config.minimized = false
				position = 0;
			}else{
				engageModals[ modalId ].config.minimized = true;
				position = engageModals[ modalId ].title.outerHeight() - engageModals[ modalId ].modal.outerHeight();
			}
			if( engageModals[ modalId ].config.sticky.indexOf( 'bottom' ) > -1 ){
				toggle['margin-bottom'] = position;
			}else if( engageModals[ modalId ].config.sticky.indexOf( 'top' ) > -1 ){
				toggle['margin-top'] = position;
			}
			engageModals[ modalId ].modal.stop().animate( toggle , engageModals[ modalId ].config.speed );
			return;
		}
		var lastModal;
		if( activeModals.length ){
			
			lastModal = activeModals.pop();
			if( engageModals[ lastModal ].modal.hasClass( 'engage-animate' ) && !activeModals.length ){
				engageModals[ lastModal ].modal.removeClass( 'engage-animate' );
				setTimeout( function(){
					engageModals[ lastModal ].modal.remove();
					delete engageModals[ lastModal ];
				}, 500 );
			}else{
				if( engageBackdrop ){
					engageModals[ lastModal ].modal.hide( 0 , function(){
						$( this ).remove();
						delete engageModals[ lastModal ];
					});
				}
			}

		}

		if( !activeModals.length ){
			if( engageBackdrop ){ 
				engageBackdrop.fadeOut( 250 , function(){
					$( this ).remove();
					engageBackdrop = null;
				});
			}
			pageHTML.removeClass('has-engage-modal');
		}else{			
			engageModals[ activeModals[ ( activeModals.length - 1 ) ] ].modal.show();
		}

	}
	$.engageModal = function(opts){
		var defaults    = $.extend(true, {
			element				:	'div',
			height				:	550,
			width				:	620,
			padding				:	12,
			speed				:	250
		}, opts );
		if( !engageBackdrop && ! defaults.sticky ){
			engageBackdrop = $('<div>', {"class" : "engage-backdrop"});
			if( ! defaults.focus ){
				engageBackdrop.on('click', function( e ){
					if( e.target == this ){
						closeModal();
					}
				});
			}
			pageBody.append( engageBackdrop );
			engageBackdrop.hide();
		}



		// create modal element
		var modalElement = defaults.element,
			modalId = defaults.modal;

		if( activeModals.length ){

			if( activeModals[ ( activeModals.length - 1 ) ] !== modalId ){
				engageModals[ activeModals[ ( activeModals.length - 1 ) ] ].modal.hide();
			}
		}

		if( typeof engageModals[ modalId ] === 'undefined' ){
			if( defaults.sticky ){
				defaults.sticky = defaults.sticky.split(' ');
				if( defaults.sticky.length < 2 ){
					defaults.sticky = null;
				}
				activeSticky.push( modalId );
			}
			engageModals[ modalId ] = {
				config	:	defaults,
				modal	:	$('<' + modalElement + '>', {
					id					: modalId + '_engageModal',
					tabIndex			: -1,
					"ariaLabelled-by"	: modalId + '_engageModalLable',
					"class"				: "engage-modal-wrap" + ( defaults.sticky ? ' engage-sticky-modal ' + defaults.sticky[0] + '-' + defaults.sticky[1] : '' )
				})
			};
			if( !defaults.sticky ){ activeModals.push( modalId ); }
		}else{
			engageModals[ modalId ].config = defaults;
			engageModals[ modalId ].modal.empty();
		}
		// add animate		
		if( defaults.animate && engageBackdrop ){
			var animate 		= defaults.animate.split( ' ' ),
				animateSpeed 	= defaults.speed + 'ms',
				animateEase		= ( defaults.animateEase ? defaults.animateEase : 'ease' );

			if( animate.length === 1){
				animate[1] = 0;
			}

			engageModals[ modalId ].modal.css( { 
				transform				: 'translate(' + animate[0] + ', ' + animate[1] + ')',
				'-web-kit-transition'	: 'transform ' + animateSpeed + ' ' + animateEase,
				'-moz-transition'		: 'transform ' + animateSpeed + ' ' + animateEase,
				transition				: 'transform ' + animateSpeed + ' ' + animateEase
			} );

		}
		engageModals[ modalId ].body = $('<div>', {"class" : "engage-modal-body",id: modalId + '_engageModalBody'});
		engageModals[ modalId ].content = $('<div>', {"class" : "engage-modal-content",id: modalId + '_engageModalContent'});


		// padd content		
		engageModals[ modalId ].content.css( { 
			margin : defaults.padding
		} );
		engageModals[ modalId ].body.append( engageModals[ modalId ].content ).appendTo( engageModals[ modalId ].modal );
		if( engageBackdrop ){ engageBackdrop.append( engageModals[ modalId ].modal ); }else{
			engageModals[ modalId ].modal . appendTo( $( 'body' ) );
		}


		if( defaults.footer ){
			engageModals[ modalId ].footer = $('<div>', {"class" : "engage-modal-footer",id: modalId + '_engageModalFooter'});
			engageModals[ modalId ].footer.css({ padding: defaults.padding });
			engageModals[ modalId ].footer.appendTo( engageModals[ modalId ].modal );
			// function?
			if( typeof window[defaults.footer] === 'function' ){
				engageModals[ modalId ].footer.append( window[defaults.footer]( opts, this ) );
			}else if( typeof defaults.footer === 'string' ){
				// is jquery selector?
				  try {
				  	var footerElement = $( defaults.footer );
				  	engageModals[ modalId ].footer.html( footerElement.html() );
				  } catch (err) {
				  	engageModals[ modalId ].footer.html( defaults.footer );
				  }
			}
		}

		if( defaults.title ){
			var headerAppend = 'prependTo';
			engageModals[ modalId ].header = $('<div>', {"class" : "engage-modal-title", id : modalId + '_engageModalTitle'});
			engageModals[ modalId ].closer = $('<a>', { "href" : "#close", "class":"engage-modal-closer", "data-dismiss":"modal", "aria-hidden":"true",id: modalId + '_engageModalCloser'}).html('&times;');
			engageModals[ modalId ].title = $('<h3>', {"class" : "modal-label", id : modalId + '_engageModalLable'});
			
			engageModals[ modalId ].title.html( defaults.title ).appendTo( engageModals[ modalId ].header );
			engageModals[ modalId ].title.css({ padding: defaults.padding });
			engageModals[ modalId ].title.append( engageModals[ modalId ].closer );
			if( engageModals[ modalId ].config.sticky ){
				if( engageModals[ modalId ].config.minimized && true !== engageModals[ modalId ].config.minimized ){
					setTimeout( function(){
						engageModals[ modalId ].title.trigger('click');
					}, parseInt( engageModals[ modalId ].config.minimized ) );
					engageModals[ modalId ].config.minimized = false;
				}
				engageModals[ modalId ].closer.hide();
				engageModals[ modalId ].title.addClass( 'engage-modal-closer' ).data('modal', modalId).appendTo( engageModals[ modalId ].header );
				if( engageModals[ modalId ].config.sticky.indexOf( 'top' ) > -1 ){
					headerAppend = 'appendTo';
				}
			}else{
				engageModals[ modalId ].closer.data('modal', modalId).appendTo( engageModals[ modalId ].header );
			}
			engageModals[ modalId ].header[headerAppend]( engageModals[ modalId ].modal );
		}
		// hide modal
		engageModals[ modalId ].modal.outerHeight( defaults.height );
		engageModals[ modalId ].modal.outerWidth( defaults.width );

		if( defaults.content ){
			// function?
			if( typeof defaults.content === 'function' ){
				engageModals[ modalId ].content.append( defaults.content( opts, engageModals[ modalId ], this ) );
			}else if( typeof window[defaults.content] === 'function' ){
				engageModals[ modalId ].content.append( window[defaults.content]( opts, engageModals[ modalId ], this ) );
			}else if( typeof defaults.content === 'string' ){
				// is jquery selector?
				  try {
				  	var contentElement = $( defaults.content );
				  	if( contentElement.length ){
				  		engageModals[ modalId ].content.append( contentElement.detach() );
						contentElement.show();
				  	}else{
				  		engageModals[ modalId ].content.html( defaults.content );
				  	}
				  } catch (err) {
				  	engageModals[ modalId ].content.html( defaults.content );
				  }
			}
		}

		// set position;
		positionModals();
		// return main object
		return engageModals[ modalId ];
	}

	$.fn.engageModal = function( opts ){
		if( !opts ){ opts = {}; }
		opts = $.extend( {}, this.data(), opts );
		return $.engageModal( opts );
	}

	// setup resize positioning and keypresses
    if ( window.addEventListener ) {
        window.addEventListener( "resize", positionModals, false );
        window.addEventListener( "keypress", function(e){
        	if( e.keyCode === 27 && engageBackdrop !== null ){
        		engageBackdrop.trigger('click');
        	}
        }, false );

    } else if ( window.attachEvent ) {
        window.attachEvent( "onresize", positionModals );
    } else {
        window["onresize"] = positionModals;
    }



	$(document).on('click', '[data-modal]:not(.engage-modal-closer)', function( e ){
		e.preventDefault();
		$(this).engageModal();
	});
	$(window).load( function(){
		$('[data-modal][data-autoload]').each( function(){
			$( this ).engageModal();
		});
	});

	$(document).on( 'click', '.engage-modal-closer', function( e ) {
		e.preventDefault();
		closeModal( this );
	})


})(jQuery);
