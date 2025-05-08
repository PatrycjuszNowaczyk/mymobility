jQuery(function($){

    var canBeLoaded = true, 
        current = 1;
        
    $('#loadmore span').click(function(){

        var type = $('.filter-term input[name="type[]"]:checked').map(function(){
          return $(this).val();
        }).get();        

        var data = {
            'action': 'loadmorebutton',
            'page' : current,
            'filter_type' : type,
//                'current_taxonomy' : $('#current_taxonomy').val(),
        };   

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

                    $('#archive-realizations .realizations .grid').append( posts );
 
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

    });


    function load_realizations_filters() {
        $.ajax({
            url : loadmore_params.ajaxurl,
            data : $('.filter-term form').serialize(),
            dataType : 'json',
            type : 'POST',
            beforeSend : function(xhr){
                $('#archive-realizations .realizations').addClass('loading');
            },
            success : function( data ){ 
                loadmore_params.current_page = 1;
                loadmore_params.posts = data.posts;
                loadmore_params.max_page = data.max_page;

                $('#archive-realizations .realizations').removeClass('loading');
                $('#archive-realizations .realizations .grid').html(data.content);

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
 

    $('.filter-term input[name="type[]"], .filter-term #realizations_all').on('change',function () {
        load_realizations_filters();
        current = 1;
    });

    $('.filter-term #realizations_all').click(function() {
        $('.filter-term input[name="type[]"]').prop('checked',false);
        $('.filter-term #realizations_all input').prop('checked',true);
        load_realizations_filters();
        current = 1;
    });

    $('.filter-term input[name="type[]"]').change(function() {
        if ( $('.filter-term input[name="type[]"]:checked').length > 0 ) {
            $('.filter-term #realizations_all input').prop('checked',false);
        } else {
            $('.filter-term #realizations_all input').prop('checked',true);
        }
    });

        
});