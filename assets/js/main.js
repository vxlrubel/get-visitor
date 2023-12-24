;(($)=>{
    const doc = $(document);
    const formParent = $('.gv-collect-form');
    const form = $('.gv-collect-form form');
    class GetVisitor{
        constructor(){
            this.submitForm();
        }

        submitForm(){
            form.each(function(){

                $(this).on('submit', function(e){
                    e.preventDefault();
                    let parent = $(this).closest('.gv-collect-form');
                    const apiUrl = GV.api_url;
                    const email = $(this).find('input[type="email"]').val();
                    const data = {
                        email : email
                    }
                    const nonce = GV.nonce;
                    const headers = {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': nonce
                    };

                    $.ajax({
                        type      : 'POST',
                        url       : apiUrl,
                        data      : JSON.stringify(data),
                        headers   : headers,
                        beforeSend: ()=>{
                            $(this).find('.submit-parent').append('<span class="spin"></span>');
                        },
                        success   : ( res )=>{
                            parent.find('span.spin').remove();
                            $(this).after(`<div class="notice success">${res}<span class="close">&#10006;</span></div>`);
                            parent.find('input[type="email"]').val('');
                        },
                        error     : ()=>{
                            parent.find('span.spin').remove();
                            $(this).after('<div class="notice warning">Something went wront. <span class="close">&#10006;</span></div>')
                        }
                    });
                });
            });

            // destroy notice
            formParent.on('click', 'span.close', function(e){
                let notice = $(this).closest('.notice');
                notice.fadeOut(300, ()=>{
                    notice.remove();
                });
            });
        }
    }

    doc.ready( ()=> {
        new GetVisitor;
    });
    
})(jQuery);