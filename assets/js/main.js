;(($)=>{
    const doc = $(document);
    const formParent = $('.gv-collect-form');
    const form = $('.gv-collect-form form');
    class GetVisitor{
        constructor(){
            this.submitForm();
        }

        submitForm(){
            form.on('submit', (e)=>{
                e.preventDefault();
                const email = form.find('input[type="email"]').val();
                const apiUrl = GV.api_url;
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
                        form.find('.submit-parent').append('<span class="spin"></span>');
                    },
                    success   : ( res )=>{
                        form.find('span.spin').remove();
                        form.after(`<div class="notice success">${res}<span class="close">&#10006;</span></div>`);
                        form.find('input[type="email"]').val('');
                    },
                    error     : ()=>{
                        form.find('span.spin').remove();
                        form.after('<div class="notice warning">Something went wront. <span class="close">&#10006;</span></div>')
                    }
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