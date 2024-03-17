function engage_forms_sendwp_remote_install() {
    var data = {
        'action': 'engage_forms_sendwp_remote_install',
        'sendwp_nonce': sendwp_vars.nonce
    };
    
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
        var data = JSON.parse(response);
         
         if( data.error ){

            var element = jQuery("#ef-email-settings-ui"),
            inner = '<div class="notice error"></div>',
            message;

            if( data.debug === '!security'){
                message = sendwp_vars.security_failed_message;
            } else if( data.debug === '!user_capablity'){
                message = sendwp_vars.user_capability_message;
            } else if( data.debug === 'sendwp_connected'){
                message = sendwp_vars.sendwp_connected_message;
            }

            jQuery(element).prepend( jQuery( inner ).text(message) );

        } else {

            engage_forms_sendwp_register_client(data.register_url, data.client_name, data.client_secret, data.client_redirect, data.partner_id, data.client_url);

        }
       
    });
}

function engage_forms_sendwp_register_client(register_url, client_name, client_secret, client_redirect, partner_id, client_url) {

    var form = document.createElement("form");
    form.setAttribute("method", 'POST');
    form.setAttribute("action", register_url);

    function engage_forms_sendwp_append_form_input(name, value) {
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", name);
        input.setAttribute("value", value);
        form.appendChild(input);
    }

    engage_forms_sendwp_append_form_input('client_name', client_name);    
    engage_forms_sendwp_append_form_input('client_secret', client_secret);    
    engage_forms_sendwp_append_form_input('partner_id', partner_id);
    engage_forms_sendwp_append_form_input('client_redirect', client_redirect);
    engage_forms_sendwp_append_form_input('client_url', client_url);

    document.body.appendChild(form);
    form.submit();
}
