jQuery( document ).ready( function () {

    //html-actions
    //Action
    jQuery('.bm-actions-select').select2({
        placeholder: "Select Actions"
    });

    //Generate API Key
    jQuery( document ).on( 'click', '.bm-generate-api-key', function( e ) {

        e.preventDefault();
        security = jQuery( '.bm-security' ).val();
        jQuery.ajax( {
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'bm-generate-api-key',
                _nonce: security
            },
            beforeSend: function() {
                jQuery( '.bm-generate-api-key .bm-loader' ).css( { 'display': 'inline-block' } );
            },
            success: function( response ) {
                jQuery( '.bm-api-key' ).val( response.data );
            },
            complete: function() {
                jQuery( '.bm-generate-api-key .bm-loader' ).css( { 'display': 'none' } );
            },
        } );

    } );

    //html-triggers
    //Actions
    jQuery('.bm-triggers-select').select2({
        placeholder: "Select Trigger"
    });

    //Select Site
    jQuery('.bm-triggers-site-select').select2({
        placeholder: "Select Site"
    });

    //Get actions from API
    jQuery( document ).on( 'change', '.bm-triggers-site-select', function () {

        var selectedSite = jQuery( this ).find( ':selected' );
        var apiKey = jQuery( selectedSite ).val();
        var baseURL = jQuery( selectedSite ).data( 'site' );
        var security = jQuery( '.bm-security' ).val();

        jQuery.ajax( {
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'bm-get-actions',
                api_key: apiKey,
                base_url: baseURL,
                _nonce: security
            },
            beforeSend: function() {

                jQuery( '.bm-triggers-action-select' ).html( '<option>Fetching Actions...</option>' );

            },
            success: function( response ) {

                jQuery( '.bm-triggers-action-select' ).html( `<option>Select Action</option>` );
                jQuery.each( response.data, function ( index, value ){
                    jQuery( '.bm-triggers-action-select' ).append( `<option value="${value['id']}" data-api="${apiKey}" data-site="${baseURL}">${value['title']}</option>` );
                } )

            },
            error: function( response ) {

                jQuery( '.bm-triggers-action-select' ).html( 'Check Console by pressing F12' );
                console.log( response );

            },
        } );

    } );

    //Get action fields from API
    jQuery( document ).on( 'change', '.bm-triggers-action-select', function() {

        var selectedTrigger = jQuery( this ).find( ':selected' );
        var selectedAction = jQuery( '.bm-triggers-select' ).find( ':selected' ).val();
        var apiKey = jQuery( selectedTrigger ).data( 'api' );
        var baseURL = jQuery( selectedTrigger ).data( 'site' );
        var bmAction = jQuery( selectedTrigger ).val();
        var security = jQuery( '.bm-security' ).val();

        jQuery.ajax( {
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'bm-get-action-fields',
                api_key: apiKey,
                base_url: baseURL,
                bm_action: bmAction,
                selected_action: selectedAction,
                _nonce: security
            },
            success: function ( response ) {

                var bmAction = response.action.data;
                var rowCount = 0;

                jQuery( '.fetched-action-fields' ).val( JSON.stringify( response.action.data ) );
                jQuery( '.fetched-trigger-options' ).val( JSON.stringify( response.trigger ) );
                jQuery( '.bm-trigger-found' ).html( 'When Trigger happen, Insert field to Action Input' );
                jQuery( '.bm-saved-automation-rows' ).empty();

                var bmTriggerFields = `
                <option value="">
                    Select Trigger Field
                </option>
                `;
                var bmTrigger = response.trigger;

                jQuery.each( bmTrigger, function( index, value ){

                    //Gather Options
                    var name = index.replace( '$', '' );
                    bmTriggerFields += `
                    <option value="${name}">
                        {${name}} - ${value}
                    </option>
                    `;

                } );

                //Render Trigger Fields
                jQuery.each( bmAction, function( index, value ) {

                    var lastRow = jQuery( '.bm-trigger-row' );
                    var totalRows = lastRow.length;
                    lastRow = lastRow[totalRows - 1];

                    var name = index.replace( '$', '' );
                    var label = name.replace( /_/g, ' ' );

                    if( rowCount == 0 ) {

                        jQuery( lastRow ).after(
                            `<tr class="bm-trigger-row">`
                        );

                    }

                    lastRow = jQuery( '.bm-trigger-row' );
                    totalRows = lastRow.length;
                    lastRow = lastRow[totalRows - 1];

                    jQuery( lastRow ).append(
                        `<td>
                            <div><label for="">${label}</label></div>
                            <select class="bm-action-field ${name}" style="width: 100%;" name="bm_trigger_action[${name}]">
                                ${bmTriggerFields}
                            </select>
                            <div><sup>${value.description}</sup></div>
                        </td>`
                    );


                    rowCount = rowCount + 1;

                    if( rowCount == 3 ) {

                        jQuery( lastRow ).after(
                            `</tr>`
                        );

                        rowCount = 0;

                    }


                } );

            }
        } );

    } );

    jQuery( '.bm-action-field' ).select2();

    //Select Action
    jQuery('.bm-triggers-action-select').select2({
        placeholder: "Select Site to get Actions"
    });

    /*Logs Page Starts*/
    jQuery( document ).on( 'click', '.bm-session-transcript', function (){

        var data = jQuery( this ).data( 'data' );
        jQuery( this ).siblings( '.bm-show-session-transcript' ).text( data );

    } );
    /*Logs Page Ends*/

} );