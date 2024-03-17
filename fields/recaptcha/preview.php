<div class="notice notice-error"><?php
	printf( '<p>%s <a href="https://engageforms.com/doc/recaptcha-field/" title="%s" target="_blank">%s</a></p>',
		esc_html__( 'This field type is discontinued.', 'engage-forms' ),
		esc_attr__( 'Documenation for this field type', 'engage-forms' ),
		esc_html__( 'Click here for more information.', 'engage-forms' )
	);

	?>
</div>
{{#if config/public_key}}
	{{#if config/private_key}}
		<div id="cap{{id}}" class="g-recaptcha" data-sitekey="{{config/public_key}}" data-theme="{{config/theme}}"></div>
		{{#script}}

		jQuery(document).ready( function(){
			if( typeof grecaptcha === 'object' ){
				
				var captch = jQuery('#cap{{id}}');

				grecaptcha.render( captch[0], {
					"sitekey"	:	"{{config/public_key}}",
					"theme"		:	"{{config/theme}}"
				});
			}
		});

		{{/script}}
	{{else}}
		<p><?php esc_html_e( 'No Secret Key Added', 'engage-forms' ); ?></p>
	{{/if}}
{{else}}
	<p><?php esc_html_e( 'No Site Key Added', 'engage-forms'); ?></p>
{{/if}}
