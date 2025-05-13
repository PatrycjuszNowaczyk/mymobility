<?php

// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

class Badania_Front extends Badanie {
  private $wpdb;
  private $table_name;

  private $page_id;
  private $page_settings;

  public function __construct() {
    global $wpdb;
    $this->wpdb       = $wpdb;
    $this->table_name = $this->wpdb->prefix . 'badania';


    // $o_badaniach = get_field('o_badaniach','options');

    // if(isset($o_badaniach['strona'])) {
    // $this->page_id = $o_badaniach['strona'];
    if ( ICL_LANGUAGE_CODE == 'pl' ) {
      $this->page_id = 25;
    } elseif ( ICL_LANGUAGE_CODE == 'uk' ) {
      $this->page_id = 991;
    } else {
      $this->page_id = 984;
    }

    $this->page_settings = get_field( 'badania', $this->page_id );
    // }

    //actions
    add_action( 'wp_head', [ $this, 'js_badanie' ] );

    add_action( 'wp_ajax_badanie_dodaj', [ $this, 'badanie_dodaj' ] );
    add_action( 'wp_ajax_nopriv_badanie_dodaj', [ $this, 'badanie_dodaj' ] );

    add_action( 'wp_ajax_badanie_dodaj_krok', [ $this, 'badanie_dodaj_krok' ] );
    add_action( 'wp_ajax_nopriv_badanie_dodaj_krok', [ $this, 'badanie_dodaj_krok' ] );

    add_action( 'wp_ajax_wczytaj_wstepne', [ $this, 'wczytaj_wstepne' ] );
    add_action( 'wp_ajax_nopriv_wczytaj_wstepne', [ $this, 'wczytaj_wstepne' ] );

    add_action( 'wp_ajax_wczytaj_badanie', [ $this, 'wczytaj_badanie' ] );
    add_action( 'wp_ajax_nopriv_wczytaj_badanie', [ $this, 'wczytaj_badanie' ] );

    add_action( 'wp_ajax_wczytaj_krok_pierwszy_etapu', [ $this, 'wczytaj_krok_pierwszy_etapu' ] );
    add_action( 'wp_ajax_nopriv_wczytaj_krok_pierwszy_etapu', [ $this, 'wczytaj_krok_pierwszy_etapu' ] );

    add_action( 'wp_ajax_wczytaj_podkrok', [ $this, 'wczytaj_podkrok' ] );
    add_action( 'wp_ajax_nopriv_wczytaj_podkrok', [ $this, 'wczytaj_podkrok' ] );

    add_action( 'wp_ajax_wyswietl_wynik', [ $this, 'wyswietl_wynik' ] );
    add_action( 'wp_ajax_nopriv_wyswietl_wynik', [ $this, 'wyswietl_wynik' ] );

    add_action( 'wp_ajax_wczytaj_podsumowanie_etapu', [ $this, 'wczytaj_podsumowanie_etapu' ] );
    add_action( 'wp_ajax_nopriv_wczytaj_podsumowanie_etapu', [ $this, 'wczytaj_podsumowanie_etapu' ] );

    add_action( 'wp_ajax_podsumowanie_wstepne', [ $this, 'podsumowanie_wstepne' ] );
    add_action( 'wp_ajax_nopriv_podsumowanie_wstepne', [ $this, 'podsumowanie_wstepne' ] );

    add_action( 'wp_ajax_podsumowanie_badania', [ $this, 'podsumowanie_badania' ] );
    add_action( 'wp_ajax_nopriv_podsumowanie_badania', [ $this, 'podsumowanie_badania' ] );

    add_action( 'wp_ajax_badanie_podsumowanie_form', [ $this, 'badanie_podsumowanie_form' ] );
    add_action( 'wp_ajax_nopriv_badanie_podsumowanie_form', [ $this, 'badanie_podsumowanie_form' ] );

    add_action( 'wp_ajax_badanie_pdf', [ $this, 'badanie_pdf' ] );
    add_action( 'wp_ajax_nopriv_badanie_pdf', [ $this, 'badanie_pdf' ] );

  }

  public function js_badanie() {
    if ( is_page_template( 'template/page-badanie.php' ) ) {

      $badania        = $this->page_settings;
      $przed_badaniem = $badania['przed_badaniem'];
      $zgoda          = $przed_badaniem['zgoda'];
      $zrozumienie    = $przed_badaniem['zrozumienie'];
      $komunikaty     = $badania['komunikaty'];
      ?>
      <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
        <?php if(isset( $zgoda['alert'] )) { ?>
        var text_zgoda_uczestnicwo = "<?= $zgoda['alert']; ?>";
        <?php } ?>
        <?php if(isset( $zrozumienie['alert'] )) { ?>
        var text_zgoda_zrozumienie = "<?= $zrozumienie['alert']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['pole_puste'] )) { ?>
        var text_error_empty = "<?= $komunikaty['pole_puste']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['opcja_pusta'] )) { ?>
        var text_error_empty_options = "<?= $komunikaty['opcja_pusta']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['nie_znaleziono_kodu_badania'] )) { ?>
        var text_error_nofind = "<?= $komunikaty['nie_znaleziono_kodu_badania']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['koniecznosc_wyboru_kroku'] )) { ?>
        var text_choose_step = "<?= $komunikaty['koniecznosc_wyboru_kroku']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['blad'] )) { ?>
        var text_error_badanie = "<?= $komunikaty['blad']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['braki_w_formularzu'] )) { ?>
        var text_error_validation = "<?= $komunikaty['braki_w_formularzu']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['skopiowanie_kodu'] )) { ?>
        var text_copy_code = "<?= $komunikaty['skopiowanie_kodu']; ?>";
        <?php } ?>
        <?php if(isset( $komunikaty['wypelnij_krok1'] )) { ?>
        var text_not_fill_step1 = "<?= $komunikaty['wypelnij_krok1']; ?>";
        <?php } ?>
        var title_wstepne = "<?= __( 'Informacje wstępne', 'migracja' ) ?>";

      </script>
      <?php
    }
  }

  private function badanie_nowe_ID() {
    $badanie_row = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}` ORDER BY badanie_ID DESC LIMIT 1" );

    if ( $badanie_row != null ) {
      $id = $badanie_row->badanie_ID + 1;
    } else {
      $id = 1;
    }

    return $id;
  }

  private function wynik_nowy_ID( $krok ) {
    $badanie_row = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}_wyniki_{$krok}` ORDER BY wynik_ID DESC LIMIT 1" );

    if ( $badanie_row != null ) {
      $id = $badanie_row->wynik_ID + 1;
    } else {
      $id = 1;
    }

