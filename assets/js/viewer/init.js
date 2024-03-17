/**
 * Set up the entry viewer
 *
 * @since 1.5.0
 */
jQuery( function ($) {
    if( 'object' == typeof EF_ENTRY_VIEWER_2_CONFIG ){

        var formId = EF_ENTRY_VIEWER_2_CONFIG.formId;

        var tokens = {
            //REST API Nonce
            nonce: EF_ENTRY_VIEWER_2_CONFIG.api.nonce,
            //Special token for entry viewer
            token: EF_ENTRY_VIEWER_2_CONFIG.api.token
        };

        var api = new EFAPI( EF_ENTRY_VIEWER_2_CONFIG.api, EF_ENTRY_VIEWER_2_CONFIG.perPage, formId, tokens, $ );
        $.when( api.getForm(), api.getEntries(1) ).then( function( d1, d2 ){
            var form = d1[0];

            var entries = d2[0];
            var formStore = new EFFormStoreFactory( formId, form.field_details.order, form.field_details.entry_list );
            var entriesStore = new EFEntriesStoreFactory( formId, entries );
            entriesStore.setPage(1);
            if( null != d2[2].getResponseHeader( 'X-EF-API-TOTAL-PAGES')  ){
                entriesStore.setTotalPages(d2[2].getResponseHeader( 'X-EF-API-TOTAL-PAGES' ) );
            }
            if( null != d2[2].getResponseHeader( 'X-EF-API-TOTAL' ) ){
                entriesStore.setTotal( d2[2].getResponseHeader( 'X-EF-API-TOTAL' ) );
            }
            var viewer = new EFEntryViewer2( formId, formStore, entriesStore, api, EF_ENTRY_VIEWER_2_CONFIG );

        }, function(r){
            var entriesId = typeof EF_ENTRY_VIEWER_2_CONFIG.targets == 'object' && typeof EF_ENTRY_VIEWER_2_CONFIG.targets.entries == 'string' ? EF_ENTRY_VIEWER_2_CONFIG.targets.entries : 'engage-forms-entries';
            var navId = typeof EF_ENTRY_VIEWER_2_CONFIG.targets == 'object' && typeof EF_ENTRY_VIEWER_2_CONFIG.targets.nav == 'string' ? EF_ENTRY_VIEWER_2_CONFIG.targets.nav : 'engage-forms-entries-nav';
            jQuery('#' + navId).remove();
            if ( 'object' == typeof r && 404 == r.status  ) {
                jQuery('#' + entriesId).html('<div class="alert alert-error">' + EF_ENTRY_VIEWER_2_CONFIG.strings.no_entries + '</div>');
            }else{
                jQuery('#' + entriesId).html('<div class="alert alert-error">' + EF_ENTRY_VIEWER_2_CONFIG.strings.not_allowed + '</div>');

            }
        });

    }

});
