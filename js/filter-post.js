jQuery(function($){

    var canBeLoaded = true, 
        bottomOffset = 2000;
        current = 1;
        
    //$(window).scroll(function(){
    $('#loadmore .btn').click(function(){ 


        var categories = $('input[name="kategoria[]"]:checked').map(function(){
          return $(this).val();
        }).get();        

        var years = $('input[name="rok_produkcji[]"]:checked').map(function(){
          return $(this).val();
        }).get()

        var data = {
            'action': 'loadmorebutton', // the parameter for admin-ajax.php
            'query': loadmore_params.posts, // loop parameters passed by wp_localize_script()
            'page' : loadmore_params.current_page, // current page
            'szukaj_slowa' : $('input[name="szukaj_slowa"]').val(), // cat
            'kategoria' : categories,
            'years' : years,
            'typ' : 'instrukcje',
            'current_ID' : loadmore_params.current_ID,
            'order' : $('#order_by').val()
        };   

        //if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == true ){
            $.ajax({
                url : loadmore_params.ajaxurl, // AJAX handler
                data : data,
                type : 'POST',
                beforeSend : function ( xhr ) {
                    canBeLoaded = false; 
                    $('#loadmore').addClass('loading');
                },
                success : function( posts ){
                    $('#loadmore').removeClass('loading');

                    if( posts && loadmore_params.max_page > 1) { 
                        current++; 
     
                        if ( current == loadmore_params.max_page ) {
                            $('#loadmore').hide();  
                            canBeLoaded = false;                        
                        } else { 
                            canBeLoaded = true; 
                        }

                        $('#misha_posts_wrap').append( posts );
     
                     } else {
                        $('#loadmore').hide(); 
                        canBeLoaded = false;
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
            return false;
        //}
    });
 
    function ajax_loadpost() {
        $.ajax({
            url : loadmore_params.ajaxurl,
            data : $('#misha_filters').serialize(), // form data
            dataType : 'json', // this data type allows us to receive objects from the server
            type : 'POST',
            beforeSend : function(xhr){
                $('#category-shop .box_content').addClass('loading');
            },
            success : function( data ){ 
                loadmore_params.current_page = 1;
                loadmore_params.posts = data.posts;
                loadmore_params.max_page = data.max_page;

                $('#category-shop .box_content').removeClass('loading');
                $('#misha_posts_wrap').html(data.content);
                if ( data.max_page < 2 ) {
                    $('#loadmore').hide();
                } else {
                    $('#loadmore').show();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
        return false;        
    }

    $('#misha_filters #order_by, #misha_filters input[name="kategoria[]"], #misha_filters input[name="rok_produkcji[]"]').on('change',function () {
        ajax_loadpost();
        current = 1;
    });

    $('#misha_filters').submit(function(e){
        e.preventDefault();
        current = 1;
        ajax_loadpost(); 
    });
 
});