    return $id;
  }

  private function generujKod( $length = 8 ) {
    $randomString = '';

    $badanie = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}` ORDER BY badanie_ID DESC LIMIT 1" );

    do {
      $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen( $characters );
      for ( $i = 0; $i < $length; $i++ ) {
        $randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
      }

      $row = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}` WHERE `badanie_code` = %s",
          array(
            $randomString,
          )
        )
      );
    } while ( ( $badanie != null ) && ( $row != null ) );

    return $randomString;
  }

  public function badanie_dodaj() {

    $krok       = $_POST['krok'];
    $krok_upper = strtoupper( $_POST['krok'] );

    $badanie_ID = $this->badanie_nowe_ID();
    $wyniki_ID  = $this->wynik_nowy_ID( $krok );
    $code       = $this->generujKod();

    $badanie_sql                           = array(
      'badanie_ID' => $badanie_ID,
    );
    $badanie_sql['badanie_code']           = $code;
    $badanie_sql['badanie_date']           = date( "Y-m-d H:i:s" );
    $badanie_sql['badanie_status']         = 'otwarte';
    $badanie_sql['badanie_wyniki_wstepne'] = $wyniki_ID;

    $wyniki_sql = array(
      'wynik_ID' => $wyniki_ID,
    );
    $result     = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY ID" );

    foreach ( $result as $row ) :
      if ( !empty( $_POST[ $krok_upper . '_' . $row->ID . '_inne' ] ) ) {
        $wyniki_sql[ $krok_upper . '_' . $row->ID ] = $_POST[ $krok_upper . '_' . $row->ID . '_inne' ];
      } else {
        if ( isset( $_POST[ $krok_upper . '_' . $row->ID ] ) ) :
          if ( !is_array( $_POST[ $krok_upper . '_' . $row->ID ] ) ) {
            $wyniki_sql[ $krok_upper . '_' . $row->ID ] = $_POST[ $krok_upper . '_' . $row->ID ];
          } else {
            $answers_implode                            = implode( "||", $_POST[ $krok_upper . '_' . $row->ID ] );
            $wyniki_sql[ $krok_upper . '_' . $row->ID ] = $answers_implode;
          }
        endif;
      }
    endforeach;

    $this->wpdb->insert( $this->table_name, $badanie_sql );
    $this->wpdb->insert( $this->table_name . '_wyniki_wstepne', $wyniki_sql );

    wp_die();
  }

  public function badanie_dodaj_krok() {
    $badanie_ID = $_POST['badanie_ID'];
    $krok       = $_POST['krok'];
    $krok_upper = strtoupper( $_POST['krok'] );
    $wyniki_ID  = $this->wynik_nowy_ID( $krok );

    $wyniki_sql = array(
      'wynik_ID' => $wyniki_ID,
    );
    $result     = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY ID" );
    foreach ( $result as $row ) :
      if ( isset( $_POST[ $krok_upper . '_' . $row->ID ] ) && is_array( $_POST[ $krok_upper . '_' . $row->ID ] ) ) {
        //checkboxy
        $checkboxes = isset( $_POST[ $krok_upper . '_' . $row->ID ] ) ? $_POST[ $krok_upper . '_' . $row->ID ] : array();
        $answers    = array();

        foreach ( $checkboxes as $checkbox ) {
          if ( $checkbox == 'inne' ) {
            $answers[] = $_POST[ $krok_upper . '_' . $row->ID . '_inne' ];
            // tu trzeba sprawdzic czy zapisuje sie "inne", bo cos jest nie tak
          } else {
            if ( isset( $checkbox ) && !empty( $checkbox ) ) {
              $answers[] = $checkbox;
            }
          }
        }

        $answers_implode = implode( "||", $answers );

        $wyniki_sql[ $krok_upper . '_' . $row->ID ] = $answers_implode;

      } else {
        if ( !empty( $_POST[ $krok_upper . '_' . $row->ID . '_inne' ] ) ) {
          $wyniki_sql[ $krok_upper . '_' . $row->ID ] = $_POST[ $krok_upper . '_' . $row->ID . '_inne' ];
        } else {
          if ( isset( $_POST[ $krok_upper . '_' . $row->ID ] ) ) :
            $wyniki_sql[ $krok_upper . '_' . $row->ID ] = $_POST[ $krok_upper . '_' . $row->ID ];
          endif;
        }
      }
    endforeach;
    $this->wpdb->insert( $this->table_name . '_wyniki_' . $krok, $wyniki_sql );

    //update
    $this->wpdb->update( $this->table_name, array( 'badanie_wyniki_' . $krok => $wyniki_ID ), array( 'badanie_ID' => $badanie_ID ) );


    $row = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    if (
      ( $row->badanie_wyniki_krok1_1 !== null )
      && ( $row->badanie_wyniki_krok2_1 !== null )
      && ( ( $row->badanie_wyniki_krok3_1 !== null ) && ( $row->badanie_wyniki_krok3_2 !== null ) && ( $row->badanie_wyniki_krok3_3 !== null ) )
      && ( ( $row->badanie_wyniki_krok4_1 !== null ) && ( $row->badanie_wyniki_krok4_2 !== null ) )
    ) {
      $this->wpdb->update( $this->table_name, array( 'badanie_status' => "zamknięte" ), array( 'badanie_ID' => $badanie_ID ) );
    }


    wp_die();
  }

  public function badanie_podsumowanie_form() {
    $badanie_ID = $_POST['badanie_ID'];

    $badania      = $this->page_settings;
    $podsumowanie = $badania['podsumowanie_badania'];

    if ( isset( $_POST['badanie_satysfakcja'] ) ) :
      $input['badanie_satysfakcja'] = $_POST['badanie_satysfakcja'];
    endif;
    if ( isset( $_POST['badanie_nastroj'] ) ) :
      $input['badanie_nastroj'] = $_POST['badanie_nastroj'];
    endif;
    if ( isset( $_POST['badanie_haslo'] ) ) :
      $input['badanie_haslo'] = $_POST['badanie_haslo'];
    endif;
    if ( isset( $_POST['badanie_pseudonim'] ) ) :
      $input['badanie_pseudonim'] = $_POST['badanie_pseudonim'];
    endif;
    if ( isset( $_POST['badanie_email'] ) ) :
      $input['badanie_email'] = $_POST['badanie_email'];
    endif;
    if ( isset( $_POST['badanie_zrodlo'] ) ) :
      $input['badanie_zrodlo'] = $_POST['badanie_zrodlo'];
    endif;
    if ( isset( $_POST['badanie_jezyk'] ) ) :
      $input['badanie_jezyk'] = $_POST['badanie_jezyk'];
    endif;

    $this->wpdb->update( $this->table_name, $input, array( 'badanie_ID' => $badanie_ID ) );

    if ( isset( $podsumowanie['podziekowanie'] ) ) {
      echo '<p class="thankyou">' . $podsumowanie['podziekowanie'] . '</p>';
      if ( isset( $_POST['badanie_nastroj'] ) && $_POST['badanie_nastroj'] < 5 ) {
        echo '<div class="list-nastroj">' . $podsumowanie['lista_wsparcia'] . '</div>';
      }
    }

    wp_die();
  }

  public function wyswietl_wynik() {
    if ( isset( $_POST['nazwa'] ) ) {
      $nazwa = $_POST['nazwa'];
    }
    if ( isset( $_POST['czesc'] ) ) {
      $czesc = $_POST['czesc'];
    }
    $section = $this->page_settings[ $nazwa ];
    $result  = $section[ $czesc ]['wynik_zwrotny'];
    $title   = $section[ $czesc ]['naglowek_wyniku'];


    if ( $_POST['step'] === 'step-1-1' ) {
      $wynik_krok1_1 = $this->badanie_wynik_1_1( $_POST['badanie_ID'] );
      if ( isset( $wynik_krok1_1['poznawcze'] ) ) {
        $result = str_replace( '{wynik_krok1_1_poznawcze}', number_format( $wynik_krok1_1['poznawcze'], 1, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok1_1['manualne'] ) ) {
        $result = str_replace( '{wynik_krok1_1_manualne}', number_format( $wynik_krok1_1['manualne'], 1, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok1_1['manualne'] ) ) {
        $result = str_replace( '{wynik_krok1_1_miekkie}', number_format( $wynik_krok1_1['miekkie'], 1, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok1_1['manualne'] ) ) {
        $result = str_replace( '{wynik_krok1_1_zyciowe}', number_format( $wynik_krok1_1['zyciowe'], 1, ',', ' ' ), $result );
      }
    }

    if ( $_POST['step'] === 'step-1-3' ) {

      $wynik_krok1_3 = $this->badanie_wynik_1_3( $_POST['badanie_ID'] );

      // ten if ponizej jest nowy, trzeba ogarnac by nie wyswietlalo wyniku gdy wszystko jest 0
      if (
        // (isset($wynik_krok1_3['dopasowanie']) && $wynik_krok1_3['dopasowanie'] != 0)
        // &&
        ( isset( $wynik_krok1_3['niedobor'] ) && $wynik_krok1_3['niedobor'] != 0 )
        &&
        ( isset( $wynik_krok1_3['nadmiar'] ) && $wynik_krok1_3['nadmiar'] != 0 )
      ) {
        // if(isset($wynik_krok1_3['dopasowanie'])) {
        //     $result = str_replace('{wynik_krok1_3_dopasowanie}', number_format($wynik_krok1_3['dopasowanie'], 2, ',', ' '), $result);
        // }
        if ( isset( $wynik_krok1_3['niedobor'] ) ) {
          $result = str_replace( '{wynik_krok1_3_niedobor}', number_format( $wynik_krok1_3['niedobor'], 2, ',', ' ' ), $result );
        } else {
          $result = str_replace( '{wynik_krok1_3_niedobor}', '0,00', $result );
        }
        if ( isset( $wynik_krok1_3['nadmiar'] ) ) {
          $result = str_replace( '{wynik_krok1_3_nadmiar}', number_format( $wynik_krok1_3['nadmiar'], 2, ',', ' ' ), $result );
        } else {
          $result = str_replace( '{wynik_krok1_3_nadmiar}', '0,00', $result );
        }
        if ( !( isset( $wynik_krok1_3['dopasowanie'] ) ) && !( isset( $wynik_krok1_3['niedobor'] ) ) && !( isset( $wynik_krok1_3['nadmiar'] ) ) ) {
          if ( $section[ $czesc ]['wynik_zwrotny_alternatywny'] ) {
            $result = $section[ $czesc ]['wynik_zwrotny_alternatywny'];
          } else {
            $result = '';
          }
        }
      } else {
        $result = '';
      }


    }

    if ( $_POST['step'] === 'step-2-1' ) {
      $wynik_krok2_1 = $this->badanie_wynik_2_1( $_POST['badanie_ID'] );
      if ( isset( $wynik_krok2_1['poczucie_skutecznosci'] ) ) {
        $result = str_replace( '{wynik_krok2_1_poczucie_skutecznosci}', number_format( $wynik_krok2_1['poczucie_skutecznosci'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok2_1['optymizm'] ) ) {
        $result = str_replace( '{wynik_krok2_1_optymizm}', number_format( $wynik_krok2_1['optymizm'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok2_1['nadzieja'] ) ) {
        $result = str_replace( '{wynik_krok2_1_nadzieja}', number_format( $wynik_krok2_1['nadzieja'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok2_1['odpornosc_psych'] ) ) {
        $result = str_replace( '{wynik_krok2_1_odpornosc_psych}', number_format( $wynik_krok2_1['odpornosc_psych'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok2_1['ogolny'] ) ) {
        $result = str_replace( '{wynik_krok2_1_ogolny}', number_format( $wynik_krok2_1['ogolny'], 2, ',', ' ' ), $result );
      }
    }

    if ( $_POST['step'] === 'step-3-2' ) {
      $wynik_krok3_2 = $this->badanie_wynik_3_2( $_POST['badanie_ID'] );
      if ( isset( $wynik_krok3_2['wsp_rodz'] ) ) {
        $result = str_replace( '{wynik_krok3_2_wsp_rodz}', number_format( $wynik_krok3_2['wsp_rodz'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok3_2['wsp_przyj'] ) ) {
        $result = str_replace( '{wynik_krok3_2_wsp_przyj}', number_format( $wynik_krok3_2['wsp_przyj'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok3_2['wsp_oz'] ) ) {
        $result = str_replace( '{wynik_krok3_2_wsp_oz}', number_format( $wynik_krok3_2['wsp_oz'], 2, ',', ' ' ), $result );
      }
      if ( isset( $wynik_krok3_2['wsp_all'] ) ) {
        $result = str_replace( '{wynik_krok3_2_wsp_all}', number_format( $wynik_krok3_2['wsp_all'], 2, ',', ' ' ), $result );
      }
    }

    $data = array();

    if ( !empty( $result ) ) {
      $data['result'] = '<div class="result ">';
      if ( isset( $title ) ) {
        $data['result'] .= '<h2 class="title">' . $title . '</h2>';
      }
      $data['result'] .= '<div class="text">';
      $data['result'] .= $result;
      $data['result'] .= '<div class="result-hide"><span>' . __( 'Zwiń wynik', 'migracja' ) . '</span></div>';
      $data['result'] .= '</div>';
      $data['result'] .= '<div class="result-show"><span>' . __( 'Rozwiń wynik', 'migracja' ) . '</span></div>';
      $data['result'] .= '</div>';
      wp_send_json_success( $data );
    } else {
      wp_send_json_error( $data );
    }
  }

  public function wczytaj_badanie() {
    if ( isset( $_POST['badanie_code'] ) ) {
      $row  = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}` WHERE `badanie_code` = %s",
          array(
            $_POST['badanie_code'],
          )
        )
      );
      $data = array();
      if ( $row != null ) {
        $data['badanie_ID'] = $row->badanie_ID;
        if (
          ( $row->badanie_wyniki_krok1_1 !== null )
          && ( $row->badanie_wyniki_krok2_1 !== null )
          && ( ( $row->badanie_wyniki_krok3_1 !== null ) && ( $row->badanie_wyniki_krok3_2 !== null ) && ( $row->badanie_wyniki_krok3_3 !== null ) )
          && ( $row->badanie_wyniki_krok4_1 !== null ) && ( $row->badanie_wyniki_krok4_2 !== null )
        ) {
          $data['wczytaj'] = 'koniec';
        } else {
          $data['wczytaj'] = 'wstepne';
        }
        wp_send_json_success( $data );
      } else {
        wp_send_json_error();
      }
    }
    wp_die();
  }

  private function badanie_filtrowanie_specjalne( $badanie_ID, $input_name ) {
    $hide_special = false;

    if ( false === empty( $badanie_ID ) ) {
      $wynik_ID = $badanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
          array(
            $badanie_ID,
          )
        )
      );

      $wyniki_wstepne = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_wyniki_wstepne` WHERE `wynik_ID` = %d",
          array(
            $badanie->badanie_wyniki_wstepne,
          )
        )
      );

      $pytania_do_ukrycia = [
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
        "KROK1_1_96"
      ];

      if ( "0" === $wyniki_wstepne->WSTEPNE_2 ) {
        if ( in_array( $input_name, $pytania_do_ukrycia ) ) {
          $hide_special = true;
        }
      }
    }

    return $hide_special;
  }

  private function lista_pytan( $krok, $badanie_ID = '' ) {
    $krok_upper = strtoupper( $krok );
    $html       = '';
    $result     = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY kolejnosc" );
    if ( $result != null ) :
      foreach ( $result as $row ) :
        $pytanie = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
            array(
              $row->pytanie_ID,
            )
          )
        );
        if ( $pytanie->pytanie_warunek ) {
          $hide = $pytanie->pytanie_warunek;
        } else {
          $hide = '';
        }

        if ( ICL_LANGUAGE_CODE == 'pl' ) {
          $pytanie_text = $this->clear_special_char( $pytanie->pytanie_pl );
          $skala_min    = $pytanie->pytanie_skala_min;
          $skala_max    = $pytanie->pytanie_skala_max;
        } elseif ( ICL_LANGUAGE_CODE == 'uk' ) {
          $pytanie_text = $this->clear_special_char( $pytanie->pytanie_uk );
          $skala_min    = $pytanie->pytanie_skala_min_uk;
          $skala_max    = $pytanie->pytanie_skala_max_uk;
        } else {
          $pytanie_text = $this->clear_special_char( $pytanie->pytanie_en );
          $skala_min    = $pytanie->pytanie_skala_min_en;
          $skala_max    = $pytanie->pytanie_skala_max_en;
        }

        $input_name = $krok_upper . '_' . $row->ID;

        // FILTROWANIE SPECJALNE
        $hide_special = $this->badanie_filtrowanie_specjalne( $badanie_ID, $input_name );

        if ( isset( $pytanie->pytanie_nieobowiazkowe ) && ( $pytanie->pytanie_nieobowiazkowe > 0 ) ) {
          $required = false;
        } else {
          $required = true;
        }


        if ( $pytanie->pytanie_typ == 'input' ) :
          $html .= $this->field_input( $input_name, $pytanie_text, $required, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'text' ) :
          $html .= $this->field_text( $input_name, $pytanie_text, $required, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'number' ) :
          $html .= $this->field_number( $input_name, $pytanie_text, $required, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'textarea' ) :
          $html .= $this->field_textarea( $input_name, $pytanie_text, $required, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'select' ) :
          $html .= $this->field_select( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'select-kraje' ) :
          $html .= $this->field_select_kraje( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'select-urodziny' ) :
          $html .= $this->field_select_urodziny( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'radio' ) :
          $html .= $this->field_radio( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'checkbox' ) :
          $html .= $this->field_checkbox( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'skala-1-5' ) :
          $html .= $this->field_skala( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, 1, 5, $skala_min, $skala_max, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'skala-1-7' ) :
          $html .= $this->field_skala( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, 1, 7, $skala_min, $skala_max, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'skala-1-6' ) :
          $html .= $this->field_skala( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, 1, 6, $skala_min, $skala_max, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'skala-1-10' ) :
          $html .= $this->field_skala( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, 1, 10, $skala_min, $skala_max, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'skala-0-4' ) :
          $html .= $this->field_skala( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, 0, 4, $skala_min, $skala_max, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'skala-0-10' ) :
          $html .= $this->field_skala( $input_name, $pytanie_text, $required, $row->pytanie_ID, $hide, 0, 10, $skala_min, $skala_max, $hide_special );
        elseif ( $pytanie->pytanie_typ == 'jezyki' ) :
          $html .= $this->field_jezyki( $input_name, $pytanie_text, $required, $hide, $hide_special );
        endif;
      endforeach;

      $html .= '<div class="line-btn">
                <button class="btn btn-blue-line">' . __( 'Zapisz', 'migracja' ) . '</button>
            </div>';

      return $html;

    endif;
  }

  public function wczytaj_wstepne() {
    $krok = $_POST['krok'];
    ?>
    <div id="step-0">
      <form action="#" method="POST" class="form-step" data-step="wstepne">
        <?= $this->lista_pytan( $krok ); ?>
      </form>
    </div>
    <?php
    wp_die();
  }

  public function wczytaj_krok_pierwszy_etapu() {
    $data = array();
    $krok = $_POST['krok'];

    $row = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $_POST['badanie_ID'],
        )
      )
    );

    if ( $_POST['krok'] == 'krok1_1' ) {
      $kapital          = $this->page_settings['kapital_ludzki'];
      $data['naglowek'] = __( 'Krok 1', 'migracja' ) . ':<br>' . __( 'Kapitał ludzki', 'migracja' );
      $etap             = $row->badanie_wyniki_krok1_1;
      $step             = 'step-1-1';
    } elseif ( $_POST['krok'] == 'krok2_1' ) {
      $kapital          = $this->page_settings['kapital_psychologiczny'];
      $data['naglowek'] = __( 'Krok 2', 'migracja' ) . ':<br>' . __( 'Kapitał psychologiczny', 'migracja' );
      $etap             = $row->badanie_wyniki_krok2_1;
      $step             = 'step-2-1';
    } elseif ( $_POST['krok'] == 'krok3_1' ) {
      $kapital          = $this->page_settings['kapital_spoleczny'];
      $data['naglowek'] = __( 'Krok 3', 'migracja' ) . ':<br>' . __( 'Kapitał społeczny', 'migracja' );
      $etap             = $row->badanie_wyniki_krok3_1;
      $step             = 'step-3-1';
    } elseif ( $_POST['krok'] == 'krok4_1' ) {
      $kapital          = $this->page_settings['kapital_ekonomiczny'];
      $data['naglowek'] = __( 'Krok 4', 'migracja' ) . ':<br>' . __( 'Kapitał ekonomiczny', 'migracja' );
      $etap             = $row->badanie_wyniki_krok4_1;
      $step             = 'step-4-1';
    }

    $data['opis'] = $kapital['wstep'];

    $title = $kapital['pierwsza_czesc']['naglowek'];
    $desc  = $kapital['pierwsza_czesc']['opis'];

    $data['form'] = '<div id="' . $step . '" class="step">';
    if ( !empty( $title ) ) :
      $data['form'] .= '<h2>' . $title . '</h2>';
    endif;
    if ( !empty( $desc ) ) :
      $data['form'] .= '<div class="desc">' . $desc . '</div>';
    endif;

    $data['form'] .= '<form action="#" method="POST" class="form-step" data-step="' . $_POST['krok'] . '">';
    $data['form'] .= '<input type="hidden" value="' . $_POST['badanie_ID'] . '" name="badanie_ID">';


    if ( $etap < 1 ) {
      $data['form'] .= $this->lista_pytan( $krok, $_POST['badanie_ID'] );
      $data['form'] .= '</form>';
      $data['form'] .= '</div>';
      wp_send_json_success( $data );
    } else {
      $data['form'] .= '</form>';
      $data['form'] .= '<div class="info">' . __( 'Ten krok masz już uzupełniony', 'migracja' ) . '</div>';
      $data['form'] .= '</div>';
      wp_send_json_error( $data );
    }
  }

  public function wczytaj_podkrok() {
    $data = array();

    if ( isset( $_POST['krok'] ) ) {
      $krok = $_POST['krok'];
    }
    if ( isset( $_POST['nazwa'] ) ) {
      $nazwa = $_POST['nazwa'];
    }
    if ( isset( $_POST['czesc'] ) ) {
      $czesc = $_POST['czesc'];
    }
    if ( isset( $_POST['krok_liczba'] ) ) {
      $krok_liczba = $_POST['krok_liczba'];
    }
    $section = $this->page_settings[ $nazwa ];
    $title   = $section[ $czesc ]['naglowek'];
    $desc    = $section[ $czesc ]['opis'];

    $row = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $_POST['badanie_ID'],
        )
      )
    );


    // sprawdzenie czy jest jakies pytanie do wyswietlenia
    $result_kroku = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY kolejnosc" );
    if ( $result_kroku != null ) :
      foreach ( $result_kroku as $result_kroku_row ) :
        $pytanie = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
            array(
              $result_kroku_row->pytanie_ID,
            )
          )
        );
        if ( $pytanie->pytanie_warunek ) {
          $hide = $pytanie->pytanie_warunek;
        }
        $krok_upper   = strtoupper( $krok );
        $input_name   = $krok_upper . '_' . $result_kroku_row->ID;
        $hide_special = $this->badanie_filtrowanie_specjalne( $_POST['badanie_ID'], $input_name );


        if ( !( $pytanie->pytanie_warunek ) && $hide_special == false ) {
          $uruchom_krok = true;
          break;
        } else {
          $uruchom_krok = false;
        }

      endforeach;
    endif;

    if ( $uruchom_krok == false ) {
      $this->wpdb->update( $this->table_name, array( 'badanie_wyniki_' . $krok => '0' ), array( 'badanie_ID' => $_POST['badanie_ID'] ) );
    }

    if ( $krok === 'krok3_2' ) {
      $warunek = $row->badanie_wyniki_krok3_2;
      $etap    = 3;
    } elseif ( $krok === 'krok3_3' ) {
      $warunek = $row->badanie_wyniki_krok3_3;
      $etap    = 3;
    } elseif ( $krok === 'krok4_2' ) {
      $warunek = $row->badanie_wyniki_krok4_2;
      $etap    = 4;
    }


    if ( $warunek < 1 && $uruchom_krok == true ) {
      $data['form'] = '<div id="step-' . $etap . '-' . $krok_liczba . '" class="step">';
      if ( !empty( $title ) ) :
        $data['form'] .= '<h2>' . $title . '</h2>';
      endif;
      if ( !empty( $desc ) ) :
        $data['form'] .= '<div class="desc">' . $desc . '</div>';
      endif;

      $data['form'] .= '<form action="#" method="POST" class="form-step" data-step="' . $krok . '">' . $this->lista_pytan( $krok, $_POST['badanie_ID'] ) . '<input type="hidden" value="' . $_POST['badanie_ID'] . '" name="badanie_ID"></form>';

      $data['form'] .= '</div>';
      wp_send_json_success( $data );
    } else {
      if ( $warunek < 1 && $uruchom_krok == false ) {
        // to się wyswietla gdy nie ma pytań i z automatu wskakuje "0"
        $data['form'] = '<div id="step-' . $etap . '-' . $krok_liczba . '" class="step">';
        $data['form'] .= '</div>';
        wp_send_json_error( $data );
      } else {
        $data['form'] = '<div id="step-' . $etap . '-' . $krok_liczba . '" class="step">';
        if ( !empty( $title ) ) :
          $data['form'] .= '<h2>' . $title . '</h2>';
        endif;
        if ( !empty( $desc ) ) :
          $data['form'] .= '<div class="desc">' . $desc . '</div>';
        endif;

        $data['form'] .= '<div class="info">' . __( 'Ten krok masz już ukończony', 'migracja' ) . '</div>';

        $data['form'] .= '</div>';
        wp_send_json_error( $data );
      }
    }
  }

  public function wczytaj_podsumowanie_etapu() {
    $data = array();

    $row = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %s",
        array(
          $_POST['badanie_ID'],
        )
      )
    );

    if (
      ( $row->badanie_wyniki_krok1_1 !== null )
      && ( $row->badanie_wyniki_krok2_1 !== null )
      && ( ( $row->badanie_wyniki_krok3_1 !== null ) && ( $row->badanie_wyniki_krok3_2 !== null ) && ( $row->badanie_wyniki_krok3_3 !== null ) )
      && ( ( $row->badanie_wyniki_krok4_1 !== null ) && ( $row->badanie_wyniki_krok4_2 !== null ) )
    ) {
      $data['form']    = '<form action="#" style="margin-top: 30px;" method="POST" class="end-step"><input type="hidden" value="' . $_POST['badanie_ID'] . '" name="badanie_ID">';
      $data['form']    .= '<div class="line-btn"><button class="btn btn-blue-line">' . __( 'Zakończ badanie', 'migracja' ) . '</button></div>';
      $data['form']    .= '</form>';
      $data['badanie'] = false;
    } else {
      $data['form']    = '<form action="#" style="margin-top: 30px;" method="POST" class="load-new-step" data-step="' . $_POST['krok'] . '"><input type="hidden" value="' . $_POST['badanie_ID'] . '" name="badanie_ID">';
      $data['form']    .= '<div class="line-btn"><button class="btn btn-blue-line">' . __( 'Przejdź do następnego etapu', 'migracja' ) . '</button></div>';
      $data['form']    .= '</form>';
      $data['badanie'] = true;
    }

    wp_send_json_success( $data );
  }

  public function podsumowanie_wstepne() {
    if ( !empty( $_POST['badanie_ID'] ) ) {
      $row        = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %s",
          array(
            $_POST['badanie_ID'],
          )
        )
      );
      $badanie_ID = $_POST['badanie_ID'];


    } else {
      $row        = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}` ORDER BY badanie_ID DESC LIMIT 1" );
      $badanie_ID = $row->badanie_ID;
    }


    $odp_wstepne = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_wstepne` WHERE `wynik_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $badania       = $this->page_settings;
    $wstepne       = $badania['wstepne'];
    $podsumowanie  = $wstepne['podsumowanie'];
    $wynik_zwrotny = $podsumowanie['wynik_zwrotny'];
    $html          = '';

    if ( $podsumowanie['naglowek'] ) {
      $html .= '<header class="header"><h1>' . $podsumowanie['naglowek'] . '</h1></header>';
    }

    $html .= '<div class="content">';

    if ( !empty( $row->badanie_code ) ) {
      if ( $podsumowanie['kod'] ) :
        $html .= '<div class="blue">' . $podsumowanie['kod'] . '</div>';
      endif;
      $html .= '<div class="code" id="skopiuj-kod"><span>' . $row->badanie_code . '</span></div>';
    }

    if ( $podsumowanie['dodatkowa_informacja'] ) {
      $html .= '<div class="desc">' . $podsumowanie['dodatkowa_informacja'] . '</div>';
    }

    if ( "0" === $odp_wstepne->WSTEPNE_2 ) {
      $html .= <<<HTML
      <div class="desc">
      $wynik_zwrotny
      </div>
      HTML;
    }

    $html .= '</div>';

    $html .= '<form action="#" id="form-uruchom-krok">';
    $html .= '<div class="menu-steps">
            <ul>
                <li>
                    <label>';
    if ( $row->badanie_wyniki_krok1_1 !== null ) {
      $html .= '<input type="radio" name="uruchom-krok" value="krok-1" class="full"><span>1. ' . __( 'Kapitał ludzki', 'migracja' ) . '</span>';
    } else {
      $html .= '<input type="radio" name="uruchom-krok" value="krok-1" checked="checked"><span>1. ' . __( 'Kapitał ludzki', 'migracja' ) . '</span>';
    }
    $html .= '</label>
                </li>
                <li>
                    <label>';
    if ( ( $row->badanie_wyniki_krok2_1 !== null ) && ( $row->badanie_wyniki_krok2_2 !== null )
         && ( $row->badanie_wyniki_krok2_3 !== null )
    ) {
      $html .= '<input type="radio" name="uruchom-krok" value="krok-2" class="full"><span>2. ' . __( 'Kapitał psychologiczny', 'migracja' ) . '</span>';
    } else {
      if ( $row->badanie_wyniki_krok1_1 !== null ) {
        $html .= '<input type="radio" name="uruchom-krok" value="krok-2" checked="checked"><span>2. ' . __( 'Kapitał psychologiczny', 'migracja' ) . '</span>';
      } else {
        $html .= '<input type="radio" name="uruchom-krok" value="krok-2"><span>2. ' . __( 'Kapitał psychologiczny', 'migracja' ) . '</span>';
      }
    }
    $html .= '</label>
                </li>
                <li>
                    <label>';
    if ( ( $row->badanie_wyniki_krok3_1 !== null ) && ( $row->badanie_wyniki_krok3_2 !== null ) && ( $row->badanie_wyniki_krok3_3 !== null ) ) {
      $html .= '<input type="radio" name="uruchom-krok" value="krok-3" class="full"><span>3. ' . __( 'Kapitał społeczny', 'migracja' ) . '</span>';
    } else {
      if (
        ( $row->badanie_wyniki_krok1_1 !== null )
        && ( $row->badanie_wyniki_krok2_1 !== null )
      ) {
        $html .= '<input type="radio" name="uruchom-krok" value="krok-3" checked="checked"><span>3. ' . __( 'Kapitał społeczny', 'migracja' ) . '</span>';
      } else {
        $html .= '<input type="radio" name="uruchom-krok" value="krok-3"><span>3. ' . __( 'Kapitał społeczny', 'migracja' ) . '</span>';
      }
    }
    $html .= '</label>
                </li>
                <li>
                    <label>';
    if ( ( $row->badanie_wyniki_krok4_1 !== null ) && ( $row->badanie_wyniki_krok4_2 !== null ) ) {
      $html .= '<input type="radio" name="uruchom-krok" value="krok-4" class="full"><span>4. ' . __( 'Kapitał ekonomiczny', 'migracja' ) . '</span>';
    } else {
      if (
        ( $row->badanie_wyniki_krok1_1 !== null )
        && ( $row->badanie_wyniki_krok2_1 !== null )
        && ( ( $row->badanie_wyniki_krok3_1 !== null ) && ( $row->badanie_wyniki_krok3_2 !== null ) && ( $row->badanie_wyniki_krok3_3 !== null ) )
      ) {
        $html .= '<input type="radio" name="uruchom-krok" value="krok-4" checked="checked"><span>4. ' . __( 'Kapitał ekonomiczny', 'migracja' ) . '</span>';
      } else {
        $html .= '<input type="radio" name="uruchom-krok" value="krok-4"><span>4. ' . __( 'Kapitał ekonomiczny', 'migracja' ) . '</span>';
      }
    }
    $html .= '</label>
                </li>
            </ul>
        </div>';

    $html .= '<div class="line-btn">
            <input name="badanie_ID" type="hidden" value="' . $badanie_ID . '">
            <button class="btn btn-blue-line">' . __( 'Kontynuuj', 'migracja' ) . '</button>
        </div>';
    $html .= '</form>';

    echo $html;

    wp_die();
  }

  public function podsumowanie_badania() {
    if ( !empty( $_POST['badanie_ID'] ) ) {
      $row        = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %s",
          array(
            $_POST['badanie_ID'],
          )
        )
      );
      $badanie_ID = $_POST['badanie_ID'];
    }

    $badania      = $this->page_settings;
    $podsumowanie = $badania['podsumowanie_badania'];
    $email        = $podsumowanie['e-mail'];

    $html = '';

    if ( $podsumowanie['naglowek'] ) {
      $html .= '<header class="header"><h1>' . $podsumowanie['naglowek'] . '</h1></header>';
    }

    $html .= '<div class="content">';

    if ( $podsumowanie['tresc'] ) {
      $html .= '<div class="desc">' . $podsumowanie['tresc'] . '</div>';
    }
    $html .= '<div class="line-btn"><a href="#" id="generuj-pdf" data-badanie-id="' . $badanie_ID . '" title="' . __( 'Pobierz plik PDF z wynikami', 'migracja' ) . '" class="btn btn-green">' . __( 'Pobierz plik PDF z wynikami', 'migracja' ) . '</a></div>';

    $html .= '</div>';

    $data['opis'] = $html;


    if ( empty( $row->badanie_email ) || empty( $row->badanie_satysfakcja ) || empty( $row->badanie_nastroj ) || empty( $row->badanie_zrodlo ) ) {
      $form = '<form action="#" id="form-podsumowanie-badania">';


      if ( !( $row->badanie_satysfakcja ) ) {
        $form .= '<div class="item">';
        $form .= '<strong class="question">' . $podsumowanie['pytanie_satysfakcji'] . '</strong>
                    <div class="answers answers-scale">';

        for ( $i = 1; $i <= 10; $i++ ) {
          $form .= '<label for="badanie_satysfakcja_' . $i . '">';
          $form .= '<strong>' . $i . '</strong>';
          $form .= '<input type="radio" name="badanie_satysfakcja" id="badanie_satysfakcja_' . $i . '" value="' . $i . '">';
          $form .= '<span></span>';
          $form .= '</label>';
        }

        $form .= '</div>';

        $form .= '<div class="values">';
        $form .= '<span>' . __( 'zdecydowanie negatywnie', 'migracja' ) . '</span>';
        $form .= '<span>' . __( 'zdecydowanie pozytywnie', 'migracja' ) . '</span>';
        $form .= '</div>';

        $form .= '</div>';
      }

      if ( $row->badanie_nastroj === null ) {
        $form .= '<div class="item">';
        if ( isset( $podsumowanie['pytanie_nastroj'] ) ) {
          $form .= '<strong class="question">' . $podsumowanie['pytanie_nastroj'] . '</strong>';
          $form .= '<div class="answers answers-scale">';

          for ( $i = 0; $i <= 10; $i++ ) {
            $form .= '<label for="badanie_nastroj_' . $i . '">';
            $form .= '<strong>' . $i . '</strong>';
            $form .= '<input type="radio" name="badanie_nastroj" id="badanie_nastroj_' . $i . '" value="' . $i . '">';
            $form .= '<span></span>';
            $form .= '</label>';
          }

          $form .= '</div>';
        }

        $form .= '<div class="values">';
        $form .= '<span>' . __( 'beznadziejny', 'migracja' ) . '</span>';
        $form .= '<span>' . __( 'znakomity', 'migracja' ) . '</span>';
        $form .= '</div>';

        $form .= '</div>';
      } elseif ( $row->badanie_nastroj < 5 ) {
        $form .= '<div class="list-nastroj">' . $podsumowanie['lista_wsparcia'] . '</div>';
      }

      if ( !( $row->badanie_zrodlo ) ) {
        while ( have_rows( 'badania', $this->page_id ) ) : the_row();
          while ( have_rows( 'podsumowanie_badania' ) ) : the_row();
            while ( have_rows( 'skad_info' ) ) : the_row();
              $form .= '<div class="item">';
              $form .= '<strong class="question">' . get_sub_field( 'pytanie' ) . '</strong>
                                <div class="answers">';
              $i    = 0;
              while ( have_rows( 'opcje' ) ) : the_row();
                $i++;
                $form .= '<label for="badanie_zrodlo-' . $i . '">';
                $form .= '<input type="radio" name="badanie_zrodlo"';
                if ( get_sub_field( 'inne' ) ) {
                  $form .= ' class="wybor-inne"';
                }
                $form .= ' value="' . get_sub_field( 'odpowiedz' ) . '" id="badanie_zrodlo-' . $i . '">';
                $form .= '<span>' . get_sub_field( 'odpowiedz' ) . '</span>';
                if ( get_sub_field( 'inne' ) ) {
                  $form .= '<input type="text" name="badanie_zrodlo_inne" class="inne">';
                }
                $form .= '</label>';
              endwhile;
              $form .= '</div>
                            </div>';
            endwhile;
          endwhile;
        endwhile;
      }

      if ( !( $row->badanie_haslo ) ) {
        $form .= '<div class="haslo">';
        if ( isset( $podsumowanie['pytanie_haslo'] ) ) {
          $form .= '<label for="badanie_haslo">' . $podsumowanie['pytanie_haslo'] . '</label>';
        }

        $form .= '<input type="text" name="badanie_haslo" placeholder="' . __( 'Hasło', 'migracja' ) . '">';

        $form .= '</div>';
      }

      if ( !( $row->badanie_email ) ) {
        $form .= '<div class="email">';
        if ( isset( $email['dodatkowa_informacja'] ) ) {
          $form .= '<label for="email-input">' . $email['tresc'] . '</label>';
        }

        $form .= '<input type="email" name="badanie_email" placeholder="' . __( 'E-mail', 'migracja' ) . '">';

        if ( isset( $email['dodatkowa_informacja'] ) ) {
          $form .= '<div class="email-add-info">' . $email['dodatkowa_informacja'] . '</div>';
        }
        $form .= '</div>';
      }


      if ( !( $row->badanie_pseudonim ) ) {
        $form .= '<div class="pseudonim">';
        if ( isset( $podsumowanie['pytanie_pseudonim'] ) ) {
          $form .= '<label for="badanie_pseudonim">' . $podsumowanie['pytanie_pseudonim'] . '</label>';
        }

        $form .= '<input type="text" name="badanie_pseudonim" placeholder="' . __( 'Pseudonim', 'migracja' ) . '">';

        $form .= '</div>';
      }


      if ( !( $row->badanie_jezyk ) ) {
        $form .= '<input name="badanie_jezyk" type="hidden" value="' . ICL_LANGUAGE_CODE . '">';
      }

      $form         .= '<div class="line-btn">
                <input name="badanie_ID" type="hidden" value="' . $badanie_ID . '"> 
                <button class="btn btn-blue-line">' . __( 'Zapisz', 'migracja' ) . '</button>
            </div>';
      $form         .= '</form>';
      $data['form'] = $form;

      wp_send_json_success( $data );
    } else {
      wp_send_json_error( $data );
    }


  }

  private function clear_special_char( $string ) {
    return str_replace( array( '\"', "\'" ), array( '"', "'" ), $string );
  }

  public function field_textarea( $krok_ID, $pytanie, $required, $hide, $hide_special = false ) {
    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">
                <textarea name="' . $krok_ID . '"></textarea>
            </div>
        </div>';

    return $html;
  }

  public function field_input( $krok_ID, $pytanie, $required, $hide, $hide_special = false ) {
    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">
                <input type="text" name="' . $krok_ID . '" placeholder="' . __( 'Wpisz', 'migracja' ) . '...">
            </div>
        </div>';

    return $html;
  }

  public function field_text( $krok_ID, $pytanie, $required, $hide, $hide_special = false ) {
    $html = '<div data-item-id="' . $krok_ID . '" class="item item-text';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
        </div>';

    return $html;
  }

  public function field_number( $krok_ID, $pytanie, $required, $hide, $hide_special = false ) {
    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">
                <input type="number" min="0" name="' . $krok_ID . '" placeholder="' . __( 'Wpisz', 'migracja' ) . '...">
            </div>
        </div>';

    return $html;
  }

  public function field_select( $krok_ID, $pytanie, $required, $pytanie_ID, $hide, $hide_special = false ) {
    $odpowiedzi = $this->wpdb->get_results(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `pytanie_ID` = %d",
        array(
          $pytanie_ID,
        )
      )
    );
    $html       = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">';


    $html  .= '<select name="' . $krok_ID . '" id="' . $krok_ID . '">';
    $count = 0;
    foreach ( $odpowiedzi as $odpowiedz ) {
      $count++;


      if ( ICL_LANGUAGE_CODE == 'pl' ) {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl );
      } elseif ( ICL_LANGUAGE_CODE == 'uk' ) {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_uk );
      } else {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_en );
      }


      if ( $count == 1 ) {
        $html .= '<option value="" selected="true" disabled="disabled">-- ' . $odpowiedz_text . ' --</option>';
      } else {
        $html .= '<option value="' . $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl ) . '">' . $odpowiedz_text . '</option>';
      }
    }
    $html .= '</select>';

    // special input
    if ( $krok_ID == 'WSTEPNE_1' ) {
      $html .= '<input name="' . $krok_ID . '_inne" id="' . $krok_ID . '_inne" placeholder="' . __( 'Uzupełnij', 'migracja' ) . '" type="text" class="inne" style="display: none">';
    }

    $html .= '</div>
        </div>';

    return $html;
  }

  public function field_select_kraje( $krok_ID, $pytanie, $required, $pytanie_ID, $hide, $hide_special = false ) {

    if ( ICL_LANGUAGE_CODE == 'pl' ) {
      $kraje = array(
        "Polska",
        "Ukraina",
        "Afganistan",
        "Albania",
        "Algieria",
        "Andora",
        "Angola",
        "Antigua i Barbuda",
        "Arabia Saudyjska",
        "Argentyna",
        "Armenia",
        "Australia",
        "Austria",
        "Azerbejdżan",
        "Bahamy",
        "Bahrajn",
        "Bangladesz",
        "Barbados",
        "Belgia",
        "Belize",
        "Benin",
        "Bhutan",
        "Białoruś",
        "Boliwia",
        "Bośnia i Hercegowina",
        "Botswana",
        "Brazylia",
        "Brunei",
        "Bułgaria",
        "Burkina Faso",
        "Burundi",
        "Chile",
        "Chiny",
        "Chorwacja",
        "Cypr",
        "Czad",
        "Czarnogóra",
        "Czechy",
        "Dania",
        "Demokratyczna Republika Konga",
        "Dominika",
        "Dominikana",
        "Dżibuti",
        "Egipt",
        "Ekwador",
        "Erytrea",
        "Estonia",
        "Eswatini",
        "Etiopia",
        "Fidżi",
        "Filipiny",
        "Finlandia",
        "Francja",
        "Gabon",
        "Gambia",
        "Ghana",
        "Grecja",
        "Grenada",
        "Gruzja",
        "Gujana",
        "Gwatemala",
        "Gwinea",
        "Gwinea Bissau",
        "Gwinea Równikowa",
        "Haiti",
        "Hiszpania",
        "Holandia",
        "Honduras",
        "Indie",
        "Indonezja",
        "Irak",
        "Iran",
        "Irlandia",
        "Islandia",
        "Izrael",
        "Jamajka",
        "Japonia",
        "Jemen",
        "Jordania",
        "Kambodża",
        "Kamerun",
        "Kanada",
        "Katar",
        "Kazachstan",
        "Kenia",
        "Kirgistan",
        "Kiribati",
        "Kolumbia",
        "Komory",
        "Kongo",
        "Korea Południowa",
        "Korea Północna",
        "Kostaryka",
        "Kuba",
        "Kuwejt",
        "Laos",
        "Lesotho",
        "Liban",
        "Liberia",
        "Libia",
        "Liechtenstein",
        "Litwa",
        "Luksemburg",
        "Łotwa",
        "Macedonia Północna",
        "Madagaskar",
        "Malawi",
        "Malediwy",
        "Malezja",
        "Mali",
        "Malta",
        "Maroko",
        "Mauretania",
        "Mauritius",
        "Meksyk",
        "Mikronezja",
        "Mjanma",
        "Mołdawia",
        "Monako",
        "Mongolia",
        "Mozambik",
        "Namibia",
        "Nauru",
        "Nepal",
        "Niemcy",
        "Niger",
        "Nigeria",
        "Nikaragua",
        "Norwegia",
        "Nowa Zelandia",
        "Oman",
        "Pakistan",
        "Palau",
        "Panama",
        "Papua-Nowa Gwinea",
        "Paragwaj",
        "Peru",
        "Południowa Afryka",
        "Portugalia",
        "Republika Środkowoafrykańska",
        "Republika Zielonego Przylądka",
        "Rosja",
        "Rumunia",
        "Rwanda",
        "Saint Kitts i Nevis",
        "Saint Lucia",
        "Saint Vincent i Grenadyny",
        "Salwador",
        "Samoa",
        "San Marino",
        "Senegal",
        "Serbia",
        "Seszele",
        "Sierra Leone",
        "Singapur",
        "Słowacja",
        "Słowenia",
        "Somalia",
        "Sri Lanka",
        "Stany Zjednoczone",
        "Sudan",
        "Sudan Południowy",
        "Surinam",
        "Syria",
        "Szwajcaria",
        "Szwecja",
        "Tadżykistan",
        "Tajlandia",
        "Tanzania",
        "Timor Wschodni",
        "Togo",
        "Tonga",
        "Trynidad i Tobago",
        "Tunezja",
        "Turcja",
        "Turkmenistan",
        "Tuvalu",
        "Uganda",
        "Urugwaj",
        "Uzbekistan",
        "Vanuatu",
        "Watykan",
        "Wenezuela",
        "Węgry",
        "Wielka Brytania",
        "Wietnam",
        "Włochy",
        "Wybrzeże Kości Słoniowej",
        "Wyspy Marshalla",
        "Wyspy Salomona",
        "Wyspy Świętego Tomasza i Książęca",
        "Zambia",
        "Zimbabwe",
        "Zjednoczone Emiraty Arabskie"
      );
    } elseif ( ICL_LANGUAGE_CODE == 'uk' ) {
      $kraje = array(
        "Польща",
        "Україна",
        "Австралія",
        "Австрія",
        "Азербайджан",
        "Албанія",
        "Алжир",
        "Ангола",
        "Андорра",
        "Антигуа і Барбуда",
        "Аргентина",
        "Афганістан",
        "Багамські острови",
        "Бангладеш",
        "Барбадос",
        "Бахрейн",
        "Беліз",
        "Бельгія",
        "Бенін",
        "Берег Слонової Кістки",
        "Білорусь",
        "Болгарія",
        "Болівія",
        "Боснія і Герцеговина",
        "Ботсвана",
        "Бразилія",
        "Бруней",
        "Буркіна Фасо",
        "Бурунді",
        "Бутан",
        "В'єтнам",
        "Вануату",
        "Ватикан",
        "Венесуела",
        "Вірменія",
        "Габон",
        "Гаїті",
        "Гайана",
        "Гамбія",
        "Гана",
        "Гватемала",
        "Гвінея",
        "Гвінея-Бісау",
        "Гондурас",
        "Гранада",
        "Греція",
        "Грузія",
        "Данія",
        "Демократична Республіка Конго",
        "Джібуті",
        "Домініка",
        "Домініканська республіка",
        "Еквадор",
        "Екваторіальна Гвінея",
        "Еритрея",
        "Есватіні",
        "Естонія",
        "Ефіопія",
        "Єгипет",
        "Ємен",
        "Замбія",
        "Зімбабве",
        "Ізраїль",
        "Індія",
        "Індонезія",
        "Ірак",
        "Іран",
        "Ірландія",
        "Ісландія",
        "Іспанія",
        "Італія",
        "Йорданія",
        "Йти",
        "Кабо-Верде",
        "Казахстан",
        "Камбоджа",
        "Камерун",
        "Канада",
        "Катар",
        "Кенія",
        "Киргизстан",
        "Китай",
        "Кіпр",
        "Кірібаті",
        "Колумбія",
        "Коморські острови",
        "Конго",
        "Коста-Ріка",
        "Куба",
        "Кувейт",
        "Лаос",
        "Латвія",
        "Лесото",
        "Литва",
        "Ліберія",
        "Ліван",
        "Лівія",
        "Ліхтенштейн",
        "Люксембург",
        "М'янма",
        "Маврикій",
        "Мавританія",
        "Мадагаскар",
        "Малаві",
        "Малайзія",
        "Малі",
        "Мальдіви",
        "Мальта",
        "Марокко",
        "Маршаллові острови",
        "Мексика",
        "Мікронезія",
        "Мозамбік",
        "Молдова",
        "Монако",
        "Монголія",
        "Намібія",
        "Науру",
        "Непал",
        "Нігер",
        "Нігерія",
        "Нідерланди",
        "Нікарагуа",
        "Німеччина",
        "Нова Зеландія",
        "Норвегія",
        "Об'єднане Королівство",
        "Об'єднані Арабські Емірати",
        "Оман",
        "Пакистан",
        "Палау",
        "Панама",
        "Папуа-Нова Гвінея",
        "Парагвай",
        "Перу",
        "Південна Африка",
        "Південна Корея",
        "Південний Судан",
        "Північна Корея",
        "Північна Македонія",
        "Португалія",
        "Росія",
        "Руанда",
        "Румунія",
        "Сальвадор",
        "Самоа",
        "Сан-Марино",
        "Сан-Томе і Прінсіпі",
        "Саудівська Аравія",
        "Сейшельські острови",
        "Сенегал",
        "Сент-Вінсент і Гренадини",
        "Сент-Кітс і Невіс",
        "Сент-Люсія",
        "Сербія",
        "Сирія",
        "Сінгапур",
        "Словаччина",
        "Словенія",
        "Соломонові острови",
        "Сомалі",
        "Сполучені Штати",
        "Судан",
        "Сурінам",
        "Східний Тимор",
        "Сьєрра-Леоне",
        "Таджикистан",
        "Таїланд",
        "Танзанія",
        "Тонга",
        "Тринідад і Тобаго",
        "Тувалу",
        "Туніс",
        "Туреччина",
        "Туркменістан",
        "Уганда",
        "Угорщина",
        "Узбекистан",
        "Уругвай",
        "Фіджі",
        "Філіппіни",
        "Фінляндія",
        "Франція",
        "Хорватія",
        "Центральноафриканська Республіка",
        "Чад",
        "Чеська Республіка",
        "Чилі",
        "Чорногорія",
        "Швейцарія",
        "Швеція",
        "Шрі Ланка",
        "Ямайка",
        "Японія"
      );
    } else {
      $kraje = array(
        "Poland",
        "Ukraine",
        "Afghanistan",
        "Albania",
        "Algeria",
        "Andorra",
        "Angola",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Australia",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bhutan",
        "Bolivia",
        "Bosnia and Herzegovina",
        "Botswana",
        "Brazil",
        "Brunei",
        "Bulgaria",
        "Burkina Faso",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Canada",
        "Cape Verde",
        "Central African Republic",
        "Chad",
        "Chile",
        "China",
        "Colombia",
        "Comoros",
        "Congo",
        "Costa Rica",
        "Croatia",
        "Cuba",
        "Cyprus",
        "Czech Republic",
        "Democratic Republic of Congo",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "East Timor",
        "Ecuador",
        "Egypt",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Estonia",
        "Eswatini",
        "Ethiopia",
        "Fiji",
        "Finland",
        "France",
        "Gabon",
        "Gambia",
        "Georgia",
        "Germany",
        "Ghana",
        "Granada",
        "Greece",
        "Guatemala",
        "Guinea",
        "Guinea-Bissau",
        "Guyana",
        "Haiti",
        "Honduras",
        "Hungary",
        "Iceland",
        "India",
        "Indonesia",
        "Iran",
        "Iraq",
        "Ireland",
        "Israel",
        "Italy",
        "Ivory Coast",
        "Jamaica",
        "Japan",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Kuwait",
        "Kyrgyzstan",
        "Laos",
        "Latvia",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libya",
        "Liechtenstein",
        "Lithuania",
        "Luxembourg",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Mauritania",
        "Mauritius",
        "Mexico",
        "Micronesia",
        "Moldova",
        "Monaco",
        "Mongolia",
        "Montenegro",
        "Morocco",
        "Mozambique",
        "Myanmar",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "North Korea",
        "North Macedonia",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Portugal",
        "Qatar",
        "Romania",
        "Russia",
        "Rwanda",
        "Saint Kitts and Nevis",
        "Saint Lucia",
        "Saint Vincent and the Grenadines",
        "Samoa",
        "San Marino",
        "São Tomé and Príncipe",
        "Saudi Arabia",
        "Senegal",
        "Serbia",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Korea",
        "Spain",
        "Sri Lanka",
        "Sudan",
        "Sudan South",
        "Suriname",
        "Sweden",
        "Switzerland",
        "Syria",
        "Tajikistan",
        "Tanzania",
        "Thailand",
        "Togo",
        "Tonga",
        "Trinidad and Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Tuvalu",
        "Uganda",
        "United Arab Emirates",
        "United Kingdom",
        "United States",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Vatican",
        "Venezuela",
        "Vietnam",
        "Yemen",
        "Zambia",
        "Zimbabwe"
      );
    }

    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">';
    $html .= '<select name="' . $krok_ID . '" id="' . $krok_ID . '">';
    $html .= '<option selected="true" value="" disabled="disabled">-- ' . __( 'Wybierz kraj', 'migracja' ) . ' --</option>';
    foreach ( $kraje as $kraj ) {
      $html .= '<option value="' . $kraj . '">' . $kraj . '</option>';
    }
    $html .= '</select>';

    $html .= '</div>
        </div>';

    return $html;
  }

  public function field_select_urodziny( $krok_ID, $pytanie, $required, $pytanie_ID, $hide, $hide_special = false ) {
    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">';
    $html .= '<select name="' . $krok_ID . '" id="' . $krok_ID . '">';
    $html .= '<option value="" selected="true" disabled="disabled">-- ' . __( 'Wybierz rok', 'migracja' ) . ' --</option>';
    for ( $i = 2022; $i >= 1920; $i-- ) {
      $html .= '<option value="' . $i . '">' . $i . '</option>';
    }
    $html .= '</select>';

    $html .= '</div>
        </div>';

    return $html;
  }

  public function field_radio( $krok_ID, $pytanie, $required, $pytanie_ID, $hide, $hide_special = false ) {
    $odpowiedzi = $this->wpdb->get_results(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `pytanie_ID` = %d",
        array(
          $pytanie_ID,
        )
      )
    );

    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">';

    $i = 0;
    foreach ( $odpowiedzi as $odpowiedz ) {
      $i++;
      $html .= '<label for="' . $krok_ID . '_' . $i . '">';
      $html .= '<input type="radio" name="' . $krok_ID . '"';
      if ( $odpowiedz->odpowiedz_inne ) {
        $html .= ' class="wybor-inne"';
      }
      if ( $odpowiedz->odpowiedz_wartosc != null ) {
        $value = $odpowiedz->odpowiedz_wartosc;
      } else {
        $value = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl );
      }
      if ( $odpowiedz->odpowiedz_inne ) {
        $html .= ' value="inne"';
      } else {
        $html .= ' value="' . $value . '"';
      }
      $html .= ' id="' . $krok_ID . '_' . $i . '" data-odpowiedz-numer="' . $odpowiedz->odpowiedz_ID . '">';

      if ( ICL_LANGUAGE_CODE == 'pl' ) {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl );
      } elseif ( ICL_LANGUAGE_CODE == 'uk' ) {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_uk );
      } else {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_en );
      }

      $html .= '<span>' . $this->clear_special_char( $odpowiedz_text ) . '</span>';

      if ( $odpowiedz->odpowiedz_inne ) {
        $html .= '<input type="text" name="' . $krok_ID . '_inne" class="inne">';
      }
      $html .= '</label>';
    }

    $html .= '</div>
        </div>';

    return $html;
  }

  public function field_checkbox( $krok_ID, $pytanie, $required, $pytanie_ID, $hide, $hide_special = false ) {
    $odpowiedzi = $this->wpdb->get_results(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `pytanie_ID` = %d",
        array(
          $pytanie_ID,
        )
      )
    );

    $html = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">';

    $i = 0;
    foreach ( $odpowiedzi as $odpowiedz ) {
      $i++;
      $html .= '<label for="' . $krok_ID . '_' . $i . '">';
      $html .= '<input type="checkbox" name="' . $krok_ID . '[]"';
      if ( $odpowiedz->odpowiedz_inne ) {
        $html .= ' class="wybor-inne"';
      }
      if ( $odpowiedz->odpowiedz_wartosc != null ) {
        $value = $i . '.' . $odpowiedz->odpowiedz_wartosc;
      } else {
        $value = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl );
      }
      if ( $odpowiedz->odpowiedz_inne ) {
        $html .= ' value="inne"';
      } else {
        $html .= ' value="' . $value . '"';
      }
      $html .= ' id="' . $krok_ID . '_' . $i . '" data-odpowiedz-numer="' . $odpowiedz->odpowiedz_ID . '">';

      if ( ICL_LANGUAGE_CODE == 'pl' ) {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl );
      } elseif ( ICL_LANGUAGE_CODE == 'uk' ) {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_uk );
      } else {
        $odpowiedz_text = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_en );
      }

      $html .= '<span>' . $this->clear_special_char( $odpowiedz_text ) . '</span>';
      if ( $odpowiedz->odpowiedz_inne ) {
        $html .= '<input type="text" name="' . $krok_ID . '_inne" class="inne inne_checkbox">';
      }
      $html .= '</label>';
    }

    $html .= '</div>
        </div>';

    return $html;
  }

  public function field_skala( $krok_ID, $pytanie, $required, $pytanie_ID, $hide, $skala_min, $skala_max, $skala_min_text, $skala_max_text, $hide_special = false ) {
    $odpowiedzi = $this->wpdb->get_results(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `pytanie_ID` = %d",
        array(
          $pytanie_ID,
        )
      )
    );
    $html       = '<div data-item-id="' . $krok_ID . '" class="item';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers answers-scale">';

    for ( $i = $skala_min; $i <= $skala_max; $i++ ) {
      $html .= '<label for="' . $krok_ID . '_' . $i . '">';
      $html .= '<strong>' . $i . '</strong>';
      $html .= '<input type="radio" name="' . $krok_ID . '" value="' . $i . '" id="' . $krok_ID . '_' . $i . '">';
      $html .= '<span></span>';
      $html .= '</label>';
    }

    $html .= '</div>';

    if ( $skala_min_text || $skala_max_text ) :
      $html .= '<div class="values">';
      $html .= '<span>' . $this->clear_special_char( $skala_min_text ) . '</span>';
      $html .= '<span>' . $this->clear_special_char( $skala_max_text ) . '</span>';
      $html .= '</div>';
    endif;

    $html .= '</div>';

    return $html;

  }

  public function field_jezyki( $krok_ID, $pytanie, $required, $hide, $hide_special = false ) {
    $html = '<div data-item-id="' . $krok_ID . '" class="item item-langs';
    if ( $required == false ) {
      $html .= ' no-required';
    }
    if ( $hide || $hide_special ) {
      $html .= ' hide';
      if ( $hide_special ) {
        $html .= ' hide_special';
      }
      $html .= '" style="display: none;"';
      if ( !$hide_special ) {
        $html .= ' data-show-question="[' . $hide . ']';
      }
    }
    $html .= '">';
    if ( $pytanie ) :
      $html .= '<strong class="question">' . $this->clear_special_char( $pytanie );
      if ( $required != false ) {
        $html .= '*';
      }
      $html .= '</strong>';
    endif;
    $html .= '
            <div class="answers">
                <div><input type="text" name="' . $krok_ID . '[]" placeholder="' . __( 'Wpisz język', 'migracja' ) . '..."></div>
            </div>
            <div class="add-button" style="padding-top: 10px;">
                <a href="#" class="btn btn-blue" title="' . __( 'Dodaj język', 'migracja' ) . '">' . __( 'Dodaj język', 'migracja' ) . '</a>
            </div>
        </div>';

    return $html;
  }

  public function badanie_pdf() {
    if ( isset( $_POST['badanie_ID'] ) ) {
      $badanie_ID = $_POST['badanie_ID'];
      $logo       = 'logo-moja-migracja.jpg';
      $html       = '';

      require_once( get_template_directory() . '/php/TCPDF/examples/tcpdf_include.php' );

      // create new PDF document
      $pdf = new MyTCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

      // set document information
      $pdf->setCreator( PDF_CREATOR );
      $pdf->setAuthor( __( 'Moja Migracja', 'migracja' ) );
      $pdf->setTitle( __( 'Moja Migracja - Wynik twojego badanie', 'migracja' ) );
      $pdf->setSubject( __( 'Wyniki', 'migracja' ) );
      // $pdf->setKeywords('TCPDF, PDF, example, test, guide');

      // set default header data
      // $pdf->setHeaderData($logo, 40, '', "\n\n\n", array(0,0,0), array(217, 217, 217));

      $pdf->setFooterData( array( 0, 0, 0 ), array( 217, 217, 217 ) );

      // set header and footer fonts
      $pdf->setHeaderFont( array( PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
      $pdf->setFooterFont( array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );

      // set default monospaced font
      $pdf->setDefaultMonospacedFont( PDF_FONT_MONOSPACED );

      // set margins
      $pdf->setMargins( PDF_MARGIN_LEFT, 33, PDF_MARGIN_RIGHT );
      $pdf->setHeaderMargin( PDF_MARGIN_HEADER );
      $pdf->setFooterMargin( PDF_MARGIN_FOOTER );

      // set auto page breaks
      $pdf->setAutoPageBreak( true, PDF_MARGIN_BOTTOM );

      // set image scale factor
      $pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );

      // set some language-dependent strings (optional)
      if ( @file_exists( get_template_directory() . '/php/TCPDF/examples/lang/pol.php' ) ) {
        require_once( get_template_directory() . '/php/TCPDF/examples/lang/pol.php' );
        $pdf->setLanguageArray( $l );
      }

      // ---------------------------------------------------------

      // set font
      $pdf->setFont( 'dejavusans', '', 12 );

      // add a page
      $pdf->AddPage();

      // ---------------------------------------------------------

      $name_1 = '<h1 style="color: #059f8e; text-align: center; font-size: 21px;">1. ' . __( 'Kapitał ludzki', 'migracja' ) . '</h1>';
      $pdf->writeHTML( $name_1, true, false, true, false, '' );
      $pdf->Ln( 2 );

      $kapital_ludzki = $this->page_settings['kapital_ludzki'];
      if ( isset( $kapital_ludzki['pierwsza_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_ludzki = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">1.1 ' . __( 'Kompetencje', 'migracja' ) . '</h2>';
        $result_kapital_ludzki .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_ludzki['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_ludzki .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_ludzki['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_ludzki .= $kapital_ludzki['pierwsza_czesc']['wynik_zwrotny'];

        $result_kapital_ludzki .= '</div>';


        if ( isset( $result_kapital_ludzki ) ) {
          $wynik_krok1_1 = $this->badanie_wynik_1_1( $badanie_ID );
          if ( isset( $wynik_krok1_1['poznawcze'] ) ) {
            $result_kapital_ludzki = str_replace( '{wynik_krok1_1_poznawcze}', number_format( $wynik_krok1_1['poznawcze'], 1, ',', ' ' ), $result_kapital_ludzki );
          }
          if ( isset( $wynik_krok1_1['manualne'] ) ) {
            $result_kapital_ludzki = str_replace( '{wynik_krok1_1_manualne}', number_format( $wynik_krok1_1['manualne'], 1, ',', ' ' ), $result_kapital_ludzki );
          }
          if ( isset( $wynik_krok1_1['manualne'] ) ) {
            $result_kapital_ludzki = str_replace( '{wynik_krok1_1_miekkie}', number_format( $wynik_krok1_1['miekkie'], 1, ',', ' ' ), $result_kapital_ludzki );
          }
          if ( isset( $wynik_krok1_1['manualne'] ) ) {
            $result_kapital_ludzki = str_replace( '{wynik_krok1_1_zyciowe}', number_format( $wynik_krok1_1['zyciowe'], 1, ',', ' ' ), $result_kapital_ludzki );
          }
        }

      }

      if ( isset( $kapital_ludzki['druga_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_ludzki_1_2 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">1.2 ' . __( 'Formalny kapitał ludzki', 'migracja' ) . '</h2>';
        $result_kapital_ludzki_1_2 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_ludzki['druga_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_ludzki_1_2 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_ludzki['druga_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_ludzki_1_2 .= $kapital_ludzki['druga_czesc']['wynik_zwrotny'];

        $result_kapital_ludzki_1_2 .= '</div>';

      }


      // $wynik_krok1_3 = $this->badanie_wynik_1_3($badanie_ID);
      // if((isset($wynik_krok1_3['dopasowanie']) && $wynik_krok1_3['dopasowanie'] != 0) && (isset($wynik_krok1_3['niedobor']) && $wynik_krok1_3['niedobor'] != 0) && (isset($wynik_krok1_3['nadmiar']) && $wynik_krok1_3['nadmiar'] != 0)) :
      //     // if( isset($wynik_krok1_3['dopasowanie']) && isset($wynik_krok1_3['niedobor']) && isset($wynik_krok1_3['nadmiar']) ) {
      //     if(isset($kapital_ludzki['trzecia_czesc']['wynik_zwrotny'])) {


      //         $result_kapital_ludzki_1_3 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">1.3 '. __('Dopasowanie kompetencyjne', 'migracja') . '</h2>';
      //         $result_kapital_ludzki_1_3 .= '<div style="font-size: 13px;">';

      //         if(isset($kapital_ludzki['trzecia_czesc']['naglowek_wyniku'])) {
      //             $result_kapital_ludzki_1_3 .= '<p style="font-weight: bold; color: #059f8e;">'. $kapital_ludzki['trzecia_czesc']['naglowek_wyniku'] . '</p>';
      //         }

      //         $result_kapital_ludzki_1_3 .= $kapital_ludzki['trzecia_czesc']['wynik_zwrotny'];

      //         if(isset($wynik_krok1_3['dopasowanie'])) {
      //             $result_kapital_ludzki_1_3 = str_replace('{wynik_krok1_3_dopasowanie}', number_format($wynik_krok1_3['dopasowanie'], 2, ',', ' '), $result_kapital_ludzki_1_3);
      //         }
      //         if(isset($wynik_krok1_3['niedobor'])) {
      //             $result_kapital_ludzki_1_3 = str_replace('{wynik_krok1_3_niedobor}', number_format($wynik_krok1_3['niedobor'], 2, ',', ' '), $result_kapital_ludzki_1_3);
      //         }
      //         if(isset($wynik_krok1_3['nadmiar'])) {
      //             $result_kapital_ludzki_1_3 = str_replace('{wynik_krok1_3_nadmiar}', number_format($wynik_krok1_3['nadmiar'], 2, ',', ' '), $result_kapital_ludzki_1_3);
      //         }

      //         $result_kapital_ludzki_1_3 .= '</div>';

      //     }
      // endif;

      // }

      // ---------------------------------------------------------


      if ( isset( $result_kapital_ludzki ) && $result_kapital_ludzki ) {
        $html .= $result_kapital_ludzki;
      }
      if ( isset( $result_kapital_ludzki_1_2 ) && $result_kapital_ludzki_1_2 ) {
        $html .= $result_kapital_ludzki_1_2;
      }
      // if(isset($result_kapital_ludzki_1_3) && $result_kapital_ludzki_1_3) {
      //     $html .= $result_kapital_ludzki_1_3;
      // }

      $pdf->writeHTML( $html, true, false, true, false, '' );


      // ---------------------------------------------------------


      $name_2 = '<h1 style="color: #059f8e; text-align: center; font-size: 21px;">2. ' . __( 'Kapitał psychologiczny', 'migracja' ) . '</h1>';
      $pdf->writeHTML( $name_2, true, false, true, false, '' );
      $pdf->Ln( 2 );

      $kapital_psychologiczny = $this->page_settings['kapital_psychologiczny'];
      if ( isset( $kapital_psychologiczny['pierwsza_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_psychologiczny_2_1 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">2.1 ' . __( 'Kapitał psychologiczny', 'migracja' ) . '</h2>';
        $result_kapital_psychologiczny_2_1 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_psychologiczny['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_psychologiczny_2_1 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_psychologiczny['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_psychologiczny_2_1 .= $kapital_psychologiczny['pierwsza_czesc']['wynik_zwrotny'];

        $result_kapital_psychologiczny_2_1 .= '</div>';


        if ( isset( $result_kapital_psychologiczny_2_1 ) ) {
          $wynik_krok2_1 = $this->badanie_wynik_2_1( $badanie_ID );
          if ( isset( $wynik_krok2_1['poczucie_skutecznosci'] ) ) {
            $result_kapital_psychologiczny_2_1 = str_replace( '{wynik_krok2_1_poczucie_skutecznosci}', number_format( $wynik_krok2_1['poczucie_skutecznosci'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_1 );
          }
          if ( isset( $wynik_krok2_1['optymizm'] ) ) {
            $result_kapital_psychologiczny_2_1 = str_replace( '{wynik_krok2_1_optymizm}', number_format( $wynik_krok2_1['optymizm'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_1 );
          }
          if ( isset( $wynik_krok2_1['nadzieja'] ) ) {
            $result_kapital_psychologiczny_2_1 = str_replace( '{wynik_krok2_1_nadzieja}', number_format( $wynik_krok2_1['nadzieja'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_1 );
          }
          if ( isset( $wynik_krok2_1['odpornosc_psych'] ) ) {
            $result_kapital_psychologiczny_2_1 = str_replace( '{wynik_krok2_1_odpornosc_psych}', number_format( $wynik_krok2_1['odpornosc_psych'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_1 );
          }
          if ( isset( $wynik_krok2_1['ogolny'] ) ) {
            $result_kapital_psychologiczny_2_1 = str_replace( '{wynik_krok2_1_ogolny}', number_format( $wynik_krok2_1['ogolny'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_1 );
          }
        }

      }

      if ( isset( $kapital_psychologiczny['druga_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_psychologiczny_2_2 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">2.2 ' . __( 'Osobowość', 'migracja' ) . '</h2>';
        $result_kapital_psychologiczny_2_2 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_psychologiczny['druga_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_psychologiczny_2_2 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_psychologiczny['druga_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_psychologiczny_2_2 .= $kapital_psychologiczny['druga_czesc']['wynik_zwrotny'];

        $result_kapital_psychologiczny_2_2 .= '</div>';

        $wynik_krok2_2 = $this->badanie_wynik_2_2( $badanie_ID );
        if ( isset( $wynik_krok2_2['ekstrawersja'] ) ) {
          $result_kapital_psychologiczny_2_2 = str_replace( '{wynik_krok2_2_ekstrawersja}', number_format( $wynik_krok2_2['ekstrawersja'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_2 );
        }
        if ( isset( $wynik_krok2_2['ugodowosc'] ) ) {
          $result_kapital_psychologiczny_2_2 = str_replace( '{wynik_krok2_2_ugodowosc}', number_format( $wynik_krok2_2['ugodowosc'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_2 );
        }
        if ( isset( $wynik_krok2_2['sumiennosc'] ) ) {
          $result_kapital_psychologiczny_2_2 = str_replace( '{wynik_krok2_2_sumiennosc}', number_format( $wynik_krok2_2['sumiennosc'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_2 );
        }
        if ( isset( $wynik_krok2_2['neurotyzm'] ) ) {
          $result_kapital_psychologiczny_2_2 = str_replace( '{wynik_krok2_2_neurotyzm}', number_format( $wynik_krok2_2['neurotyzm'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_2 );
        }
        if ( isset( $wynik_krok2_2['wyobraznia'] ) ) {
          $result_kapital_psychologiczny_2_2 = str_replace( '{wynik_krok2_2_wyobraznia}', number_format( $wynik_krok2_2['wyobraznia'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_2 );
        }


      }

      if ( isset( $kapital_psychologiczny['trzecia_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_psychologiczny_2_3 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">2.3 ' . __( 'Refleksyjność', 'migracja' ) . '</h2>';
        $result_kapital_psychologiczny_2_3 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_psychologiczny['trzecia_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_psychologiczny_2_3 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_psychologiczny['trzecia_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_psychologiczny_2_3 .= $kapital_psychologiczny['trzecia_czesc']['wynik_zwrotny'];

        $wynik_krok2_3 = $this->badanie_wynik_2_3( $badanie_ID );
        if ( isset( $wynik_krok2_3['refl_komunikacyjna'] ) ) {
          $result_kapital_psychologiczny_2_3 = str_replace( '{wynik_krok2_3_refl_komunikacyjna}', number_format( $wynik_krok2_3['refl_komunikacyjna'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_3 );
        }
        if ( isset( $wynik_krok2_3['refl_autonomiczna'] ) ) {
          $result_kapital_psychologiczny_2_3 = str_replace( '{wynik_krok2_3_refl_autonomiczna}', number_format( $wynik_krok2_3['refl_autonomiczna'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_3 );
        }
        if ( isset( $wynik_krok2_3['metarefleksyjnosc'] ) ) {
          $result_kapital_psychologiczny_2_3 = str_replace( '{wynik_krok2_3_metarefleksyjnosc}', number_format( $wynik_krok2_3['metarefleksyjnosc'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_3 );
        }
        if ( isset( $wynik_krok2_3['refl_peknieta'] ) ) {
          $result_kapital_psychologiczny_2_3 = str_replace( '{wynik_krok2_3_refl_peknieta}', number_format( $wynik_krok2_3['refl_peknieta'], 2, ',', ' ' ), $result_kapital_psychologiczny_2_3 );
        }

        $result_kapital_psychologiczny_2_3 .= '</div>';

      }

      // ---------------------------------------------------------

      $html2 = '';
      if ( isset( $result_kapital_psychologiczny_2_1 ) && $result_kapital_psychologiczny_2_1 ) {
        $html2 .= $result_kapital_psychologiczny_2_1;
      }
      if ( isset( $result_kapital_psychologiczny_2_2 ) && $result_kapital_psychologiczny_2_2 ) {
        $html2 .= $result_kapital_psychologiczny_2_2;
      }
      if ( isset( $result_kapital_psychologiczny_2_3 ) && $result_kapital_psychologiczny_2_3 ) {
        $html2 .= $result_kapital_psychologiczny_2_3;
      }

      $pdf->writeHTML( $html2, true, false, true, false, '' );


      // ---------------------------------------------------------


      $name_3 = '<h1 style="color: #059f8e; text-align: center; font-size: 21px;">3. ' . __( 'Kapitał społeczny', 'migracja' ) . '</h1>';
      $pdf->writeHTML( $name_3, true, false, true, false, '' );
      $pdf->Ln( 2 );

      $kapital_spoleczny = $this->page_settings['kapital_spoleczny'];
      if ( isset( $kapital_spoleczny['pierwsza_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_spoleczny_3_1 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">3.1 ' . __( 'Relacje społeczne', 'migracja' ) . '</h2>';
        $result_kapital_spoleczny_3_1 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_spoleczny['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_spoleczny_3_1 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_spoleczny['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_spoleczny_3_1 .= $kapital_spoleczny['pierwsza_czesc']['wynik_zwrotny'];

        $result_kapital_spoleczny_3_1 .= '</div>';

      }

      if ( isset( $kapital_spoleczny['druga_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_spoleczny_3_2 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">3.2 ' . __( 'Spostrzegane wsparcie społeczne', 'migracja' ) . '</h2>';
        $result_kapital_spoleczny_3_2 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_spoleczny['druga_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_spoleczny_3_2 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_spoleczny['druga_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_spoleczny_3_2 .= $kapital_spoleczny['druga_czesc']['wynik_zwrotny'];

        $wynik_krok3_2 = $this->badanie_wynik_3_2( $badanie_ID );
        if ( isset( $wynik_krok3_2['wsp_rodz'] ) ) {
          $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_rodz}', number_format( $wynik_krok3_2['wsp_rodz'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
        }
        if ( isset( $wynik_krok3_2['wsp_przyj'] ) ) {
          $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_przyj}', number_format( $wynik_krok3_2['wsp_przyj'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
        }
        if ( isset( $wynik_krok3_2['wsp_oz'] ) ) {
          $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_oz}', number_format( $wynik_krok3_2['wsp_oz'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
        }
        if ( isset( $wynik_krok3_2['wsp_all'] ) ) {
          $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_all}', number_format( $wynik_krok3_2['wsp_all'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
        }

        $result_kapital_spoleczny_3_2 .= '</div>';

      }

      if ( isset( $kapital_spoleczny['trzecia_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_spoleczny_3_3 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">3.3 ' . __( 'Zaangażowanie społeczno-obywatelskie', 'migracja' ) . '</h2>';
        $result_kapital_spoleczny_3_3 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_spoleczny['trzecia_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_spoleczny_3_3 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_spoleczny['trzecia_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_spoleczny_3_3 .= $kapital_spoleczny['trzecia_czesc']['wynik_zwrotny'];

        $result_kapital_spoleczny_3_3 .= '</div>';

      }

      // ---------------------------------------------------------

      $html3 = '';
      if ( isset( $result_kapital_spoleczny_3_1 ) && $result_kapital_spoleczny_3_1 ) {
        $html3 .= $result_kapital_spoleczny_3_1;
      }
      if ( isset( $result_kapital_spoleczny_3_2 ) && $result_kapital_spoleczny_3_2 ) {
        $html3 .= $result_kapital_spoleczny_3_2;
      }
      if ( isset( $result_kapital_spoleczny_3_3 ) && $result_kapital_spoleczny_3_3 ) {
        $html3 .= $result_kapital_spoleczny_3_3;
      }

      $pdf->writeHTML( $html3, true, false, true, false, '' );


      // ---------------------------------------------------------


      $name_4 = '<h1 style="color: #059f8e; text-align: center; font-size: 21px;">4. ' . __( 'Kapitał ekonomiczny', 'migracja' ) . '</h1>';
      $pdf->writeHTML( $name_4, true, false, true, false, '' );
      $pdf->Ln( 2 );

      $kapital_ekonomiczny = $this->page_settings['kapital_ekonomiczny'];
      if ( isset( $kapital_ekonomiczny['pierwsza_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_ekonomiczny_4_1 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">4.1 ' . __( 'Sposób gospodarowania pieniędzmi', 'migracja' ) . '</h2>';
        $result_kapital_ekonomiczny_4_1 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_ekonomiczny['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_ekonomiczny_4_1 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_ekonomiczny['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_ekonomiczny_4_1 .= $kapital_ekonomiczny['pierwsza_czesc']['wynik_zwrotny'];

        $result_kapital_ekonomiczny_4_1 .= '</div>';

      }

      if ( isset( $kapital_ekonomiczny['druga_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_ekonomiczny_4_2 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">4.2 ' . __( 'Stabilność zawodowa', 'migracja' ) . '</h2>';
        $result_kapital_ekonomiczny_4_2 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_ekonomiczny['druga_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_ekonomiczny_4_2 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_ekonomiczny['druga_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_ekonomiczny_4_2 .= $kapital_ekonomiczny['druga_czesc']['wynik_zwrotny'];

        $result_kapital_ekonomiczny_4_2 .= '</div>';

      }

      // ---------------------------------------------------------

      $html4 = '';
      if ( isset( $result_kapital_ekonomiczny_4_1 ) && $result_kapital_ekonomiczny_4_1 ) {
        $html4 .= $result_kapital_ekonomiczny_4_1;
      }
      if ( isset( $result_kapital_ekonomiczny_4_2 ) && $result_kapital_ekonomiczny_4_2 ) {
        $html4 .= $result_kapital_ekonomiczny_4_2;
      }

      $pdf->writeHTML( $html4, true, false, true, false, '' );

      // $pdf->AddPage();

      // ---------------------------------------------------------


      $pdf->Ln( 2 );

      $footer_text = __( 'Dokument został wygenerowany z portalu badawczego www.mojamigracja.org. Portal został stworzony przez Zespół Centrum Badań nad Zmianą Społeczną i Mobilnością Akademii Leona Koźmińskiego w ramach projektu BigMig finansowanego ze środków Narodowego Centrum Nauki (OPUS, nr projektu 2020/37/B/HS6/02342).', 'migracja' );
      // $foot1 = '<table cellpading="0" cellspacing="0" width="100%" style="width: 100%; text-align:center;"><tr><td><img src="http://mojamigracja.org/wp-content/uploads/2022/10/alk-logo.jpeg" width="150" height="112"></td></tr></table>';
      // $pdf->writeHTML($foot1, true, false, true, false, '');
      // $pdf->Ln(1);
      $foot2 = '<table cellpading="0" cellspacing="0" width="100%" style="width: 100%; text-align:center;"><tr><td style="font-size: 11px; line-height: 16px; color:#1d1d1f;">' . $footer_text . '</td></tr></table>';
      $pdf->writeHTML( $foot2, true, false, true, false, '' );

      // ---------------------------------------------------------

      //Close and output PDF document
      $pdf->Output( wp_upload_dir()['path'] . '/wynik-badania.pdf', 'F' );

      $result = array();

      $result['link'] = wp_upload_dir()['url'] . '/wynik-badania.pdf';
      // $result['name'] = __('badanie','migracja') . '-' . $badanie_ID . '.pdf';
      $result['name'] = 'MyMigration-MyResults.pdf';
    }

    wp_send_json( $result );
  }

  public function badanie_wynik_1_1( $badanie_ID ) {
    $wynik_ID = $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $odp = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_krok1_1` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok1_1,
        )
      )
    );

    $poznawcze = ( (float) $odp->KROK1_1_1 + (float) $odp->KROK1_1_2 + (float) $odp->KROK1_1_3 + (float) $odp->KROK1_1_5 + (float) $odp->KROK1_1_6 + (float) $odp->KROK1_1_7 + (float) $odp->KROK1_1_8 + (float) $odp->KROK1_1_9 ) / 8;
    $manualne  = ( (float) $odp->KROK1_1_4 + (float) $odp->KROK1_1_10 + (float) $odp->KROK1_1_11 + (float) $odp->KROK1_1_21 ) / 4;
    $miekkie   = ( (float) $odp->KROK1_1_12 + (float) $odp->KROK1_1_13 + (float) $odp->KROK1_1_14 + (float) $odp->KROK1_1_15 + (float) $odp->KROK1_1_16 + (float) $odp->KROK1_1_17 + (float) $odp->KROK1_1_18 + (float) $odp->KROK1_1_19 + (float) $odp->KROK1_1_20 ) / 9;
    $zyciowe   = ( (float) $odp->KROK1_1_22 + (float) $odp->KROK1_1_23 + (float) $odp->KROK1_1_24 + (float) $odp->KROK1_1_25 + (float) $odp->KROK1_1_26 ) / 5;

    $wyniki              = array();
    $wyniki['poznawcze'] = $poznawcze;
    $wyniki['manualne']  = $manualne;
    $wyniki['miekkie']   = $miekkie;
    $wyniki['zyciowe']   = $zyciowe;

    return $wyniki;
  }

  public function badanie_wynik_1_3( $badanie_ID ) {
    $wyniki = array();

    $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $wynik_ID = $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $odp_1_1 = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_krok1_1` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok1_1,
        )
      )
    );


    if ( $badanie->badanie_wyniki_krok1_3 != 0 ) {
      $warunek = $badanie->badanie_wyniki_krok1_3;
      $odp_1_3 = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_wyniki_krok1_3` WHERE `wynik_ID` = %d",
          array(
            $badanie->badanie_wyniki_krok1_3,
          )
        )
      );


      $KP_1  = (int) $odp_1_1->KROK1_1_1;
      $KP_2  = (int) $odp_1_1->KROK1_1_2;
      $KP_3  = (int) $odp_1_1->KROK1_1_3;
      $KP_4  = (int) $odp_1_1->KROK1_1_4;
      $KP_5  = (int) $odp_1_1->KROK1_1_5;
      $KP_6  = (int) $odp_1_1->KROK1_1_6;
      $KP_7  = (int) $odp_1_1->KROK1_1_7;
      $KP_8  = (int) $odp_1_1->KROK1_1_8;
      $KP_9  = (int) $odp_1_1->KROK1_1_9;
      $KP_10 = (int) $odp_1_1->KROK1_1_10;
      $KP_11 = (int) $odp_1_1->KROK1_1_11;
      $KP_12 = (int) $odp_1_1->KROK1_1_12;
      $KP_13 = (int) $odp_1_1->KROK1_1_13;
      $KP_14 = (int) $odp_1_1->KROK1_1_14;
      $KP_15 = (int) $odp_1_1->KROK1_1_15;
      $KP_16 = (int) $odp_1_1->KROK1_1_16;
      $KP_17 = (int) $odp_1_1->KROK1_1_17;
      $KP_18 = (int) $odp_1_1->KROK1_1_18;
      $KP_19 = (int) $odp_1_1->KROK1_1_19;
      $KP_20 = (int) $odp_1_1->KROK1_1_20;
      $KP_21 = (int) $odp_1_1->KROK1_1_21;
      $KP_22 = (int) $odp_1_1->KROK1_1_22;
      $KP_23 = (int) $odp_1_1->KROK1_1_23;
      $KP_24 = (int) $odp_1_1->KROK1_1_24;
      $KP_25 = (int) $odp_1_1->KROK1_1_25;
      $KP_26 = (int) $odp_1_1->KROK1_1_26;

      $KW_1  = (int) $odp_1_3->KROK1_3_1;
      $KW_2  = (int) $odp_1_3->KROK1_3_2;
      $KW_3  = (int) $odp_1_3->KROK1_3_34;
      $KW_4  = (int) $odp_1_3->KROK1_3_5;
      $KW_5  = (int) $odp_1_3->KROK1_3_6;
      $KW_6  = (int) $odp_1_3->KROK1_3_7;
      $KW_7  = (int) $odp_1_3->KROK1_3_8;
      $KW_8  = (int) $odp_1_3->KROK1_3_9;
      $KW_9  = (int) $odp_1_3->KROK1_3_10;
      $KW_10 = (int) $odp_1_3->KROK1_3_11;
      $KW_11 = (int) $odp_1_3->KROK1_3_12;
      $KW_12 = (int) $odp_1_3->KROK1_3_13;
      $KW_13 = (int) $odp_1_3->KROK1_3_14;
      $KW_14 = (int) $odp_1_3->KROK1_3_15;
      $KW_15 = (int) $odp_1_3->KROK1_3_16;
      $KW_16 = (int) $odp_1_3->KROK1_3_17;
      $KW_17 = (int) $odp_1_3->KROK1_3_18;
      $KW_18 = (int) $odp_1_3->KROK1_3_19;
      $KW_19 = (int) $odp_1_3->KROK1_3_20;
      $KW_20 = (int) $odp_1_3->KROK1_3_21;
      $KW_21 = (int) $odp_1_3->KROK1_3_22;
      $KW_22 = (int) $odp_1_3->KROK1_3_23;
      $KW_23 = (int) $odp_1_3->KROK1_3_24;
      $KW_24 = (int) $odp_1_3->KROK1_3_25;
      $KW_25 = (int) $odp_1_3->KROK1_3_26;
      $KW_26 = (int) $odp_1_3->KROK1_3_27;


      for ( $i = 1; $i < 27; $i++ ) {
        if ( ( isset( ${'KP_' . $i} ) ) && ( isset( ${'KW_' . $i} ) ) ) {
          ${'DK_' . $i} = ${'KP_' . $i} - ${'KW_' . $i};
        }
      }

      for ( $i = 1; $i < 27; $i++ ) {
        if ( isset( ${'KW_' . $i} ) && ${'KW_' . $i} < 2 ) {
          ${'KW_' . $i . 'R'} = 1;
        } elseif ( isset( ${'KW_' . $i} ) && ${'KW_' . $i} > 2 ) {
          ${'KW_' . $i . 'R'} = 2;
        }
      }


      for ( $i = 1; $i < 27; $i++ ) {
        if ( isset( ${'KW_' . $i . 'R'} ) && ${'KW_' . $i . 'R'} == 1 ) {
          ${'Nadmiar' . $i} = ${'DK_' . $i};
        } elseif ( isset( ${'KW_' . $i . 'R'} ) && ${'KW_' . $i . 'R'} == 2 ) {
          ${'Niedobor' . $i} = ${'DK_' . $i};
        }

      }

      $NADMIAR      = 0;
      $NADMIR_count = 0;

      for ( $i = 1; $i < 27; $i++ ) {
        if ( isset( ${'Nadmiar' . $i} ) ) {
          $NADMIAR += ${'Nadmiar' . $i};
          $NADMIR_count++;
        }
      }

      if ( $NADMIAR != 0 && $NADMIR_count != 0 ) {
        $NADMIAR           = $NADMIAR / $NADMIR_count;
        $wyniki['nadmiar'] = $NADMIAR;
      }

      // obliczanie sredniej niedoboru
      $NIEDOBOR       = 0;
      $NIEDOBOR_count = 0;

      for ( $i = 1; $i < 27; $i++ ) {
        if ( isset( ${'Niedobor' . $i} ) ) {
          $NIEDOBOR += ${'Niedobor' . $i};
          $NIEDOBOR_count++;
        }
      }

      if ( $NIEDOBOR != 0 && $NIEDOBOR_count != 0 ) {
        $NIEDOBOR           = $NIEDOBOR / $NIEDOBOR_count;
        $NIEDOBOR           = $NIEDOBOR * ( -1 );
        $wyniki['niedobor'] = $NIEDOBOR;
      } else {
        $wyniki['niedobor'] = 0;
      }


      if ( $odp_1_3->KROK1_3_31 !== null ) {
        $wyniki['dopasowanie'] = $odp_1_3->KROK1_3_31;
      } else {
        $wyniki['dopasowanie'] = 0;
      }

    } else {
      $wyniki['nadmiar']     = 0;
      $wyniki['niedobor']    = 0;
      $wyniki['dopasowanie'] = 0;
    }

    return $wyniki;
  }

  public function badanie_wynik_2_1( $badanie_ID ) {
    $wynik_ID = $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $odp = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_krok2_1` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok2_1,
        )
      )
    );

    $nadzieja              = $odp->KROK2_1_1 + $odp->KROK2_1_2 + $odp->KROK2_1_3;
    $poczucie_skutecznosci = $odp->KROK2_1_4 + $odp->KROK2_1_5 + $odp->KROK2_1_6;
    $odpornosc_psych       = $odp->KROK2_1_7 + $odp->KROK2_1_8 + $odp->KROK2_1_9;
    $optymizm              = $odp->KROK2_1_10 + $odp->KROK2_1_11 + $odp->KROK2_1_12;

    $wyniki                          = array();
    $wyniki['nadzieja']              = $nadzieja;
    $wyniki['poczucie_skutecznosci'] = $poczucie_skutecznosci;
    $wyniki['odpornosc_psych']       = $odpornosc_psych;
    $wyniki['optymizm']              = $optymizm;
    $wyniki['ogolny']                = $nadzieja + $poczucie_skutecznosci + $odpornosc_psych + $optymizm;

    return $wyniki;
  }

  public function badanie_wynik_2_2( $badanie_ID ) {
    $wynik_ID = $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $odp = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_krok2_2` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok2_2,
        )
      )
    );

    $IPIP1 = $odp->KROK2_2_3;
    if ( $odp->KROK2_2_6 != null ) {
      if ( $odp->KROK2_2_6 == 1 ) {
        $IPIP6R = 5;
      } elseif ( $odp->KROK2_2_6 == 2 ) {
        $IPIP6R = 4;
      } elseif ( $odp->KROK2_2_6 == 3 ) {
        $IPIP6R = 3;
      } elseif ( $odp->KROK2_2_6 == 4 ) {
        $IPIP6R = 2;
      } elseif ( $odp->KROK2_2_6 == 5 ) {
        $IPIP6R = 1;
      }
    }
    $IPIP11 = $odp->KROK2_2_11;
    if ( $odp->KROK2_2_16 != null ) {
      if ( $odp->KROK2_2_16 == 1 ) {
        $IPIP16R = 5;
      } elseif ( $odp->KROK2_2_16 == 2 ) {
        $IPIP16R = 4;
      } elseif ( $odp->KROK2_2_16 == 3 ) {
        $IPIP16R = 3;
      } elseif ( $odp->KROK2_2_16 == 4 ) {
        $IPIP16R = 2;
      } elseif ( $odp->KROK2_2_16 == 5 ) {
        $IPIP16R = 1;
      }
    }

    $ekstrawersja = $IPIP1 + $IPIP6R + $IPIP11 + $IPIP16R;

    $IPIP2 = $odp->KROK2_2_2;
    if ( $odp->KROK2_2_7 != null ) {
      if ( $odp->KROK2_2_7 == 1 ) {
        $IPIP7R = 5;
      } elseif ( $odp->KROK2_2_7 == 2 ) {
        $IPIP7R = 4;
      } elseif ( $odp->KROK2_2_7 == 3 ) {
        $IPIP7R = 3;
      } elseif ( $odp->KROK2_2_7 == 4 ) {
        $IPIP7R = 2;
      } elseif ( $odp->KROK2_2_7 == 5 ) {
        $IPIP7R = 1;
      }
    }
    $IPIP12 = $odp->KROK2_2_12;
    if ( $odp->KROK2_2_17 != null ) {
      if ( $odp->KROK2_2_17 == 1 ) {
        $IPIP17R = 5;
      } elseif ( $odp->KROK2_2_17 == 2 ) {
        $IPIP17R = 4;
      } elseif ( $odp->KROK2_2_17 == 3 ) {
        $IPIP17R = 3;
      } elseif ( $odp->KROK2_2_17 == 4 ) {
        $IPIP17R = 2;
      } elseif ( $odp->KROK2_2_17 == 5 ) {
        $IPIP17R = 1;
      }
    }

    $ugodowosc = $IPIP2 + $IPIP7R + $IPIP12 + $IPIP17R;

    $IPIP3 = $odp->KROK2_2_22;
    if ( $odp->KROK2_2_8 != null ) {
      if ( $odp->KROK2_2_8 == 1 ) {
        $IPIP8R = 5;
      } elseif ( $odp->KROK2_2_8 == 2 ) {
        $IPIP8R = 4;
      } elseif ( $odp->KROK2_2_8 == 3 ) {
        $IPIP8R = 3;
      } elseif ( $odp->KROK2_2_8 == 4 ) {
        $IPIP8R = 2;
      } elseif ( $odp->KROK2_2_8 == 5 ) {
        $IPIP8R = 1;
      }
    }
    $IPIP13 = $odp->KROK2_2_13;
    if ( $odp->KROK2_2_18 != null ) {
      if ( $odp->KROK2_2_18 == 1 ) {
        $IPIP18R = 5;
      } elseif ( $odp->KROK2_2_18 == 2 ) {
        $IPIP18R = 4;
      } elseif ( $odp->KROK2_2_18 == 3 ) {
        $IPIP18R = 3;
      } elseif ( $odp->KROK2_2_18 == 4 ) {
        $IPIP18R = 2;
      } elseif ( $odp->KROK2_2_18 == 5 ) {
        $IPIP18R = 1;
      }
    }

    $sumiennosc = $IPIP3 + $IPIP8R + $IPIP13 + $IPIP18R;

    $IPIP4 = $odp->KROK2_2_4;
    if ( $odp->KROK2_2_9 != null ) {
      if ( $odp->KROK2_2_9 == 1 ) {
        $IPIP9R = 5;
      } elseif ( $odp->KROK2_2_9 == 2 ) {
        $IPIP9R = 4;
      } elseif ( $odp->KROK2_2_9 == 3 ) {
        $IPIP9R = 3;
      } elseif ( $odp->KROK2_2_9 == 4 ) {
        $IPIP9R = 2;
      } elseif ( $odp->KROK2_2_9 == 5 ) {
        $IPIP9R = 1;
      }
    }
    $IPIP14 = $odp->KROK2_2_14;
    if ( $odp->KROK2_2_19 != null ) {
      if ( $odp->KROK2_2_19 == 1 ) {
        $IPIP19R = 5;
      } elseif ( $odp->KROK2_2_19 == 2 ) {
        $IPIP19R = 4;
      } elseif ( $odp->KROK2_2_19 == 3 ) {
        $IPIP19R = 3;
      } elseif ( $odp->KROK2_2_19 == 4 ) {
        $IPIP19R = 2;
      } elseif ( $odp->KROK2_2_19 == 5 ) {
        $IPIP19R = 1;
      }
    }

    $neurotyzm = $IPIP4 + $IPIP9R + $IPIP14 + $IPIP19R;

    $IPIP5 = $odp->KROK2_2_5;
    if ( $odp->KROK2_2_7 != null ) {
      if ( $odp->KROK2_2_7 == 1 ) {
        $IPIP10R = 5;
      } elseif ( $odp->KROK2_2_7 == 2 ) {
        $IPIP10R = 4;
      } elseif ( $odp->KROK2_2_7 == 3 ) {
        $IPIP10R = 3;
      } elseif ( $odp->KROK2_2_7 == 4 ) {
        $IPIP10R = 2;
      } elseif ( $odp->KROK2_2_7 == 5 ) {
        $IPIP10R = 1;
      }
    }
    if ( $odp->KROK2_2_15 != null ) {
      if ( $odp->KROK2_2_15 == 1 ) {
        $IPIP15R = 5;
      } elseif ( $odp->KROK2_2_15 == 2 ) {
        $IPIP15R = 4;
      } elseif ( $odp->KROK2_2_15 == 3 ) {
        $IPIP15R = 3;
      } elseif ( $odp->KROK2_2_15 == 4 ) {
        $IPIP15R = 2;
      } elseif ( $odp->KROK2_2_15 == 5 ) {
        $IPIP15R = 1;
      }
    }
    if ( $odp->KROK2_2_20 != null ) {
      if ( $odp->KROK2_2_20 == 1 ) {
        $IPIP20R = 5;
      } elseif ( $odp->KROK2_2_20 == 2 ) {
        $IPIP20R = 4;
      } elseif ( $odp->KROK2_2_20 == 3 ) {
        $IPIP20R = 3;
      } elseif ( $odp->KROK2_2_20 == 4 ) {
        $IPIP20R = 2;
      } elseif ( $odp->KROK2_2_20 == 5 ) {
        $IPIP20R = 1;
      }
    }

    $wyobraznia = $IPIP5 + $IPIP10R + $IPIP15R + $IPIP20R;

    $wyniki                 = array();
    $wyniki['ekstrawersja'] = $ekstrawersja;
    $wyniki['ugodowosc']    = $ugodowosc;
    $wyniki['sumiennosc']   = $sumiennosc;
    $wyniki['neurotyzm']    = $neurotyzm;
    $wyniki['wyobraznia']   = $wyobraznia;

    return $wyniki;
  }

  public function badanie_wynik_2_3( $badanie_ID ) {
    $wynik_ID = $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $odp = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_krok2_3` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok2_3,
        )
      )
    );


    $refl_komunikacyjna = ( $odp->KROK2_3_1 + $odp->KROK2_3_5 + $odp->KROK2_3_9 ) / 3;

    $ICONI2 = $odp->KROK2_3_2;
    if ( $odp->KROK2_3_6 != null ) {
      if ( $odp->KROK2_3_6 == 1 ) {
        $ICONI6R = 7;
      } elseif ( $odp->KROK2_3_6 == 2 ) {
        $ICONI6R = 6;
      } elseif ( $odp->KROK2_3_6 == 3 ) {
        $ICONI6R = 5;
      } elseif ( $odp->KROK2_3_6 == 4 ) {
        $ICONI6R = 4;
      } elseif ( $odp->KROK2_3_6 == 5 ) {
        $ICONI6R = 3;
      } elseif ( $odp->KROK2_3_6 == 6 ) {
        $ICONI6R = 2;
      } elseif ( $odp->KROK2_3_6 == 7 ) {
        $ICONI6R = 1;
      }
    }
    if ( $odp->KROK2_3_11 != null ) {
      if ( $odp->KROK2_3_11 == 1 ) {
        $ICONI11R = 7;
      } elseif ( $odp->KROK2_3_11 == 2 ) {
        $ICONI11R = 6;
      } elseif ( $odp->KROK2_3_11 == 3 ) {
        $ICONI11R = 5;
      } elseif ( $odp->KROK2_3_11 == 4 ) {
        $ICONI11R = 4;
      } elseif ( $odp->KROK2_3_11 == 5 ) {
        $ICONI11R = 3;
      } elseif ( $odp->KROK2_3_11 == 6 ) {
        $ICONI11R = 2;
      } elseif ( $odp->KROK2_3_11 == 7 ) {
        $ICONI11R = 1;
      }
    }

    $refl_autonomiczna = ( $ICONI2 + $ICONI6R + $ICONI11R ) / 3;

    $metarefleksyjnosc = ( $odp->KROK2_3_3 + $odp->KROK2_3_7 + $odp->KROK2_3_12 ) / 3;
    $refl_peknieta     = ( $odp->KROK2_3_4 + $odp->KROK2_3_8 + $odp->KROK2_3_10 + $odp->KROK2_3_13 ) / 4;

    $wyniki                       = array();
    $wyniki['refl_komunikacyjna'] = $refl_komunikacyjna;
    $wyniki['refl_autonomiczna']  = $refl_autonomiczna;
    $wyniki['metarefleksyjnosc']  = $metarefleksyjnosc;
    $wyniki['refl_peknieta']      = $refl_peknieta;

    return $wyniki;
  }

  public function badanie_wynik_3_2( $badanie_ID ) {
    $wynik_ID = $badanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}` WHERE `badanie_ID` = %d",
        array(
          $badanie_ID,
        )
      )
    );

    $odp = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_wyniki_krok3_2` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok3_2,
        )
      )
    );

    $wsp_rodz  = ( $odp->KROK3_2_3 + $odp->KROK3_2_4 + $odp->KROK3_2_8 + $odp->KROK3_2_11 ) / 4;
    $wsp_przyj = ( $odp->KROK3_2_6 + $odp->KROK3_2_7 + $odp->KROK3_2_9 + $odp->KROK3_2_12 ) / 4;
    $wsp_oz    = ( $odp->KROK3_2_1 + $odp->KROK3_2_2 + $odp->KROK3_2_5 + $odp->KROK3_2_10 ) / 4;
    $wsp_all   = ( $wsp_rodz + $wsp_przyj + $wsp_oz );

    $wyniki              = array();
    $wyniki['wsp_rodz']  = $wsp_rodz;
    $wyniki['wsp_przyj'] = $wsp_przyj;
    $wyniki['wsp_oz']    = $wsp_oz;
    $wyniki['wsp_all']   = $wsp_all;

    return $wyniki;
  }


}
