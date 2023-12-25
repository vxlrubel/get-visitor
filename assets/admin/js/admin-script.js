;(($)=>{
    const doc    = $(document);
    const form   = $('.add-new-visitor form');
    const apiUrl = GV.api_url;
    const nonce  = GV.nonce;
    class GV_ADMIN_SCRIPT{
        constructor(){
            this.addNewVisitor();
            this.deleteVisitor();
        }
        

        deleteVisitor(){
            const listParent = $('.get-visitor-list-parent');
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

            // submit form
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
                            _this.before('<div class="notice notice-success is-dismissible"><p><strong>Email add successfully.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                            _this.find('input[type="email"]').val('');
                        },
                        error     : ()=>{
                            _this.before('<div class="notice notice-warning is-dismissible"><p><strong>Something went wrong.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
                            _this.find('span.spin').fadeOut(300, (e)=>{
                                _this.find('span.spin').remove();
                            });
                        }
                    });

                })
            });

            // destroy notice
            $('.add-new-visitor').on('click', 'button.notice-dismiss', function(e){
                e.preventDefault();
                let notice_item = $(this).closest('div.notice');
                notice_item.fadeOut(300, ()=>{
                    notice_item.remove();
                })
            });
        }
    }

    // initiate the program
    doc.ready(()=>{ new GV_ADMIN_SCRIPT });

})(jQuery);