jQuery(function($){

    var canBeLoaded = true, 
        bottomOffset = 1200,
        current_page = 1; 
        
    $(window).scroll(function(){
        if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){        
            $.ajax({
                url : loadmore_params.ajaxurl, // AJAX handler
                data : {
                    'action': 'loadmorebutton', // the parameter for admin-ajax.php
                    'query': loadmore_params.posts, // loop parameters passed by wp_localize_script()
                    'page' : current_page, // current page
                    'typ' : 'wyszukiwarka',
                    'typ_wyszukiwarki' : loadmore_params.typ_wyszukiwarki,
                    's' : loadmore_params.s
                },
                type : 'POST',
                beforeSend : function ( xhr ) {
                    canBeLoaded = false;  
                },
                success : function( posts ){ 
                    if( posts ) { 
                        current_page++; 
                        $('#page-search .results').append( posts );
                        if ( current_page == loadmore_params.max_page ) {
                            $('#loadmore').hide(); 
                            canBeLoaded = false;      
                        } else {
                            canBeLoaded = true; 
                        }
                    } else {
                        $('#loadmore').hide(); 
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
            return false;
        }
    });
 
});