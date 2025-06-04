$( 'document' ).ready( function () {
  $( document ).on( 'submit', '#form-code', function ( e ) {
    e.preventDefault();
    const kod = $( '#badanie_code' ).val();
    const input = $( this ).find( 'input' );
    const alertErrorEmpty = '<span class="error">' + text_error_empty + '</span>';
    const alertErrorNoFind = '<span class="error">' + text_error_nofind + '</span>';

    if ( kod ) {
      $.ajax( {
        'type' : 'POST',
        'dataType' : 'JSON',
        'url' : ajaxurl,
        'data' : {
          'action' : 'wczytaj_badanie',
          'badanie_code' : kod,
        },
        beforeSend : function () {
          $( '#page-badanie' ).addClass( 'loading' );
        },
        success : function ( response ) {
          $( '#page-badanie' ).removeClass( 'loading' );

          console.log( response );

          if ( response.success === true ) {
            if ( response.data.wczytaj === 'wstepne' ) {
              podsumowanie_wstepne( response.data.badanie_ID );
            } else if ( response.data.wczytaj === 'koniec' ) {
              wczytaj_podsumowanie_badania( response.data.badanie_ID );
            }
          } else {
            input.css( 'border-color', 'red' );
            input.after( alertErrorNoFind );
          }
        },
        error : function ( error ) {
          console.log( error );
        }
      } );
    } else {
      input.css( 'border-color', 'red' );
      input.after( alertErrorEmpty );
    }
  } );

  $( document ).on( 'submit', '#form-uruchom-krok', function ( e ) {
    e.preventDefault();
    if ( $( this ).find( 'input[name="uruchom-krok"]:checked' ).val() == 'krok-1' ) {
      let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();
      wczytaj_krok_pierwszy_etapu( 'krok1_1', badanie_ID );
    } else if ( $( this ).find( 'input[name="uruchom-krok"]:checked' ).val() == 'krok-2' ) {
      if ( $( 'input[name="uruchom-krok"][value="krok-1"]' ).hasClass( 'full' ) ) {
        let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();
        wczytaj_krok_pierwszy_etapu( 'krok2_1', badanie_ID );
      } else {
        alert( text_not_fill_step1 );
      }
    } else if ( $( this ).find( 'input[name="uruchom-krok"]:checked' ).val() == 'krok-3' ) {
      if ( $( 'input[name="uruchom-krok"][value="krok-1"]' ).hasClass( 'full' ) ) {
        let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();
        wczytaj_krok_pierwszy_etapu( 'krok3_1', badanie_ID );
      } else {
        alert( text_not_fill_step1 );
      }
    } else if ( $( this ).find( 'input[name="uruchom-krok"]:checked' ).val() == 'krok-4' ) {
      if ( $( 'input[name="uruchom-krok"][value="krok-1"]' ).hasClass( 'full' ) ) {
        let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();
        wczytaj_krok_pierwszy_etapu( 'krok4_1', badanie_ID );
      } else {
        alert( text_not_fill_step1 );
      }
    } else {
      alert( text_choose_step );
    }
  } );

  $( document ).on( 'click', '#generuj-pdf', function ( e ) {
    let badanie_ID = $( this ).data( 'badanie-id' );
    $.ajax( {
      'type' : 'POST',
      'dataType' : 'JSON',
      'url' : ajaxurl,
      'data' : {
        'action' : 'badanie_pdf',
        'badanie_ID' : badanie_ID,
      },
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
      },
      success : function ( response ) {
        $( '#page-badanie' ).removeClass( 'loading' );

        console.log( response );

        let temp_link = $( '<a></a>' ).attr( {
          download : response.name,
          href : response.link,
          id : 'download',
          name : 'test',
        } );

        $( "body" ).append( temp_link );

        temp_link[0].click();
        temp_link.remove();

      },
      error : function ( error ) {
        console.log( error );
      }
    } );
  } );

  function wczytaj_krok_pierwszy_etapu( etap, badanie_ID ) {
    $.ajax( {
      'type' : 'POST',
      'dataType' : 'JSON',
      'url' : ajaxurl,
      'data' : {
        'action' : 'wczytaj_krok_pierwszy_etapu',
        'krok' : etap,
        'badanie_ID' : badanie_ID,
      },
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
        $( '#page-badanie #badanie-formularz .steps-content' ).html( '' );
      },
      success : function ( response ) {
        console.log( response );

        $( '#page-badanie' ).removeClass( 'loading' );
        $( '#page-badanie .col-left' ).html( '<header class="header"><h1>' + response.data.naglowek + '</h1></header><div class="content"><div class="desc">' + response.data.opis + '</div></div>' );
        $( '#page-badanie #badanie-formularz' ).show();
        $( '#page-badanie #badanie-formularz .steps-nav li' ).removeClass( 'active' );
        if ( etap === 'krok1_1' ) {
          $( '#page-badanie #badanie-formularz .steps-nav .step-1' ).parent().addClass( 'active' );
        } else if ( etap === 'krok2_1' ) {
          $( '#page-badanie #badanie-formularz .steps-nav .step-2' ).parent().addClass( 'active' );
        } else if ( etap === 'krok3_1' ) {
          $( '#page-badanie #badanie-formularz .steps-nav .step-3' ).parent().addClass( 'active' );
        } else if ( etap === 'krok4_1' ) {
          $( '#page-badanie #badanie-formularz .steps-nav .step-4' ).parent().addClass( 'active' );
        }
        $( '#page-badanie #badanie-formularz .steps-content' ).html( response.data.form );
        $( 'html, body' ).animate( {
          scrollTop : $( '#page-badanie .col-left' ).offset().top - 120
        }, 500 );

        if ( response.success === false ) {
          if ( etap === 'krok1_1' ) {
            wyswietl_wynik_krok( 'kapital_ludzki', 'pierwsza_czesc', 'step-1-1', false, badanie_ID );
            wczytaj_podkrok( 'krok2_1', 'kapital_psychologiczny', 'pierwsza_czesc', 1 );
          } else if ( etap === 'krok2_1' ) {
            wyswietl_wynik_krok( 'kapital_psychologiczny', 'pierwsza_czesc', 'step-2-1', false, badanie_ID );
            wczytaj_podkrok( 'krok3_1', 'kapital_spoleczny', 'pierwsza_czesc', 1 );
          } else if ( etap === 'krok3_1' ) {
            wyswietl_wynik_krok( 'kapital_spoleczny', 'pierwsza_czesc', 'step-3-1', false, badanie_ID );
            wczytaj_podkrok( 'krok3_2', 'kapital_spoleczny', 'druga_czesc', 2 );
          } else if ( etap === 'krok3_2' ) {
            wyswietl_wynik_krok( 'kapital_spoleczny', 'druga_czesc', 'step-3-2', false, badanie_ID );
            wczytaj_podkrok( 'krok3_3', 'kapital_spoleczny', 'trzecia_czesc', 3 );
          } else if ( etap === 'krok4_1' ) {
            wyswietl_wynik_krok( 'kapital_ekonomiczny', 'pierwsza_czesc', 'step-4-1', false, badanie_ID );
            wczytaj_podkrok( 'krok4_2', 'kapital_ekonomiczny', 'druga_czesc', 2 );
          }
        }

      },
      error : function ( error ) {
        console.log( error );
      }
    } )
  }

  function wczytaj_podkrok( krok, nazwa, czesc, step, badanie ) {

    if ( $( 'input[name="badanie_ID"]' ).val() ) {
      badanie_ID = $( 'input[name="badanie_ID"]' ).val();
    } else {
      badanie_ID = badanie;
    }

    $.ajax( {
      'type' : 'POST',
      'dataType' : 'JSON',
      'url' : ajaxurl,
      'data' : {
        'action' : 'wczytaj_podkrok',
        'nazwa' : nazwa,
        'czesc' : czesc,
        'krok' : krok,
        'krok_liczba' : step,
        'badanie_ID' : badanie_ID,
      },
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
      },
      success : function ( response ) {
        console.log( response );
        let prev = step - 1;
        let next = step + 1;

        $( '#page-badanie' ).removeClass( 'loading' );
        if ( krok === 'krok1_1' ) {
          $( '#page-badanie #badanie-formularz .steps-content #step-1-' + prev ).after( response.data.form );
        } else if ( krok === 'krok2_1' ) {
          $( '#page-badanie #badanie-formularz .steps-content #step-2-' + prev ).after( response.data.form );
        } else if ( krok === 'krok3_1' || krok === 'krok3_2' || krok === 'krok3_3' ) {
          $( '#page-badanie #badanie-formularz .steps-content #step-3-' + prev ).after( response.data.form );
        } else if ( krok === 'krok4_1' || krok === 'krok4_2' ) {
          $( '#page-badanie #badanie-formularz .steps-content #step-4-' + prev ).after( response.data.form );
        }

        if ( response.success === false ) {
          if ( krok === 'krok2_1' ) {
            wyswietl_wynik_krok( 'kapital_ludzki', 'pierwsza_czesc', 'step-1-' + step, false, badanie_ID );
            wczytajPodsumowanieEtapu( '1-1', 'krok2_1', badanie_ID );

            // 	wyswietl_wynik_krok('kapital_ludzki', 'druga_czesc', 'step-1-'+step, false, badanie_ID);
            // 	wczytaj_podkrok('krok1_3', 'kapital_ludzki', 'trzecia_czesc', next);
            // } else if(krok === 'krok1_3') {
            // 	wyswietl_wynik_krok('kapital_ludzki', 'trzecia_czesc', 'step-1-'+step, false, badanie_ID);
            // 	wczytajPodsumowanieEtapu('1-3', 'krok2_1', badanie_ID);
          } else if ( krok === 'krok2_2' ) {
            wyswietl_wynik_krok( 'kapital_psychologiczny', 'druga_czesc', 'step-2-' + step, false, badanie_ID );
            wczytaj_podkrok( 'krok2_3', 'kapital_psychologiczny', 'trzecia_czesc', next );
          } else if ( krok === 'krok2_3' ) {
            wyswietl_wynik_krok( 'kapital_psychologiczny', 'trzecia_czesc', 'step-2-' + step, false, badanie_ID );
            wczytajPodsumowanieEtapu( '2-3', 'krok3_1', badanie_ID );
          } else if ( krok === 'krok3_2' ) {
            wyswietl_wynik_krok( 'kapital_spoleczny', 'druga_czesc', 'step-3-' + step, false, badanie_ID );
            wczytaj_podkrok( 'krok3_3', 'kapital_spoleczny', 'trzecia_czesc', next );
          } else if ( krok === 'krok3_3' ) {
            wyswietl_wynik_krok( 'kapital_spoleczny', 'trzecia_czesc', 'step-3-' + step, false, badanie_ID );
            wczytajPodsumowanieEtapu( '3-3', 'krok4_1', badanie_ID );
          } else if ( krok === 'krok4_2' ) {
            wyswietl_wynik_krok( 'kapital_ekonomiczny', 'druga_czesc', 'step-4-' + step, false, badanie_ID );
            wczytajPodsumowanieEtapu( '4-2', 'wstepne_podsumowanie', badanie_ID );
          }
        }
      },
      error : function ( error ) {
        console.log( error );
      }
    } );
  }

  function wyswietl_wynik_krok( nazwa, czesc, step, usun_formularz, badanie_ID ) {
    $.ajax( {
      'type' : 'POST',
      'dataType' : 'JSON',
      'url' : ajaxurl,
      'data' : {
        'action' : 'wyswietl_wynik',
        'nazwa' : nazwa,
        'czesc' : czesc,
        'step' : step,
        'badanie_ID' : badanie_ID,
      },
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
        if ( usun_formularz ) {
          $( '#page-badanie #badanie-formularz .steps-content #' + step + ' form' ).remove();
        }
      },
      success : function ( response ) {
        console.log( response );
        $( '#page-badanie' ).removeClass( 'loading' );
        $( '#page-badanie #badanie-formularz .steps-content #' + step ).append( response.data.result );

        $( 'html, body' ).animate( {
          scrollTop : $( '#page-badanie #badanie-formularz .steps-content #' + step + ' .result' ).offset().top - 120
        }, 500 );

      },
      error : function ( error ) {
        console.log( error );
      }
    } )

  }

  function podsumowanie_wstepne( badanie_ID ) {
    $.ajax( {
      'type' : 'POST',
      'url' : ajaxurl,
      'data' : {
        'action' : 'podsumowanie_wstepne',
        'badanie_ID' : badanie_ID
      },
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
        $( '#page-badanie .col-left' ).html( '' );
        $( '#page-badanie .col-right' ).html( '' );
        $( '#page-badanie #badanie-formularz' ).hide();
      },
      success : function ( response ) {
        $( '#page-badanie' ).removeClass( 'loading' );
        $( '#page-badanie .col-left' ).html( response );
        $( 'html, body' ).animate( {
          scrollTop : $( '#page-badanie .col-left' ).offset().top - 120
        }, 500 );
      },
      error : function ( error ) {
        console.log( error );
      }
    } );
  }

  function wczytaj_podsumowanie_badania( badanie_ID ) {
    $.ajax( {
      'type' : 'POST',
      'dataType' : 'JSON',
      'url' : ajaxurl,
      'data' : {
        'action' : 'podsumowanie_badania',
        'badanie_ID' : badanie_ID,
      },
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
        $( '#page-badanie .col-left' ).html( '' );
        $( '#page-badanie .col-right' ).html( '' );
        $( '#badanie-formularz .steps-content' ).html( '' );
      },
      success : function ( response ) {
        $( '#page-badanie' ).removeClass( 'loading' );
        $( '#page-badanie .col-left' ).html( response.data.opis );
        $( '#badanie-formularz .steps-nav' ).remove();
        console.log( response );
        if ( response.success === true ) {
          $( '#badanie-formularz .steps-content' ).html( response.data.form );
        } else {
          $( '#badanie-formularz' ).remove();
        }
        $( 'html, body' ).animate( {
          scrollTop : $( '#page-badanie .col-left' ).offset().top - 120
        }, 500 );
      },
      error : function ( error ) {
        console.log( error );
      }
    } );
  }

  function clearInputsHidden() {
    $( 'input' ).each( function () {
      let thisInput = $( this );
      if ( thisInput.closest( '.item' ).hasClass( 'noactive' ) ) {
        let thisInputType = thisInput.attr( 'type' );

        if ( thisInputType == "radio" || thisInputType == "checkbox" || thisInputType == "select" ) {
          thisInput.prop( 'checked', false );
        } else {
          thisInput.val( '' );
        }
      }
    } );
  }

  function input_inne() {
    $( 'input.inne' ).on( 'change', function ( e ) {
      let radio = $( this ).closest( '.answers' ).find( 'input[type="radio"]:checked' );

      if ( !( radio.hasClass( 'wybor-inne' ) ) ) {
        radio.prop( 'checked', false );
        $( this ).closest( '.answers' ).find( 'input[type="radio"].wybor-inne' ).prop( 'checked', true );
      }
    } );
  }

  function change_option() {
    let tablica = [];
    let narodowosc = '';
    $( '#page-badanie form.form-step' ).attr( 'data-checked', "[" + tablica + "]" );

    $( '#page-badanie .item input[type="radio"]' ).on( 'change', function ( e ) {
      const inputName = $( this ).attr( 'name' );

      $( '#page-badanie .item input[name="' + inputName + '"]' ).each( function () {
        const odpowiedz = $( this ).data( 'odpowiedz-numer' );
        const index = tablica.indexOf( odpowiedz );
        if ( index > -1 ) {
          tablica.splice( index, 1 );
        }
      } );

      const odpowiedzNumer = $( '#page-badanie .item input[name="' + inputName + '"]:checked' ).data( 'odpowiedz-numer' );

      tablica.push( odpowiedzNumer );

      $( '#page-badanie form.form-step' ).attr( 'data-checked', "[" + tablica + "]" );

      $( '.hide' ).each( function () {
        let id = $( this ).data( 'show-question' );

        if ( ( 'undefined' !== typeof id ) && ( id.length > 0 ) ) {
          for ( let i = 0 ; i < id.length ; i++ ) {
            let sprawdz = id[i];
            if ( tablica.indexOf( sprawdz ) > -1 ) {
              // if($(this).data('item-id') !== 'WSTEPNE_42' && $(this).data('item-id') !== 'WSTEPNE_49' && $(this).data('item-id') !== 'WSTEPNE_59') {
              $( this ).show();
              $( this ).addClass( 'active' ).removeClass( 'noactive' );
              break;
              // } else {
              // 	if(narodowosc === "b") {
              // 		$(this).show();
              // 		$(this).addClass('active').removeClass('noactive');
              // 		break;
              // 	} else {
              // 		$(this).hide();
              // 		$(this).removeClass('active').addClass('noactive');
              // 	}
              // }
            } else {
              $( this ).hide();
              $( this ).removeClass( 'active' ).addClass( 'noactive' );
            }
          }
        }
      } );

      const inne = $( this ).closest( '.answers' ).find( 'input.inne' );
      if ( inne && !( $( this ).hasClass( 'wybor-inne' ) ) ) {
        inne.val( '' );
      }
    } );

    $( '#page-badanie .item input[type="checkbox"]' ).on( 'change', function ( e ) {
      let odpowiedzNumer = $( this ).data( 'odpowiedz-numer' );
      let index = tablica.indexOf( odpowiedzNumer );

      if ( index > -1 ) {
        tablica.splice( index, 1 );
      }

      if ( $( this ).is( ':checked' ) ) {
        tablica.push( odpowiedzNumer );
      }
      console.log( tablica );

      $( '#page-badanie form.form-step' ).attr( 'data-checked', "[" + tablica + "]" );

      $( '.hide' ).each( function () {
        let id = $( this ).data( 'show-question' );

        if ( ( 'undefined' !== typeof id ) && ( id.length > 0 ) ) {
          for ( let i = 0 ; i < id.length ; i++ ) {
            let sprawdz = id[i];
            if ( tablica.indexOf( sprawdz ) > -1 ) {
              $( this ).show();
              $( this ).addClass( 'active' ).removeClass( 'noactive' );
              break;
            } else {
              $( this ).hide();
              $( this ).removeClass( 'active' ).addClass( 'noactive' );
            }
          }
        }
      } );
    } );

    $( '#page-badanie .item .inne_checkbox' ).on( 'input', function () {
      const input = $( this );
      const input_checkbox = input.closest( 'label' ).find( 'input[type="checkbox"]' );
      const odpowiedzNumer = input_checkbox.data( 'odpowiedz-numer' );

      if ( input.val() && !( input_checkbox.is( ':checked' ) ) ) {
        input_checkbox.prop( 'checked', true );
        tablica.push( odpowiedzNumer );
        console.log( tablica );
      }
    } );

    // narodowosc - skomplikowany filtr w WSTEPNE
    // $('#page-badanie .item[data-item-id="WSTEPNE_23"] input[type="radio"]').on('change', function() {
    // 	narodowosc = $(this).parent().find('input:checked').val();
    // 	let items = $('.item[data-item-id="WSTEPNE_42"], .item[data-item-id="WSTEPNE_49"], .item[data-item-id="WSTEPNE_59"]');
    // 	if((narodowosc === "b") && ($('input[name="WSTEPNE_28"]:checked').val() === "b" || $('input[name="WSTEPNE_28"]:checked').val() === "c") && ($('input[name="WSTEPNE_41"]:checked').val() === "a")) {
    // 		items.show();
    // 		items.addClass('active').removeClass('noactive');
    // 	} else {
    // 		items.hide();
    // 		items.removeClass('active').addClass('noactive');
    // 		items.find('input[type="text"]').val('');
    // 		items.find('input[type="number"]').val('');
    // 		items.find('textarea').val('');
    // 		items.find('input[type="radio"]').prop('checked',false);
    // 		items.find('input[type="checkbox"]').prop('checked',false);
    // 	}

    // });
  }

  function validate_form() {
    let formError = false;

    $( '#page-badanie form .item' ).each( function () {
      const item = $( this );

      if ( !( item.hasClass( 'no-required' ) ) ) {
        if ( item.hasClass( 'error' ) ) {
          item.removeClass( 'error' );
          if ( item.find( '.alert' ) ) {
            item.find( '.alert' ).remove();
          }
        }
        if ( ( item.hasClass( 'hide' ) && item.hasClass( 'active' ) ) || !( item.hasClass( 'hide' ) ) ) {
          const input = item.find( 'input' );
          const textarea = item.find( 'textarea' );
          const select = item.find( 'select' );
          if ( input.length && input.attr( 'type' ) == 'radio' && !( item.find( 'input[type="radio"]:checked' ).val() ) ) {
            console.log( 'Problem: ' + item.data( 'item-id' ) + item.find( 'input[type="radio"]:checked' ).val() );

            item.addClass( 'error' );
            if ( item.find( '.alert' ) ) {
              item.find( '.alert' ).remove();
            }
            item.append( '<span class="alert">' + text_error_empty_options + '</span>' );
            formError = true;
          } else if ( input.length && input.attr( 'type' ) == 'checkbox' && !( item.find( 'input[type="checkbox"]:checked' ).val() ) ) {
            item.addClass( 'error' );
            if ( item.find( '.alert' ) ) {
              item.find( '.alert' ).remove();
            }
            item.append( '<span class="alert">' + text_error_empty_options + '</span>' );
            formError = true;
          } else if ( select.length && !( select.find( 'option:selected' ).val() ) ) {
            item.addClass( 'error' );
            if ( item.find( '.alert' ) ) {
              item.find( '.alert' ).remove();
            }
            item.append( '<span class="alert">' + text_error_empty_options + '</span>' );
            formError = true;
          } else if ( input.length && ( input.attr( 'type' ) == 'number' || input.attr( 'type' ) == 'text' ) && !( input.val() ) ) {
            if ( !( input.hasClass( 'inne' ) ) /*|| (input.hasClass('inne') && input.prev('select').find('option:selected').val() == 'inna')*/ ) {
              item.addClass( 'error' );
              if ( item.find( '.alert' ) ) {
                item.find( '.alert' ).remove();
              }
              item.append( '<span class="alert">' + text_error_empty + '</span>' );
              formError = true;
            }
          } else if ( textarea.length && !( textarea.val() ) ) {
            item.addClass( 'error' );
            if ( item.find( '.alert' ) ) {
              item.find( '.alert' ).remove();
            }
            item.append( '<span class="alert">' + text_error_empty + '</span>' );
            formError = true;
          }
        } else {
          item.find( 'input[type="text"]' ).val( '' );
          item.find( 'input[type="number"]' ).val( '' );
          item.find( 'textarea' ).val( '' );
          item.find( 'input[type="radio"]' ).prop( 'checked', false );
          item.find( 'input[type="checkbox"]' ).prop( 'checked', false );
        }
      }

    } );

    return formError;
  }

  function show_form_error() {
    if ( $( this ).find( '.problem' ) ) {
      $( this ).find( '.problem' ).remove();
    }

    $( this ).append( '<div class="problem">' + text_error_validation + '</div>' );

    $( 'html, body' ).animate( {
      scrollTop : $( '#page-badanie form .item.error' ).offset().top - 120
    }, 500 );
    $( this ).find( 'button' ).removeAttr( 'disabled' );
  }

  function submit_form( action, step ) {
    $( '#page-badanie form.form-step[data-step="' + step + '"]' ).submit( function ( e ) {
      $( this ).find( 'button' ).attr( 'disabled', 'disabled' );
      e.preventDefault();
      e.stopImmediatePropagation();
      $( '#page-badanie .loading' ).addClass( 'loading' );
      clearInputsHidden();

      let formError = validate_form();


      if ( formError ) {
        show_form_error();
      } else {
        let form = $( this ).get( 0 );
        let formData = new FormData( form );

        formData.append( 'action', action );
        formData.append( 'krok', step );

        let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();

        $.ajax( {
          'type' : 'POST',
          'url' : ajaxurl,
          'data' : formData,
          processData : false,
          contentType : false,
          beforeSend : function ( send ) {
            console.log( formData );
          },
          success : function ( success ) {
            $( this ).hide();
            $( this ).find( 'button' ).removeAttr( 'disabled' );
            $( '#page-badanie .loading' ).removeClass( 'loading' );
            if ( step == 'wstepne' ) {
              podsumowanie_wstepne();
            } else if ( step == 'krok1_1' ) {
              wyswietl_wynik_krok( 'kapital_ludzki', 'pierwsza_czesc', 'step-1-1', true, badanie_ID );
              wczytajPodsumowanieEtapu( '1-1', 'krok2_1', badanie_ID );
            } else if ( step == 'krok2_1' ) {
              wyswietl_wynik_krok( 'kapital_psychologiczny', 'pierwsza_czesc', 'step-2-1', true, badanie_ID );
              wczytajPodsumowanieEtapu( '2-1', 'krok3_1', badanie_ID );
            } else if ( step == 'krok3_1' ) {
              wyswietl_wynik_krok( 'kapital_spoleczny', 'pierwsza_czesc', 'step-3-1', true, badanie_ID );
              wczytaj_podkrok( 'krok3_2', 'kapital_spoleczny', 'druga_czesc', 2, badanie_ID );
            } else if ( step == 'krok3_2' ) {
              wyswietl_wynik_krok( 'kapital_spoleczny', 'druga_czesc', 'step-3-2', true, badanie_ID );
              wczytaj_podkrok( 'krok3_3', 'kapital_spoleczny', 'trzecia_czesc', 3, badanie_ID );
            } else if ( step == 'krok3_3' ) {
              wyswietl_wynik_krok( 'kapital_spoleczny', 'trzecia_czesc', 'step-3-3', true, badanie_ID );
              wczytajPodsumowanieEtapu( '3-3', 'krok4_1', badanie_ID );
            } else if ( step == 'krok4_1' ) {
              wyswietl_wynik_krok( 'kapital_ekonomiczny', 'pierwsza_czesc', 'step-4-1', true, badanie_ID );
              wczytaj_podkrok( 'krok4_2', 'kapital_ekonomiczny', 'druga_czesc', 2, badanie_ID );
            } else if ( step == 'krok4_2' ) {
              wyswietl_wynik_krok( 'kapital_ekonomiczny', 'druga_czesc', 'step-4-2', true, badanie_ID );
              wczytajPodsumowanieEtapu( '4-2', 'wstepne_podsumowanie', badanie_ID );
            }
          },
          error : function ( error ) {
            console.log( error );
          }
        } );
      }

      return false;

    } );
  }

  function wczytajPodsumowanieEtapu( step, krok, badanie_ID ) {
    $.ajax( {
      'type' : 'POST',
      'dataType' : 'JSON',
      'url' : ajaxurl,
      'data' : {
        'action' : 'wczytaj_podsumowanie_etapu',
        'krok' : krok,
        'badanie_ID' : badanie_ID,
      },
      success : function ( response ) {
        $( '#page-badanie #badanie-formularz .steps-content #step-' + step ).after( response.data.form );
      },
      error : function ( error ) {
        console.log( error );
      }
    } );
  }


  $( document ).on( 'submit', '#page-badanie form.end-step', function ( e ) {
    e.preventDefault();
    e.stopImmediatePropagation();
    let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();
    wczytaj_podsumowanie_badania( badanie_ID );
  } );


  $( document ).on( 'submit', '#page-badanie form.load-new-step', function ( e ) {
    e.preventDefault();
    e.stopImmediatePropagation();
    let badanie_ID = $( this ).find( 'input[name="badanie_ID"]' ).val();
    const etap = $( this ).data( 'step' );
    if ( 'wstepne_podsumowanie' == etap ) {
      podsumowanie_wstepne( badanie_ID );
    } else if ( etap ) {
      wczytaj_krok_pierwszy_etapu( etap, badanie_ID );
    } else {
      alert( text_error_badanie );
    }
  } );

  $( document ).on( 'submit', '#page-badanie #form-podsumowanie-badania', function ( e ) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let form = $( this ).get( 0 );
    let formData = new FormData( form );
    let formError = validate_form();

    if ( formError ) {
      show_form_error();
      return;
    }

    formData.append( 'action', 'badanie_podsumowanie_form' );

    $.ajax( {
      'type' : 'POST',
      'data' : formData,
      processData : false,
      contentType : false,
      'url' : ajaxurl,
      beforeSend : function () {
        $( '#page-badanie' ).addClass( 'loading' );
      },
      success : function ( response ) {
        $( '#page-badanie' ).removeClass( 'loading' );
        $( '#badanie-formularz .steps-content form#form-podsumowanie-badania button' ).remove();
        $( '#badanie-formularz .steps-content form#form-podsumowanie-badania' ).after( response );
        $( 'input').attr('disabled', 'disabled');
        $( 'select').attr('disabled', 'disabled');
        $( 'textarea').attr('disabled', 'disabled');
        $( 'html, body' ).animate( {
          scrollTop : $( '#badanie-formularz .steps-content .thankyou' ).offset().top - 120
        }, 500 );
      },
      error : function ( error ) {
        console.error( error );
      }
    } );
  } );

  $( document ).ajaxComplete( function () {
    change_option();
    input_inne();

    submit_form( 'badanie_dodaj', 'wstepne' );

    submit_form( 'badanie_dodaj_krok', 'krok1_1' );

    submit_form( 'badanie_dodaj_krok', 'krok2_1' );

    submit_form( 'badanie_dodaj_krok', 'krok3_1' );
    submit_form( 'badanie_dodaj_krok', 'krok3_2' );
    submit_form( 'badanie_dodaj_krok', 'krok3_3' );

    submit_form( 'badanie_dodaj_krok', 'krok4_1' );
    submit_form( 'badanie_dodaj_krok', 'krok4_2' );
  } );

  $( document ).on( 'click', '.item-langs .btn', function ( e ) {
    e.preventDefault();
    let lang_input = $( this ).closest( '.item' ).find( '.answers div:first' ).html();
    $( this ).closest( '.item' ).find( '.answers' ).append( '<div>' + lang_input + '<a href="#" class="item-langs-remove"></a></div>' );
  } );

  $( document ).on( 'click', '.item-langs-remove', function ( e ) {
    e.preventDefault();
    let lang_input = $( this ).parent().remove();
  } );
} );
