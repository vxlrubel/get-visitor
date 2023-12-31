;(($)=>{
    const doc             = $(document);
    const form            = $('.add-new-visitor form');
    const apiUrl          = GV.api_url;
    const ajaxUrl         = GV.ajax_url;
    const apiSettingsUrl  = GV.api_settings_url;
    const deactivationUrl = GV.deactivation_link;
    const nonce           = GV.nonce;
    const listParent      = $('.get-visitor-list-parent');
    const settings        = $('.gv-settings');
    class GV_ADMIN_SCRIPT{
        constructor(){
            this.tabs();
            this.addNewVisitor();
            this.deleteVisitor();
            this.fetchSingleItem();
            this.updateVisitor();
            this.destroyPopup();
            this.destroyNotice();
            this.updateGeneralSettings();
            this.resetGeneralSettings();
            this.copyShortcode();
            this.updateOptions();
            this.deleteMultipleVisitor();
            this.exportData();
            this.deactivation();
        }

        deactivation(){
            this.execute_popup_message();
        }

        execute_popup_message(){

            // open popup
            $('[data-slug="get-visitor"]').on('click', '#deactivate-get-visitor', function(e){
                e.preventDefault();
                let _this  = $(this);
                let parent = _this.closest('div.wrap').append(
                    `
                    <div class="deactivation-popup-parent">
                        <div class="gv-plugin-deactive-popup">
                            <div class="flash-box">

                                <div class="popup-title">
                                    <span>Choose a option!</span> <span class="close" data-dismis-popup="true">✖</span>
                                </div>

                                <ul class="popup-body">
                                    <li>
                                        
                                        <label>
                                            <input type="radio" name="gv_database_table">
                                            Delete the database table
                                        </label>
                                    </li>
                                    <li>
                                        
                                        <label>
                                            <input type="radio" checked="checked" name="gv_database_table">
                                            Keep database table
                                        </label>
                                    </li>
                                </ul>

                                <div class="popup-footer">
                                    <a href="${deactivationUrl}" class="deactive">Deactivate</a>
                                    <a href="javascript:void(0);" data-dismis-popup="true">Cancel</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    `
                );

                $('.deactivation-popup-parent').fadeIn();
            });

            // destroy popup
            $('.wrap').on('click', '[data-dismis-popup]', function(e){
                e.preventDefault();
                let dismisablePopup = $(this).closest('.deactivation-popup-parent');
                dismisablePopup.fadeOut(300, ()=>{
                    dismisablePopup.remove();
                })
            });
        }

        exportData(){
            listParent.on('click', '.gv-get-csv-data', function(e){
                e.preventDefault();
                let _this = $(this);
                let text  = _this.text();
                let data  = {
                    action: 'export_get_visitors'
                }
                $.ajax({
                    type   : 'POST',
                    url    : ajaxUrl,
                    data   : data,
                    beforeSend: ()=>{
                        _this.text('Exporting...');
                    },
                    success: (response)=>{
                        let blob          = new Blob( [response] );
                        let link          = document.createElement('a');
                            link.href     = window.URL.createObjectURL(blob)
                            link.download = `get-visitors_${Math.random()}.csv`;
                        link.click();
                        _this.text( text );
                    }
                });
            });
        }

        deleteMultipleVisitor(){
            listParent.on('click', 'input#doaction[type="submit"]', function(e){
                e.preventDefault();
                let _this       = $(this);
                let text        = _this.val();
                let actionValue = _this.siblings('select#bulk-action-selector-top').val();
                let checkboxes  = listParent.find('input[name="get_visitor[]"]:checked');
                let itemValues  = checkboxes.map(
                    function(){
                        return this.value;
                    }
                ).get();
                
                if( itemValues.length === 0 ){
                    return;
                }
                
                if( actionValue !== 'delete' ){
                    return;
                }
                
                let multiple_delete_url = GV.multiple_delete;

                let headers = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }

                let data = {
                    ids : itemValues
                }
                
                $.ajax({
                    type      : 'DELETE',
                    url       : multiple_delete_url,
                    headers   : headers,
                    data      : JSON.stringify(data),
                    beforeSend: ()=>{
                        _this.val('loading...');
                    },
                    success   : (res)=>{
                        if(res){
                            checkboxes
                                .closest('tr')
                                .addClass('item-will-be-delete')
                                .fadeOut(300, ()=>{
                                    checkboxes.closest('tr').remove();
                                });
                            _this.val(text);
                        }
                    },
                    error     : ()=>{
                        alert('something went wrong.');
                    }
                });
                
            })
        }

        updateOptions(){
            settings.on('submit', '.settings-option-form', function(e){
                e.preventDefault();
                let _this      = $(this);
                let itemValue  = _this.find('#item-count').val();
                let _button    = _this.find('input[type="submit"]');
                let _text      = _button.val();
                let option_url = apiSettingsUrl + '/' + 'options';
                let headers    = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }
                let data       = {
                    list_count : itemValue
                }
                $.ajax({
                    type      : 'POST',
                    url       : option_url,
                    data      : JSON.stringify( data ),
                    headers   : headers,
                    beforeSend: ()=>{
                        _button.val('Saving...');
                    },
                    success   : (res)=>{
                        if( res ){
                            _button.val(_text);
                            _this.before( `<div class="notice notice-success is-dismissible"><p><strong>Settings updated successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);
                        }
                    },
                    error     : (err)=>{
                        if( err ){
                            _this.before( `<div class="notice notice-warning is-dismissible"><p><strong>Something went wrong.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);
                        }
                    }
                });

                

            });
        }

        copyShortcode(){
            $('.shortcode').on('click', '.copy-shortcode', function(e){
                let text = $(this).siblings('.get-shortcode').text();
                let createArea = $('<textarea>');
                createArea.val(text);
                $(this).after(createArea);
                createArea.select();
                document.execCommand('copy');
                $(this).after('<span class="copy-notice">Copied to clipboard</span>');
                createArea.remove();
                setTimeout(() => {
                    $('.copy-notice').fadeOut(300, ()=>{
                        $('.copy-notice').remove();
                    });
                }, 750);
            });
        }

        resetGeneralSettings(){
            settings.on('click', 'input.reset-general-options', function(e){
                e.preventDefault();
                
                let _this        = $(this);
                let parent_form  = _this.closest('form.settings-general-form');
                let _title       = parent_form.find('#title');
                let _desc        = parent_form.find('#desc');
                let _placeholder = parent_form.find('#placeholder');
                let _success     = parent_form.find('#success');
                let _warning     = parent_form.find('#warning');
                let reset_url    = apiSettingsUrl + '/' + 'general' + '/' + 'reset';
                let headers      = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }

                let default_data  = {
                    title      : 'Subscribe Us',
                    email      : 'Your email address will be secure with us. Your privacy is our prime concern.',
                    placeholder: 'example@domain.com',
                    success    : 'Subscribe successfully.',
                    warning    : 'Something went wrong.'
                }
                
                let data         = {
                    title         : default_data.title,
                    desc          : default_data.email,
                    placeholder   : default_data.placeholder,
                    notice_success: default_data.success,
                    notice_warning: default_data.warning
                }

                $.ajax({
                    type      : 'POST',
                    url       : reset_url,
                    data      : JSON.stringify( data ),
                    headers   : headers,
                    beforeSend: ()=>{
                        _this.val('Reseting...');
                    },
                    success   : (res)=>{
                        if( res ){
                            _this.val('Reset Default');
                            parent_form.before( `<div class="notice notice-success is-dismissible"><p><strong>Settings updated successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);

                            _title.val( res.title );
                            _desc.val( res.desc );
                            _placeholder.val( res.placeholder );
                            _success.val( res.notice_success );
                            _warning.val( res.notice_warning );
                        }
                    },
                    error     : (err)=>{
                        if( err ){
                            parent_form.before( `<div class="notice notice-warning is-dismissible"><p><strong>Something went wrong.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);
                        }
                    }
                });

                
            });
        }

        updateGeneralSettings(){
            settings.on('submit', 'form.settings-general-form', function(e){
                e.preventDefault();
                let _this      = $(this);
                let button     = _this.find('input[type="submit"]');
                let buttonText = button.val();
                let generalUrl = apiSettingsUrl + '/' + 'general';
                let data       = {
                    title         : _this.find('#title').val(),
                    desc          : _this.find('#desc').val(),
                    placeholder   : _this.find('#placeholder').val(),
                    notice_success: _this.find('#success').val(),
                    notice_warning: _this.find('#warning').val(),
                }
                let headers    = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }

                $.ajax({
                    type      : 'POST',
                    url       : generalUrl,
                    data      : JSON.stringify( data ),
                    headers   : headers,
                    beforeSend: ()=>{
                        button.val('saving...');
                    },
                    success   : (res)=>{
                        if( res ){
                            button.val(buttonText);
                            _this.before( `<div class="notice notice-success is-dismissible"><p><strong>Settings updated successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);
                        }
                    },
                    error     : (err)=>{
                        if( err ){
                            _this.before( `<div class="notice notice-success is-dismissible"><p><strong>Something went wrong.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);
                        }
                    }
                });

            });
        }
        
        tabs(){
            $("#tabs").tabs();
        }

        destroyNotice(){
            $('.wrap').on('click', 'button.notice-dismiss', function(e){
                e.preventDefault();
                let notice_item = $(this).closest('div.notice');
                notice_item.fadeOut(300, ()=>{
                    notice_item.remove();
                })
            });
        }

        destroyPopup(){
            listParent.on('click', 'span.close', function(e){
                e.preventDefault();
                let _this = $(this);
                let popup = _this.closest('.gv-get-single-item');
                popup.removeClass('popupvisible').addClass('popup-reverse');
                setTimeout((e)=>{
                    popup.remove();
                }, 300);
            });
        }

        fetchSingleItem(){
            listParent.on('click', 'a.edit-visitor-item', function(e){
                e.preventDefault();
                let _this = $(this);
                let text = _this.text();
                let id    = parseInt(_this.data('id'));
                let api_get_single_item = apiUrl + '/' + id;
                let headers = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }
                // get single item
                $.ajax({
                    type      : 'GET',
                    url       : api_get_single_item,
                    headers   : headers,
                    beforeSend: ()=>{
                        _this.text('loading...');
                    },
                    success   : (res)=>{
                        let email = res[0].email;
                        _this.closest('td').append(`
                            <div class="gv-get-single-item">
                                <form action="javascript:void(0)" class="update-visitor-item">
                                    <div class="popup-header">
                                        <span>Edit Item</span>
                                        <span class="close">&#x2716;</span>
                                    </div>
                                    <input type="email" value="${email}">
                                    <p>
                                        <input type="submit" value="Save Changes" class="button button-primary">
                                        <input type="hidden" value="${id}">
                                    </p>
                                </form>
                            </div>
                        `);
                        $('.gv-get-single-item').addClass('popupvisible');
                        _this.text(text);
                    },
                    error     : (err)=>{
                        alert( 'Something went wrong.' );
                    }
                });
            });
        }

        updateVisitor(){
            listParent.on('submit', 'form.update-visitor-item', function(e){
                e.preventDefault();
                let _this = $(this);
                let popupBox = _this.closest('.gv-get-single-item');
                let id    = parseInt(_this.find('input[type="hidden"]').val());
                let emailField = _this.find('input[type="email"]');
                let email = emailField.val();
                let api_update_url = apiUrl + '/' + id;
                let data = {
                    email : email
                }
                let headers = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }

                $.ajax({
                    type      : 'PATCH',
                    url       : api_update_url,
                    data      : JSON.stringify(data),
                    headers   : headers,
                    beforeSend: ()=>{
                        _this.find('input[type="submit"]').after('<span style="display:inline-block;margin-left:15px;">Updating...</span>');
                    },
                    success   : ()=>{
                        _this.closest('td').find('span.get-email-address').text(email);
                        _this.closest('.gv-get-single-item').addClass()
                        popupBox.addClass('popup-reverse').removeClass('popupvisible');
                        setTimeout(()=>{ popupBox.remove() }, 300);

                        _this.closest('table').siblings('form').before(`
                            <div class="notice notice-success is-dismissible"><p><strong>Update successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
                        `);
                    },
                    error     : ()=>{
                        _this.closest('table').siblings('form').before(`
                            <div class="notice notice-warning is-dismissible"><p><strong>Something went wrong.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
                        `);
                    }
                });



                
            })
        }

        deleteVisitor(){
            listParent.on('click', 'a.delete-visitor-item', function(e){
                e.preventDefault();

                let confirmation = confirm('Are You Sure?');

                if( confirmation !== true )
                    return;
                
                let _this = $(this);
                let id = parseInt(_this.data('id'));
                let api_delete_url = apiUrl + '/' + id;
                let headers = {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce'  : nonce
                }
                
                $.ajax({
                    type      : 'DELETE',
                    url       : api_delete_url,
                    headers   : headers,
                    beforeSend: ()=>{
                        _this.text('Deleting...');
                    },
                    success   : ()=>{
                        _this.closest('tr')
                            .addClass('item-will-be-delete')
                            .fadeOut(300, ()=>{
                                _this.closest('tr').remove();
                            });
                    },
                    error     : ()=>{
                        alert('Something went wrong.')
                    }
                });

            });
        }
        
        addNewVisitor(){
            form.each(function(e){
                $(this).on('submit', function(e){
                    e.preventDefault();
                    let _this = $(this);
                    let email = _this.find('input[type="email"]').val();
                    let data  = {
                        email : email
                    }
                    let headers = {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce'  : nonce
                    }

                    $.ajax({
                        type      : 'POST',
                        url       : apiUrl,
                        data      : JSON.stringify(data),
                        headers   : headers,
                        beforeSend: ()=>{
                            _this.find('p.submit').append('<span class="spin"></span>');
                        },
                        success   : (res)=>{
                            _this.find('span.spin').fadeOut(300, (e)=>{
                                _this.find('span.spin').remove();
                            });
                            _this.before( `<div class="notice notice-success is-dismissible"><p><strong>${res}</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>`);
                            _this.find('input[type="email"]').val('');
                        },
                        error     : ()=>{
                            _this.before('<div class="notice notice-warning is-dismissible"><p><strong>Something went wrong.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                            _this.find('span.spin').fadeOut(300, (e)=>{
                                _this.find('span.spin').remove();
                            });
                        }
                    });

                });
            });
        }
    }

    // initiate the program
    doc.ready(()=>{ new GV_ADMIN_SCRIPT });

})(jQuery);