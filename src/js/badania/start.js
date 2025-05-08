$('document').ready(function() {
	function modalAlert(text) {
		alert(text);
	}

    $('.start-link').click(function(e) {
        e.preventDefault;
        $('html, body').animate({
            scrollTop: $('#badanie-formularz').offset().top - 120
        }, 500);
    });

    $('#page-badanie #start form').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const checked = form.find('input[name="udzial"]:checked').val();
        const zrozumienie = form.find('input[name="zrozumienie"]:checked').val();
        if(checked == 'tak' && zrozumienie == 'tak') {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'data': {
                    'action' : 'wczytaj_wstepne',
                    'krok' : 'wstepne'
                },
                beforeSend: function() { 
                    $('.steps-content').addClass('loading');
                },
                success: function(response) {
                    $('#page-badanie .col-left .header h1').html('0. ' + title_wstepne); 
                    // to trzeba poprawic
                    $('#page-badanie .col-left .content').html('');
                    $('.steps-content').removeClass('loading');
                    $('.steps-content').html(response);
                    $('.steps-nav').find('.step-0').parent().addClass('active');
                    $('html, body').animate({
                        scrollTop: $("#badanie-formularz").offset().top - 120	
                    }, 500);
                },
            });
        } else if(checked == 'nie' && zrozumienie == 'tak') {
            modalAlert(text_zgoda_uczestnicwo);
        } else if(checked == 'tak' && zrozumienie == 'nie') {
            modalAlert(text_zgoda_zrozumienie);
        } else if(checked == 'nie' && zrozumienie == 'nie') {
            modalAlert(text_zgoda_zrozumienie);
            modalAlert(text_zgoda_uczestnicwo);
        }
    });
});