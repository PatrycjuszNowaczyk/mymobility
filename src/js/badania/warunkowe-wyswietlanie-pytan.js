( function () {
  const questions_to_hide_in_step_1_1 = [
    "KROK1_1_37",
    "KROK1_1_40",
    "KROK1_1_43",
    "KROK1_1_45",
    "KROK1_1_46",
    "KROK1_1_47",
    "KROK1_1_48",
    "KROK1_1_76",
    "KROK1_1_77",
    "KROK1_1_78",
    "KROK1_1_79",
    "KROK1_1_80",
    "KROK1_1_81",
    "KROK1_1_82",
    "KROK1_1_83",
    "KROK1_1_84",
    "KROK1_1_85",
    "KROK1_1_86",
    "KROK1_1_87",
    "KROK1_1_88",
    "KROK1_1_89",
    "KROK1_1_90",
    "KROK1_1_91",
    "KROK1_1_92",
    "KROK1_1_93",
    "KROK1_1_94",
    "KROK1_1_95",
    "KROK1_1_96",
  ]

  const questions_to_hide_in_step_4_2 = [
    "KROK4_2_5",
    "KROK4_2_6",
    "KROK4_2_7",
    "KROK4_2_8",
    "KROK4_2_9",
    "KROK4_2_10",
    "KROK4_2_11",
    "KROK4_2_12",
    "KROK4_2_13",
    "KROK4_2_14"
  ];

  const types_of_input_to_reset = [
    'input[type="text"]',
    'input[type="number"]',
    'input[type="email"]',
    'select',
    'textarea'
  ];

  const types_of_input_to_uncheck = [
    'input[type="radio"]:checked',
    'input[type="checkbox"]:checked'
  ]


//----------------------------------------------------------------------------------------------------------------------

  $( document ).ready( function () {
// KROK1_1 START
    $( document ).on( 'change', 'input[type="radio"][name="KROK1_1_32"]:checked', function () {
      if ( $( this ).val() === '3' || $( this ).val() === '4' ) {
        ( function hide_questions_to_hide_in_step_2() {
          $( '.item' ).each( function () {
            if ( questions_to_hide_in_step_1_1.includes( $( this ).attr( 'data-item-id' ) ) ) {
              if ( types_of_input_to_reset.some( type => $( this ).find( type ).val() !== undefined ) ) {
                types_of_input_to_reset.forEach( type => {
                  $( this ).find( type ).val( '' );
                } );
              }

              if ( types_of_input_to_uncheck.some( type => $( this ).find( type ).is( ':checked' ) ) ) {
                types_of_input_to_uncheck.forEach( type => $( this ).find( type ).prop( 'checked', false ) );
              }

              $( this ).addClass( 'hide' );
              $( this ).hide();
            }
          } )
        } )()
      } else {
        $( '.item' ).each( function () {
          if ( questions_to_hide_in_step_1_1.includes( $( this ).attr( 'data-item-id' ) ) ) {
            $( this ).removeClass( 'hide' );
            $( this ).show();
          }
        } )
      }
    } )
// KROK1_1 END

//----------------------------------------------------------------------------------------------------------------------

// KROK4_2 START
    $( document ).on( 'change', 'input[type="checkbox"][name="KROK4_2_4[]"][value="1.no_statement"]', function () {
      if ( $( this ).is( ':checked' ) ) {
        ( function hide_questions_to_hide_in_step_2() {
          $( '.item' ).each( function () {
            if ( questions_to_hide_in_step_4_2.includes( $( this ).attr( 'data-item-id' ) ) ) {
              if ( types_of_input_to_reset.some( type => $( this ).find( type ).val() !== undefined ) ) {
                types_of_input_to_reset.forEach( type => {
                  $( this ).find( type ).val( '' );
                } );
              }

              if ( types_of_input_to_uncheck.some( type => $( this ).find( type ).is( ':checked' ) ) ) {
                types_of_input_to_uncheck.forEach( type => $( this ).find( type ).prop( 'checked', false ) );
              }

              $( this ).addClass( 'hide' );
              $( this ).hide();
            }
          } )
        } )()
      } else {
        $( '.item' ).each( function () {
          if ( questions_to_hide_in_step_4_2.includes( $( this ).attr( 'data-item-id' ) ) ) {
            $( this ).removeClass( 'hide' );
            $( this ).show();
          }
        } )
      }
    } )
// KROK4_2 END
  } )
} )();
