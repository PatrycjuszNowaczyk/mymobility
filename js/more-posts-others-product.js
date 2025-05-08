jQuery(function($){
    $('#loadmore .btn').click(function(){ 
        var data = {
            'action': 'loadmorebutton', // the parameter for admin-ajax.php
//            'query': loadmore_params.posts, // loop parameters passed by wp_localize_script()
            'page' : loadmore_params.current_page, // current page
            'typ' : 'produkt',
            'post_id' : loadmore_params.post_id,

        };
        $.ajax({
            url : loadmore_params.ajaxurl, // AJAX handler
            data : data,
            type : 'POST',
            beforeSend : function ( xhr ) { 
                $('#loadmore').addClass('loading');
            },
            success : function( posts ){
                $('#loadmore').removeClass('loading');
                if( posts ) {
                    $('#others-products .products').append( posts );
                    loadmore_params.current_page++; 
                    if ( loadmore_params.current_page == loadmore_params.max_page ) 
                        $('#loadmore').hide(); 
 
                } else {
                    $('#loadmore').hide(); 
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
        return false;
    });
 
});