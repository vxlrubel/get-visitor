;(($)=>{
    const doc            = $(document);
    const form           = $('.add-new-visitor form');
    const apiUrl         = GV.api_url;
    const apiSettingsUrl = GV.api_settings_url;
    const nonce          = GV.nonce;
    const listParent     = $('.get-visitor-list-parent');
    const settings       = $('.gv-settings');
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
        }

        resetGeneralSettings(){
            
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
                    desc          : _this.find('#desc').text(),
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