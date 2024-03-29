/**
 * API Client for Engage Forms API for a single form
 *
 * @since 1.5.0
 *
 * @param routes URLs for endpoints, should have URL for /entries and /forms
 * @param perPage How many items to return for page
 * @param formId Form ID
 * @param tokens Either WordPress REST API authentication nonce as string, or object with index nonce and token (token is Engage Forms Entry Token)
 * @param $ jQuery
 *
 * @returns {{getForm: getForm, getEntries: getEntries, paginatedEntryURL: paginatedEntryURL, setPerPage: setPerPage}}
 *
 * @constructor
 */
function EFAPI( routes, perPage, formId, tokens,  $ ) {
    var nonce, token;
    if( 'object' == typeof  tokens ){
        nonce = typeof  tokens.nonce == 'string' ?  tokens.nonce : false;
        token = typeof  tokens.nonce == 'string' ?  tokens.token : false;
    }else{
        nonce = tokens;
    }

    function addHeaders( xhr ){
        xhr.setRequestHeader( 'X-EF-ENTRY-TOKEN', token );
        xhr.setRequestHeader( 'X-WP-Nonce', nonce );
    }


    return {
        getForm: function () {
            return $.ajax({
                url: routes.form + formId,
                method: 'GET',
                beforeSend: function ( xhr ) {
                    addHeaders( xhr );
                }
            }).done(function (r) {
                return r;
            }).fail(function (r) {
                console.log(r);
            });
        },
        getEntries: function ( page ) {
            return $.ajax({
                url: this.paginatedEntryURL(formId, page, perPage ),
                method: 'GET',
                beforeSend: function ( xhr ) {
                    addHeaders( xhr );
                }
            } ).done(function (r) {
                return r;
            }).fail(function (r) {
                console.log(r);
            });
        },
        paginatedEntryURL: function (formId, page ) {
            var params = $.param({
                page: page,
                per_page: perPage
            });

            //If pretty permalinks are enabled params need to be prefixed with "?"
            //Else there already is a "?" so we need to add a "&"
            //@see https://github.com/EngageWP/Engage-Forms/pull/3576#issuecomment-655563315
            var divider = routes.entries.indexOf('?') === -1 ? '?' : '&';
            return routes.entries + formId + divider + params
        },
        setPerPage : function( newPerPage ) {
            perPage = newPerPage;
        },
        getPerPage :function () {
            return perPage;
        },
        savePerPage: function(){
            return $.ajax({
                url: routes.entrySettings,
                method: 'POST',
                dataType: 'json',
                beforeSend: function ( xhr ) {
                    addHeaders( xhr );
                },
                data:{
                    per_page: perPage
                }
            }).done( function( r ){
                return r.per_page;
            }).fail( function( r ){
                console.log(r);
            })

        },


    }
}
