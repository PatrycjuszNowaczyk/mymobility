jQuery('document').ready(function ($) {
    function wczytaj_liste_wynikow() { 
        $.ajax({
            'type': 'GET',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_wyniki_lista', 
            },
            beforeSend: function() {
                $('.content-wyniki').addClass('loading');
            },
            success: function(response) {
                $('.content-wyniki').removeClass('loading');
                $('.content-wyniki table tbody').html(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    $(document).on('click', '.badanie_usun', function(e) {
        e.preventDefault();    
        let badanie = $(this).data('badanie_id');
        const potwierdzenie = confirm("Czy jesteś pewien?");

        if(badanie && potwierdzenie) {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'data': {
                    'action' : 'badania_wyniki_usun',
                    'badanie_id' : badanie
                },
                beforeSend: function() {
                    $('.content-wyniki').addClass('loading');
                },
                success: function() {
                    $('.content-wyniki').removeClass('loading');
                    wczytaj_liste_wynikow();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });
});