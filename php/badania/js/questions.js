jQuery('document').ready(function ($) {

    function wczytaj_pytania(krok) {
        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_pytania_lista_row',
                'krok' : krok
            }, 
            success: function(success) {  
                $('table.table-'+ krok +' tbody').html(success);
            },
            error: function(error) {
                console.log(error);
            }
        });        
    }

    function wczytaj_odpowiedzi(pytanie, pytanie_ID, odpowiedz_ID) {
        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_pytania_lista_row_odpowiedzi',
                'pytanie_ID': pytanie_ID,
                'odpowiedz_ID': odpowiedz_ID
            },
            success: function(success) {
                pytanie.find('.lista-odpowiedzi').html(success);
            },
            error: function(error) {
                console.log(error);
            }
        });        
    }

    $('.form-pytania').submit(function(e) {
        e.preventDefault();   
        const form_pytania = $(this);
        const krok = form_pytania.find('input[name="krok"]').val();

        let form = form_pytania.get(0);
        let formData = new FormData(form);


        formData.append('action', 'badania_pytania_dodaj');

        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                form_pytania.closest('.badania-field').addClass('loading');
                console.log(formData);
            },
            success: function(success) { 
                form_pytania.closest('.badania-field').removeClass('loading');
                $('.form-pytania').trigger("reset");
                form_pytania.find('.pytanie_skala').hide();
                wczytaj_pytania(krok);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('#form-pytania select[name="warunek_pytanie_ID"]').on('change', function(e) {
        e.preventDefault();
        const select = $(this);
        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_pytania_warunek_pytanie_odpowiedzi',
                'pytanie_ID' : select.find('option:selected').val(),
            },
            success: function(success) {
                select.parent().find('.warunek_odpowiedzi').remove();
                if(success) {
                select.after(success);
            }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $('#form-pytania input[name="pytanie_warunek"]').on('change', function(e) {
        if($(this).is(':checked')) {
            $('#form-pytania #warunek-lista').show();
        } else {
            $('#form-pytania #warunek-lista').hide();
        }
    });
    
    $(document).on('change', 'select[name="pytanie_typ"]', function(e) {
        if($(this).val() === 'skala-1-7' || $(this).val() === 'skala-1-5' || $(this).val() === 'skala-1-6' || $(this).val() === 'skala-1-10' || $(this).val() === 'skala-0-10') {
            $(this).closest('table').find('.pytanie_skala').show();
        } else {
            $(this).closest('table').find('.pytanie_skala').hide();
        }
    });

    $(document).on('click', '.pytanie_odpowiedzi', function(e) {
        e.preventDefault();   
        const link = $(this);
        const parent = link.closest('tr');

        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_odpowiedz_form',
                'pytanie_ID' : link.data('pytanie_id'),
            },
            success: function(response) {
                parent.after('<tr class="form-odpowiedz"><td colspan="6">' + response + '</td></tr>');
            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $(document).on('click', '.pytanie_usun', function(e) {
        e.preventDefault();    
        const pytanie = $(this).data('pytanie_id');
        const krok_id = $(this).data('krok_id');
        const krok = $(this).data('krok');

        const potwierdzenie = confirm("Czy jesteś pewien?\n\nUsuniesz pytanie oraz odpowiedzi z bazy.");

        if(pytanie && potwierdzenie) {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'data': {
                    'action' : 'badania_pytania_usun',
                    'pytanie_id' : pytanie,
                    'krok_id' : krok_id,
                    'krok' : krok
                },
                beforeSend: function() {
                },
                success: function() {
                    alert('Pytanie oraz odpowiedzi zostały usunięte.');
                    $('#pytania a.pytanie_usun[data-pytanie_id="'+ pytanie +'"]').closest('tr').remove();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });


    $(document).on('submit', '#form-pytania-zmien', function(e) {
        e.preventDefault();    
        const form = $(this);
        const formGet = $(this).get(0);
        const formData = new FormData(formGet);
        const krok = form.find('input[name="krok"]').val();

        formData.append('action', 'badania_edycja_pytania');

        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                form.closest('.badania-field').addClass('loading');
            },
            success: function() {
                const prev = form.closest('tr').prev();
                form.closest('.badania-field').removeClass('loading');
                form.closest('tr').remove();
                wczytaj_pytania(krok);
            },
            error: function(error) {
                console.log(error);
            }
        });

    });  
    

    $(document).on('click', '.pytanie_edytuj', function(e) {
        e.preventDefault();    
        const btn = $(this);
        const pytanie = btn.data('pytanie_id');
        const krok_id = btn.data('krok_id');
        const krok = btn.data('krok');

        if(pytanie) {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'data': {
                    'action' : 'badania_pytania_edytuj',
                    'pytanie_id' : pytanie,
                    'krok_id' : krok_id,
                    'krok' : krok
                },
                beforeSend: function() {
                    btn.closest('.badania-field').addClass('loading');
                },
                success: function(response) {
                    btn.closest('.badania-field').removeClass('loading');
                    btn.closest('tr').after(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });    
    
    $(document).on('submit', '.dodaj_odpowiedz', function(e) {
        e.preventDefault();   
        const odpowiedz = $(this);
        const pytanie = odpowiedz.closest('tr').prev();
        const pytanie_ID = $('input[name="pytanie_ID"]').val();
        const odpowiedz_ID = $('input[name="odpowiedz_ID"]').val();

        let form = odpowiedz.get(0);
        let formData = new FormData(form);

        formData.append('action', 'badania_odpowiedz_dodaj');

        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                odpowiedz.closest('.badania-field').addClass('loading');
            },
            success: function(success) {
                odpowiedz.closest('.badania-field').removeClass('loading');
                odpowiedz.closest('tr').remove();

                wczytaj_odpowiedzi(pytanie, pytanie_ID, odpowiedz_ID);

            },
            error: function(error) {
                console.log(error);
            }
        });
    });

    $(document).on('click', '.odpowiedz-usun', function(e) {
        e.preventDefault();    
        const answer = $(this);
        const odpowiedz_ID = answer.data('odpowiedz_id');
        const potwierdzenie = confirm("Czy jesteś pewien?\n\nUsuniesz tą opcję odpowiedzi.");

        if(odpowiedz_ID && potwierdzenie) {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'data': {
                    'action' : 'badania_odpowiedz_usun',
                    'odpowiedz_ID' : odpowiedz_ID,
                },
                beforeSend: function() {
                    answer.closest('.badania-field').addClass('loading');
                },
                success: function() {
                    answer.closest('.badania-field').removeClass('loading');
                    alert('Odpowiedź została usunięta.');
                    answer.closest('li').remove();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });

    $(document).on('click', '.odpowiedz-edytuj', function(e) {
        e.preventDefault();    
        const answer = $(this);
        const odpowiedz_ID = answer.data('odpowiedz_id');

        if(odpowiedz_ID) {
            $.ajax({
                'type': 'POST',
                'url': ajaxurl,
                'data': {
                    'action' : 'badania_odpowiedz_form',
                    'odpowiedz_ID' : odpowiedz_ID,
                },
                beforeSend: function() {
                    answer.closest('.badania-field').addClass('loading');
                },
                success: function(response) {
                    answer.closest('.badania-field').removeClass('loading');
                    answer.closest('tr').after('<tr><td colspan="5">'+ response +'</td></tr>');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });


    $(document).on('click', '.pytanie_przesun_gora', function(e) {
        e.preventDefault();    
        const krok_id = $(this).data('krok_id');
        const krok = $(this).data('krok');

        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_pytania_gora',
                'krok_id' : krok_id,
                'krok' : krok
            },
            beforeSend: function() {
                $('table.badania-pytania-table').parent().addClass('loading');
            },
            success: function() {
                $('table.badania-pytania-table').parent().removeClass('loading');
                wczytaj_pytania(krok);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
    

    $(document).on('click', '.pytanie_przesun_dol', function(e) {
        e.preventDefault();    
        const krok_id = $(this).data('krok_id');
        const krok = $(this).data('krok');

        $.ajax({
            'type': 'POST',
            'url': ajaxurl,
            'data': {
                'action' : 'badania_pytania_dol',
                'krok_id' : krok_id,
                'krok' : krok
            },
            beforeSend: function() {
                $('table.badania-pytania-table').parent().addClass('loading');
            },
            success: function() {
                $('table.badania-pytania-table').parent().removeClass('loading');
                wczytaj_pytania(krok);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });


    $(document).on('click', '.questions-group > h2', function(e) {
        $(this).parent().toggleClass('active');
    });

});