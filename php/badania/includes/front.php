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

  public function badanie_dodaj_krok( $nazwaKroku = null ) {
    $badanie_ID = $_POST['badanie_ID'];
    $krok       = false === empty( $nazwaKroku ) ? $nazwaKroku : $_POST['krok'];
    $krok_upper = strtoupper( $krok );
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

    if ( 'metryczka' !== $krok ) {
      wp_die();
    }
  }

  public function badanie_podsumowanie_form() {
    $badanie_ID = $_POST['badanie_ID'];

    $badania      = $this->page_settings;
    $podsumowanie = $badania['podsumowanie_badania'];

    $this->badanie_dodaj_krok( 'metryczka' );

    if ( isset( $_POST['badanie_email'] ) ) :
      $input['badanie_email'] = $_POST['badanie_email'];
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
    $result  = html_entity_decode( $section[ $czesc ]['wynik_zwrotny'] );
    $title   = $section[ $czesc ]['naglowek_wyniku'];

    $parsed_sections = [];

    if ( $result ) {
      $parsed_sections = $this->parse_text_parts( $result );
    }

    if ( count( $parsed_sections ) > 0 ) {
      $result = '';
    }


    if ( $_POST['step'] === 'step-1-1' ) {
      $wynik_krok1_1 = $this->badanie_wynik_1_1( $_POST['badanie_ID'] );

      if (
        true === str_contains( $wynik_krok1_1['language'], 'other' )
        && true === str_contains( $wynik_krok1_1['language'], 'english' )
      ) {
        $result = $parsed_sections['language_2'];
      } else {
        $result = $parsed_sections['language_1'];
      }

      if ( 2 < (int) $wynik_krok1_1['programs_abroad'] ) {
        $result .= $parsed_sections['programs_abroad_2'];
      } else {
        $result .= $parsed_sections['programs_abroad_1'];
      }

      if ( 1 < (int) $wynik_krok1_1['qualification_recognition'] ) {
        $result .= $parsed_sections['qualification_recognition'];
      }

      if ( false === str_contains( $wynik_krok1_1['certifications'], '5.5' ) ) {
        $result .= $parsed_sections['certifications_1'];
      } else {
        $result .= $parsed_sections['certifications_2'];
      }

      if ( 3 > (int) $wynik_krok1_1['lifelong_learning'] ) {
        $result .= $parsed_sections['lifelong_learning_1'];
      } else {
        $result .= $parsed_sections['lifelong_learning_2'];
      }

      if ( $wynik_krok1_1['competences'] ) {
        $result .= str_replace( '{{competences_result}}', $wynik_krok1_1['competences'], $parsed_sections['competences'] );
      }

      if ( 'yes' === $wynik_krok1_1['recommendations'] ) {
        $result .= $parsed_sections['recommendations_1'];
      } else {
        $result .= $parsed_sections['recommendations_2'];
      }

      if ( false === is_null( $wynik_krok1_1['skills_acquired'] ) ) {
        if ( 10 === $wynik_krok1_1['skills_acquired'] ) {
          $result .= $parsed_sections['skills_acquired_3'];
        } else if ( 15 <= $wynik_krok1_1['skills_acquired'] ) {
          $result .= $parsed_sections['skills_acquired_1'];
        } else {
          $result .= $parsed_sections['skills_acquired_2'];
        }
      }

      if ( false === is_null( $wynik_krok1_1['mobility_experience'] ) ) {
        if ( 2 < (int) $wynik_krok1_1['mobility_experience'] ) {
          $result .= $parsed_sections['mobility_experience_2'];
        } else {
          $result .= $parsed_sections['mobility_experience_1'];
        }
      }
    }

    if ( $_POST['step'] === 'step-2-1' ) {
      $wynik_krok2_1 = $this->badanie_wynik_2_1( $_POST['badanie_ID'] );

      if ( false === is_null( $wynik_krok2_1['defining_sentences'] ) ) {
        $result = str_replace( '{{defining_sentences_result}}', $wynik_krok2_1['defining_sentences'], $parsed_sections['defining_sentences'] );
      }

      if ( false === is_null( $wynik_krok2_1['hope']['score'] ) ) {
        if ( $wynik_krok2_1['hope']['score'] >= $wynik_krok2_1['hope']['avg'] ) {
          $result .= $parsed_sections['hope_1'];
        } else {
          $result .= $parsed_sections['hope_2'];
        }

        if ( $wynik_krok2_1['self_efficacy']['score'] >= $wynik_krok2_1['self_efficacy']['avg'] ) {
          $result .= $parsed_sections['self_efficacy_1'];
        } else {
          $result .= $parsed_sections['self_efficacy_2'];
        }

        if ( $wynik_krok2_1['resilience']['score'] >= $wynik_krok2_1['resilience']['avg'] ) {
          $result .= $parsed_sections['resilience_1'];
        } else {
          $result .= $parsed_sections['resilience_2'];
        }

        if ( $wynik_krok2_1['optimism']['score'] >= $wynik_krok2_1['optimism']['avg'] ) {
          $result .= $parsed_sections['optimism_1'];
        } else {
          $result .= $parsed_sections['optimism_2'];
        }
      }

      if ( false === is_null( $wynik_krok2_1['personality'] ) ) {
        $result .= str_replace( '{{personality_result}}', $wynik_krok2_1['personality'], $parsed_sections['personality'] );
      }

      if ( false === is_null( $wynik_krok2_1['reflexivity'] ) ) {
        $result .= str_replace( '{{reflexivity_result}}', $wynik_krok2_1['reflexivity'], $parsed_sections['reflexivity'] );
      }

      if ( false === is_null( $wynik_krok2_1['mobility_experience'] ) ) {
        $result .= str_replace( '{{mobility_experience_result}}', $wynik_krok2_1['mobility_experience'], $parsed_sections['mobility_experience'] );
      }
    }

    if ( $_POST['step'] === 'step-3-1' ) {
      $wynik_krok3_1 = $this->badanie_wynik_3_1( $_POST['badanie_ID'] );

      $result = str_replace( '{{importance_of_all_contacts_result}}', $wynik_krok3_1['importance_of_all_contacts'], $result );
    }

    if ( $_POST['step'] === 'step-3-2' ) {
      $wynik_krok3_2 = $this->badanie_wynik_3_2( $_POST['badanie_ID'] );

      if ( false === is_null( $wynik_krok3_2['relations']['wsp_rodz'] ) ) {
        if ( isset( $wynik_krok3_2['relations']['wsp_rodz'] ) ) {
          $result = str_replace( '{wynik_krok3_2_wsp_rodz}', number_format( $wynik_krok3_2['relations']['wsp_rodz'], 2, ',', ' ' ), $parsed_sections['relations'] );
        }
        if ( isset( $wynik_krok3_2['relations']['wsp_przyj'] ) ) {
          $result = str_replace( '{wynik_krok3_2_wsp_przyj}', number_format( $wynik_krok3_2['relations']['wsp_przyj'], 2, ',', ' ' ), $result );
        }
        if ( isset( $wynik_krok3_2['relations']['wsp_oz'] ) ) {
          $result = str_replace( '{wynik_krok3_2_wsp_oz}', number_format( $wynik_krok3_2['relations']['wsp_oz'], 2, ',', ' ' ), $result );
        }
        if ( isset( $wynik_krok3_2['relations']['wsp_all'] ) ) {
          $result = str_replace( '{wynik_krok3_2_wsp_all}', number_format( $wynik_krok3_2['relations']['wsp_all'], 2, ',', ' ' ), $result );
        }
      }

      if ( false === is_null( $wynik_krok3_2['mobility_affected_support'] ) ) {
        $result .= str_replace( '{{mobility_affected_support_result}}', $wynik_krok3_2['mobility_affected_support'], $parsed_sections['mobility_affected_support'] );
      }
    }

    if ( $_POST['step'] === 'step-3-3' ) {
      $wynik_krok3_3 = $this->badanie_wynik_3_3( $_POST['badanie_ID'] );

      $result = $parsed_sections['general'];

      $result .= str_replace( '{{social_involvement_result}}', $wynik_krok3_3['social_involvement'], $parsed_sections['social_involvement'] );

      if ( false === is_null( $wynik_krok3_3['perceive_aspects_after_return'] ) ) {
        $result .= str_replace( '{{perceive_aspects_after_return_result}}', $wynik_krok3_3['perceive_aspects_after_return'], $parsed_sections['perceive_aspects_after_return'] );
      }
    }

    if ( $_POST['step'] === 'step-4-2' ) {
      $wynik_krok4_2 = $this->badanie_wynik_4_2( $_POST['badanie_ID'] );

      $result = str_replace( '{{average_financial_literacy_result}}', (
      false === is_null( $wynik_krok4_2['average_financial_literacy'] ) ?
        $wynik_krok4_2['average_financial_literacy'] : __( 'No statement', 'migracja' )
      ), $result );
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

      $wyniki_kapital_ludzki = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_wyniki_krok1_1` WHERE `wynik_ID` = %d",
          array(
            $badanie->badanie_wyniki_krok1_1,
          )
        )
      );


      $pytania_do_ukrycia = [
        "KROK1_1_32",
        "KROK1_1_34",
        "KROK1_1_37",
        "KROK1_1_48",
        "KROK1_1_40",
        "KROK1_1_43",
        "KROK1_1_45",
        "KROK1_1_46",
        "KROK1_1_47",
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

        "KROK2_1_22",
        "KROK2_1_15",
        "KROK2_1_16",
        "KROK2_1_17",
        "KROK2_1_18",
        "KROK2_1_23",
        "KROK2_1_19",
        "KROK2_1_20",
        "KROK2_1_21",
        "KROK2_1_24",
        "KROK2_1_26",
        "KROK2_1_27",
        "KROK2_1_28",
        "KROK2_1_29",
        "KROK2_1_30",
        "KROK2_1_31",
        "KROK2_1_53",
        "KROK2_1_62",
        "KROK2_1_63",
        "KROK2_1_64",
        "KROK2_1_67",
        "KROK2_1_75",

        "KROK3_1_15",

        "KROK3_2_15",
        "KROK3_2_16",
        "KROK3_2_17",
        "KROK3_2_18",
        "KROK3_2_19",
        "KROK3_2_20",
        "KROK3_2_21",
        "KROK3_2_22",

        "KROK3_3_88",
        "KROK3_3_89",
        "KROK3_3_27",
        "KROK3_3_22",
        "KROK3_3_23",
        "KROK3_3_24",
        "KROK3_3_25",
        "KROK3_3_28",
        "KROK3_3_29",
        "KROK3_3_30",
        "KROK3_3_31",
        "KROK3_3_32",
        "KROK3_3_33",
        "KROK3_3_34",
        "KROK3_3_38",
        "KROK3_3_91",
        "KROK3_3_92",

        "KROK4_2_1",
        "KROK4_2_3",

        "METRYCZKA_11",
      ];

      if (
        0 === (int) $wyniki_wstepne->WSTEPNE_2
        || ( false === is_null( $wyniki_kapital_ludzki ) && ( 3 === (int) $wyniki_kapital_ludzki->KROK1_1_32 || 4 === (int) $wyniki_kapital_ludzki->KROK1_1_32 ) )
      ) {
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


      if ( 'metryczka' !== $krok ) {
        $html .= '
        <div class="line-btn">
          <button class="btn btn-blue-line">' . __( 'Zapisz', 'migracja' ) . '</button>
        </div>
        ';
      }

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
    if ( ( $row->badanie_wyniki_krok2_1 !== null ) ) {
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
    if ( $row->badanie_wyniki_krok3_1 !== null && $row->badanie_wyniki_krok3_2 !== null && $row->badanie_wyniki_krok3_3 !== null ) {
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
    if ( $row->badanie_wyniki_krok4_1 !== null && $row->badanie_wyniki_krok4_2 !== null ) {
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

    $html .= '</div>';

    $data['opis'] = $html;


    if ( empty( $row->badanie_email ) ) {
      $form = '<form action="#" id="form-podsumowanie-badania">';

      $form .= $this->lista_pytan( 'metryczka', $badanie_ID );

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


      if ( !( $row->badanie_jezyk ) ) {
        $form .= '<input name="badanie_jezyk" type="hidden" value="' . ICL_LANGUAGE_CODE . '">';
      }

      $form .= '
      <div class="line-btn">
        <input name="badanie_ID" type="hidden" value="' . $badanie_ID . '"> 
        <button class="btn btn-blue-line">' . __( 'Zapisz', 'migracja' ) . '</button>
      </div>
      ';

      $form .= '</form>';

      $form .= '<div class="content">';
      $form .= '<div class="line-btn"><a href="#" id="generuj-pdf" data-badanie-id="' . $badanie_ID . '" title="' . __( 'Pobierz plik PDF z wynikami', 'migracja' ) . '" class="btn btn-green">' . __( 'Pobierz plik PDF z wynikami', 'migracja' ) . '</a></div>';
      $form .= '</div>';

      $data['form'] = $form;

      wp_send_json_success( $data );
    } else {
      wp_send_json_error( $data );
    }
  }

  private function clear_special_char( $string ) {
    return str_replace( array( '\"', "\'" ), array( '"', "'" ), $string );
  }

  private function parse_text_parts( $text ): array {
    $text = html_entity_decode( $text );

    $parsed_sections = [];

    // Regex to find [text_part id="..."]...[/text_part]
    // It captures the ID and the content between the tags.
    // The 's' modifier makes the dot (.) match newlines, so multi-line content is captured.
    $pattern = '#\[text_part id=(["“’”`\'])([\w_\d]*)([^"`\']{1}\s*\])(.*?)\[/text_part\]#su';

    preg_match_all( $pattern, $text, $matches, PREG_SET_ORDER );

    if ( $matches ) {
      foreach ( $matches as $match_item ) {
        $id                     = $match_item[2];
        $content                = $match_item[4];
        $parsed_sections[ $id ] = trim( $content );
      }
    }

    return $parsed_sections;
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
    $html         .= '
            <div class="answers">';
    $html         .= '<select name="' . $krok_ID . '" id="' . $krok_ID . '">';
    $html         .= '<option value="" selected="true" disabled="disabled">-- ' . __( 'Wybierz rok', 'migracja' ) . ' --</option>';
    $current_year = date( 'Y' );
    for ( $i = $current_year - 13; $i >= $current_year - 100; $i-- ) {
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
                <a href="#" class="btn btn-blue-line" title="' . __( 'Dodaj język', 'migracja' ) . '">' . __( 'Dodaj język', 'migracja' ) . '</a>
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
      $pdf->setAuthor( __( 'My Mobility', 'migracja' ) );
      $pdf->setTitle( __( 'My Mobility - The result of your assessment', 'migracja' ) );
      $pdf->setSubject( __( 'Result', 'migracja' ) );
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
      $pdf->setFont( 'dejavusans', '', 11 );

      // add a page
      $pdf->AddPage();

      // ---------------------------------------------------------

      $name_1 = '<h1 style="color: #059f8e; text-align: center; font-size: 21px;">1. ' . __( 'Kapitał ludzki', 'migracja' ) . '</h1>';
      $pdf->writeHTML( $name_1, true, false, true, false, '' );
      $pdf->Ln( 2 );

      $kapital_ludzki = $this->page_settings['kapital_ludzki'];
      if ( isset( $kapital_ludzki['pierwsza_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_ludzki = '<div>';

        if ( isset( $kapital_ludzki['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_ludzki .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_ludzki['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $wynik_krok1_1   = $this->badanie_wynik_1_1( $badanie_ID );
        $parsed_sections = $this->parse_text_parts( $kapital_ludzki['pierwsza_czesc']['wynik_zwrotny'] );

        if (
          true === str_contains( $wynik_krok1_1['language'], 'other' )
          && true === str_contains( $wynik_krok1_1['language'], 'english' )
        ) {
          $result_kapital_ludzki .= $parsed_sections['language_2'];
        } else {
          $result_kapital_ludzki .= $parsed_sections['language_1'];
        }

        if ( 2 < (int) $wynik_krok1_1['programs_abroad'] ) {
          $result_kapital_ludzki .= $parsed_sections['programs_abroad_2'];
        } else {
          $result_kapital_ludzki .= $parsed_sections['programs_abroad_1'];
        }

        if ( 1 < (int) $wynik_krok1_1['qualification_recognition'] ) {
          $result_kapital_ludzki .= $parsed_sections['qualification_recognition'];
        }

        if ( false === str_contains( $wynik_krok1_1['certifications'], '5.5' ) ) {
          $result_kapital_ludzki .= $parsed_sections['certifications_1'];
        } else {
          $result_kapital_ludzki .= $parsed_sections['certifications_2'];
        }

        if ( 3 > (int) $wynik_krok1_1['lifelong_learning'] ) {
          $result_kapital_ludzki .= $parsed_sections['lifelong_learning_1'];
        } else {
          $result_kapital_ludzki .= $parsed_sections['lifelong_learning_2'];
        }

        if ( $wynik_krok1_1['competences'] ) {
          $result_kapital_ludzki .= str_replace( '{{competences_result}}', $wynik_krok1_1['competences'], $parsed_sections['competences'] );
        }

        if ( 'yes' === $wynik_krok1_1['recommendations'] ) {
          $result_kapital_ludzki .= $parsed_sections['recommendations_1'];
        } else {
          $result_kapital_ludzki .= $parsed_sections['recommendations_2'];
        }

        if ( false === is_null( $wynik_krok1_1['skills_acquired'] ) ) {
          if ( 10 === $wynik_krok1_1['skills_acquired'] ) {
            $result_kapital_ludzki .= $parsed_sections['skills_acquired_3'];
          } else if ( 15 <= $wynik_krok1_1['skills_acquired'] ) {
            $result_kapital_ludzki .= $parsed_sections['skills_acquired_1'];
          } else {
            $result_kapital_ludzki .= $parsed_sections['skills_acquired_2'];
          }
        }

        if ( false === is_null( $wynik_krok1_1['mobility_experience'] ) ) {
          if ( 2 < (int) $wynik_krok1_1['mobility_experience'] ) {
            $result_kapital_ludzki .= $parsed_sections['mobility_experience_2'];
          } else {
            $result_kapital_ludzki .= $parsed_sections['mobility_experience_1'];
          }
        }

        $result_kapital_ludzki .= '</div>';
      }

      // ---------------------------------------------------------

      if ( isset( $result_kapital_ludzki ) && $result_kapital_ludzki ) {
        $html .= $result_kapital_ludzki;
      }

      $pdf->writeHTML( $html, true, false, true, false, '' );

      // ---------------------------------------------------------

      $name_2 = '<h1 style="color: #059f8e; text-align: center; font-size: 21px;">2. ' . __( 'Kapitał psychologiczny', 'migracja' ) . '</h1>';
      $pdf->writeHTML( $name_2, true, false, true, false, '' );
      $pdf->Ln( 2 );

      $kapital_psychologiczny = $this->page_settings['kapital_psychologiczny'];
      if ( isset( $kapital_psychologiczny['pierwsza_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_psychologiczny_2_1 = '<div>';

        if ( isset( $kapital_psychologiczny['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_psychologiczny_2_1 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_psychologiczny['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $wynik_krok2_1   = $this->badanie_wynik_2_1( $badanie_ID );
        $parsed_sections = $this->parse_text_parts( $kapital_psychologiczny['pierwsza_czesc']['wynik_zwrotny'] );

        if ( false === is_null( $wynik_krok2_1['defining_sentences'] ) ) {
          $result_kapital_psychologiczny_2_1 .= str_replace( '{{defining_sentences_result}}', $wynik_krok2_1['defining_sentences'], $parsed_sections['defining_sentences'] );
        }

        if ( false === is_null( $wynik_krok2_1['hope']['score'] ) ) {
          if ( $wynik_krok2_1['hope']['score'] >= $wynik_krok2_1['hope']['avg'] ) {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['hope_1'];
          } else {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['hope_2'];
          }

          if ( $wynik_krok2_1['self_efficacy']['score'] >= $wynik_krok2_1['self_efficacy']['avg'] ) {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['self_efficacy_1'];
          } else {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['self_efficacy_2'];
          }

          if ( $wynik_krok2_1['resilience']['score'] >= $wynik_krok2_1['resilience']['avg'] ) {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['resilience_1'];
          } else {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['resilience_2'];
          }

          if ( $wynik_krok2_1['optimism']['score'] >= $wynik_krok2_1['optimism']['avg'] ) {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['optimism_1'];
          } else {
            $result_kapital_psychologiczny_2_1 .= $parsed_sections['optimism_2'];
          }
        }

        if ( false === is_null( $wynik_krok2_1['personality'] ) ) {
          $result_kapital_psychologiczny_2_1 .= str_replace( '{{personality_result}}', $wynik_krok2_1['personality'], $parsed_sections['personality'] );
        }

        if ( false === is_null( $wynik_krok2_1['reflexivity'] ) ) {
          $result_kapital_psychologiczny_2_1 .= str_replace( '{{reflexivity_result}}', $wynik_krok2_1['reflexivity'], $parsed_sections['reflexivity'] );
        }

        if ( false === is_null( $wynik_krok2_1['mobility_experience'] ) ) {
          $result_kapital_psychologiczny_2_1 .= str_replace( '{{mobility_experience_result}}', $wynik_krok2_1['mobility_experience'], $parsed_sections['mobility_experience'] );
        }

        $result_kapital_psychologiczny_2_1 .= '</div>';
      }

      // ---------------------------------------------------------


      $html2 = '';
      if ( isset( $result_kapital_psychologiczny_2_1 ) && $result_kapital_psychologiczny_2_1 ) {
        $html2 .= $result_kapital_psychologiczny_2_1;
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

        $wynik_krok3_1 = $this->badanie_wynik_3_1( $badanie_ID );

        if ( isset( $kapital_spoleczny['pierwsza_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_spoleczny_3_1 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_spoleczny['pierwsza_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_spoleczny_3_1 .= str_replace( '{{importance_of_all_contacts_result}}', $wynik_krok3_1['importance_of_all_contacts'], $kapital_spoleczny['pierwsza_czesc']['wynik_zwrotny'] );

        $result_kapital_spoleczny_3_1 .= '</div>';

      }

      if ( isset( $kapital_spoleczny['druga_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_spoleczny_3_2 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">3.2 ' . __( 'Spostrzegane wsparcie społeczne', 'migracja' ) . '</h2>';
        $result_kapital_spoleczny_3_2 .= '<div style="font-size: 13px;">';

        if ( isset( $kapital_spoleczny['druga_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_spoleczny_3_2 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_spoleczny['druga_czesc']['naglowek_wyniku'] . '</p>';
        }

        $parsed_sections = $this->parse_text_parts( $kapital_spoleczny['druga_czesc']['wynik_zwrotny'] );
        $wynik_krok3_2   = $this->badanie_wynik_3_2( $badanie_ID );

        if ( false === is_null( $wynik_krok3_2['relations']['wsp_rodz'] ) ) {
          if ( isset( $wynik_krok3_2['relations']['wsp_rodz'] ) ) {
            $result_kapital_spoleczny_3_2 .= str_replace( '{wynik_krok3_2_wsp_rodz}', number_format( $wynik_krok3_2['relations']['wsp_rodz'], 2, ',', ' ' ), $parsed_sections['relations'] );
          }
          if ( isset( $wynik_krok3_2['relations']['wsp_przyj'] ) ) {
            $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_przyj}', number_format( $wynik_krok3_2['relations']['wsp_przyj'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
          }
          if ( isset( $wynik_krok3_2['relations']['wsp_oz'] ) ) {
            $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_oz}', number_format( $wynik_krok3_2['relations']['wsp_oz'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
          }
          if ( isset( $wynik_krok3_2['relations']['wsp_all'] ) ) {
            $result_kapital_spoleczny_3_2 = str_replace( '{wynik_krok3_2_wsp_all}', number_format( $wynik_krok3_2['relations']['wsp_all'], 2, ',', ' ' ), $result_kapital_spoleczny_3_2 );
          }
        }

        if ( false === is_null( $wynik_krok3_2['mobility_affected_support'] ) ) {
          $result_kapital_spoleczny_3_2 .= str_replace( '{{mobility_affected_support_result}}', $wynik_krok3_2['mobility_affected_support'], $parsed_sections['mobility_affected_support'] );
        }

        $result_kapital_spoleczny_3_2 .= '</div>';
      }

      if ( isset( $kapital_spoleczny['trzecia_czesc']['wynik_zwrotny'] ) ) {

        $result_kapital_spoleczny_3_3 = '<h2 style="color: #059f8e; text-align: center; font-size: 16px;">3.3 ' . __( 'Zaangażowanie społeczno-obywatelskie', 'migracja' ) . '</h2>';
        $result_kapital_spoleczny_3_3 .= '<div style="font-size: 13px;">';

        $parsed_sections = $this->parse_text_parts( $kapital_spoleczny['trzecia_czesc']['wynik_zwrotny'] );
        $wynik_krok3_3   = $this->badanie_wynik_3_3( $badanie_ID );

        if ( isset( $kapital_spoleczny['trzecia_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_spoleczny_3_3 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_spoleczny['trzecia_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_spoleczny_3_3 .= $parsed_sections['general'];

        $result_kapital_spoleczny_3_3 .= str_replace( '{{social_involvement_result}}', $wynik_krok3_3['social_involvement'], $parsed_sections['social_involvement'] );

        if ( false === is_null( $wynik_krok3_3['perceive_aspects_after_return'] ) ) {
          $result_kapital_spoleczny_3_3 .= str_replace( '{{perceive_aspects_after_return_result}}', $wynik_krok3_3['perceive_aspects_after_return'], $parsed_sections['perceive_aspects_after_return'] );
        }

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

        $wynik_krok4_2   = $this->badanie_wynik_4_2( $badanie_ID );

        if ( isset( $kapital_ekonomiczny['druga_czesc']['naglowek_wyniku'] ) ) {
          $result_kapital_ekonomiczny_4_2 .= '<p style="font-weight: bold; color: #059f8e;">' . $kapital_ekonomiczny['trzecia_czesc']['naglowek_wyniku'] . '</p>';
        }

        $result_kapital_ekonomiczny_4_2 .= str_replace( '{{average_financial_literacy_result}}', (
          $wynik_krok4_2['average_financial_literacy'] ?? __( 'No statement', 'migracja' )
        ), $kapital_ekonomiczny['druga_czesc']['wynik_zwrotny'] );

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

      $footer_text = __( 'This document was generated from the research portal <a href="https://mymobility.academy">www.mymobility.academy</a>. The portal was created by the team from the Center for Research on Social Change and Mobility at Kozminski University.', 'migracja' );
      $foot2       = '<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; text-align:center;"><tr><td style="font-size: 11px; line-height: 16px; color:#1d1d1f;">' . $footer_text . '</td></tr></table>';
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
          (int) $badanie->badanie_wyniki_krok1_1,
        )
      )
    );

    $wyniki = [
      'language'                  => null,
      'programs_abroad'           => null,
      'qualification_recognition' => null,
      'certifications'            => null,
      'lifelong_learning'         => null,
      'competences'               => null,
      'recommendations'           => null,
      'skills_acquired'           => null,
      'mobility_experience'       => null
    ];

    $wyniki['language'] = $odp->KROK1_1_97;

    $wyniki['programs_abroad'] = $odp->KROK1_1_32;

    $wyniki['qualification_recognition'] = $odp->KROK1_1_34;

    $wyniki['certifications'] = $odp->KROK1_1_35;

    $wyniki['lifelong_learning'] = $odp->KROK1_1_36;

    $wyniki['competences'] = round(
      (float) ( (int) $odp->KROK1_1_50 + (int) $odp->KROK1_1_51 + (int) $odp->KROK1_1_52 + (int) $odp->KROK1_1_53
                + (int) $odp->KROK1_1_54 + (int) $odp->KROK1_1_55 + (int) $odp->KROK1_1_56 + (int) $odp->KROK1_1_57
                + (int) $odp->KROK1_1_58 + (int) $odp->KROK1_1_59 + (int) $odp->KROK1_1_60 + (int) $odp->KROK1_1_61
                + (int) $odp->KROK1_1_62 + (int) $odp->KROK1_1_63 + (int) $odp->KROK1_1_64 + (int) $odp->KROK1_1_65
                + (int) $odp->KROK1_1_66 + (int) $odp->KROK1_1_67 + (int) $odp->KROK1_1_68 + (int) $odp->KROK1_1_69
                + (int) $odp->KROK1_1_70 + (int) $odp->KROK1_1_71 + (int) $odp->KROK1_1_72 + (int) $odp->KROK1_1_73
                + (int) $odp->KROK1_1_74 + (int) $odp->KROK1_1_75 ) / 26
      , 2 );

    $wyniki['recommendations'] = $odp->KROK1_1_42;

    $wyniki['skills_acquired'] = is_null( $odp->KROK1_1_77 ) ? null :
      (int) $odp->KROK1_1_77 + (int) $odp->KROK1_1_78 + (int) $odp->KROK1_1_79
      + (int) $odp->KROK1_1_80 + (int) $odp->KROK1_1_81 + (int) $odp->KROK1_1_82
      + (int) $odp->KROK1_1_83 + (int) $odp->KROK1_1_84 + (int) $odp->KROK1_1_85
      + (int) $odp->KROK1_1_86 + (int) $odp->KROK1_1_87 + (int) $odp->KROK1_1_88
      + (int) $odp->KROK1_1_89 + (int) $odp->KROK1_1_90 + (int) $odp->KROK1_1_91
      + (int) $odp->KROK1_1_92 + (int) $odp->KROK1_1_93 + (int) $odp->KROK1_1_94
      + (int) $odp->KROK1_1_95 + (int) $odp->KROK1_1_96;

    $wyniki['mobility_experience'] = $odp->KROK1_1_47;

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

    $wyniki = [
      'defining_sentences'  => null,
      'hope'                => [
        'avg'   => null,
        'score' => null,
      ],
      'self_efficacy'       => [
        'avg'   => null,
        'score' => null,
      ],
      'resilience'          => [
        'avg'   => null,
        'score' => null,
      ],
      'optimism'            => [
        'avg'   => null,
        'score' => null,
      ],
      'personality'         => null,
      'reflexivity'         => null,
      'mobility_experience' => null
    ];

    $wyniki['defining_sentences'] = round(
      (float) ( (int) $odp->KROK2_1_2 + (int) $odp->KROK2_1_3 + (int) $odp->KROK2_1_4 + (int) $odp->KROK2_1_5
                + (int) $odp->KROK2_1_6 + (int) $odp->KROK2_1_7 + (int) $odp->KROK2_1_8 + (int) $odp->KROK2_1_9
                + (int) $odp->KROK2_1_10 + (int) $odp->KROK2_1_11 + (int) $odp->KROK2_1_12 ) / 11
      , 2 );

    $wyniki['self_efficacy']['avg']   = round( 4 * 7 / 2 / 4, 2 );
    $wyniki['self_efficacy']['score'] = round(
      (float) ( (int) $odp->KROK2_1_15 + (int) $odp->KROK2_1_16 + (int) $odp->KROK2_1_17 + (int) $odp->KROK2_1_18 ) / 4
      , 2 );

    $wyniki['resilience']['avg']   = round( 3 * 7 / 2 / 3, 2 );
    $wyniki['resilience']['score'] = round(
      (float) ( (int) $odp->KROK2_1_19 + (int) $odp->KROK2_1_20 + (int) $odp->KROK2_1_21 ) / 3
      , 2 );

    $wyniki['hope']['avg']   = round( 3 * 7 / 2 / 3, 2 );
    $wyniki['hope']['score'] = round(
      (float) ( (int) $odp->KROK2_1_25 + (int) $odp->KROK2_1_26 + (int) $odp->KROK2_1_27 ) / 3
      , 2 );

    $wyniki['optimism']['avg']   = round( 2 * 7 / 2 / 2, 2 );
    $wyniki['optimism']['score'] = round(
      (float) ( (int) $odp->KROK2_1_25 + (int) $odp->KROK2_1_26 + (int) $odp->KROK2_1_27 ) / 3
      , 2 );

    $wyniki['personality'] = round(
      (float) ( (int) $odp->KROK2_1_33 + (int) $odp->KROK2_1_34 + (int) $odp->KROK2_1_35 + (int) $odp->KROK2_1_36
                + (int) $odp->KROK2_1_37 + (int) $odp->KROK2_1_38 + (int) $odp->KROK2_1_39 + (int) $odp->KROK2_1_40
                + (int) $odp->KROK2_1_41 + (int) $odp->KROK2_1_42 + (int) $odp->KROK2_1_43 + (int) $odp->KROK2_1_44
                + (int) $odp->KROK2_1_45 + (int) $odp->KROK2_1_46 + (int) $odp->KROK2_1_47 + (int) $odp->KROK2_1_48
                + (int) $odp->KROK2_1_49 + (int) $odp->KROK2_1_50 + (int) $odp->KROK2_1_51 + (int) $odp->KROK2_1_52
      ) / 20
      , 2 );

    $wyniki['reflexivity'] = round(
      (float) ( (int) $odp->KROK2_1_56 + (int) $odp->KROK2_1_57 + (int) $odp->KROK2_1_58 + (int) $odp->KROK2_1_59
                + (int) $odp->KROK2_1_60 + (int) $odp->KROK2_1_61 + (int) $odp->KROK2_1_62 + (int) $odp->KROK2_1_63
                + (int) $odp->KROK2_1_64 + (int) $odp->KROK2_1_65 + (int) $odp->KROK2_1_66
      ) / 11
      , 2 );

    $wyniki['mobility_experience'] = false === is_null( $odp->KROK2_1_74 ) ? (int) $odp->KROK2_1_77 : (
    false === is_null( $odp->KROK2_1_68 ) ? ( round(
      (float) ( (int) $odp->KROK2_1_68 + (int) $odp->KROK2_1_69 + (int) $odp->KROK2_1_70 + (int) $odp->KROK2_1_71
                + (int) $odp->KROK2_1_72 + (int) $odp->KROK2_1_73
      ) / 11
      , 2 ) ) : null
    );

    return $wyniki;
  }

  public function badanie_wynik_3_1( $badanie_ID ) {
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
        "SELECT * FROM `{$this->table_name}_wyniki_krok3_1` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok3_1,
        )
      )
    );

    $wyniki = [
      'importance_of_all_contacts' => $odp->KROK3_1_6
    ];

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

    $wsp_rodz  = is_null( $odp->KROK3_2_3 ) ? null : ( $odp->KROK3_2_3 + $odp->KROK3_2_4 + $odp->KROK3_2_8 + $odp->KROK3_2_11 ) / 4;
    $wsp_przyj = is_null( $odp->KROK3_2_3 ) ? null : ( $odp->KROK3_2_6 + $odp->KROK3_2_7 + $odp->KROK3_2_9 + $odp->KROK3_2_12 ) / 4;
    $wsp_oz    = is_null( $odp->KROK3_2_3 ) ? null : ( $odp->KROK3_2_1 + $odp->KROK3_2_2 + $odp->KROK3_2_5 + $odp->KROK3_2_10 ) / 4;
    $wsp_all   = is_null( $odp->KROK3_2_3 ) ? null : ( $wsp_rodz + $wsp_przyj + $wsp_oz );

    $wyniki                           = array();
    $wyniki['relations']['wsp_rodz']  = $wsp_rodz;
    $wyniki['relations']['wsp_przyj'] = $wsp_przyj;
    $wyniki['relations']['wsp_oz']    = $wsp_oz;
    $wyniki['relations']['wsp_all']   = $wsp_all;

    $wyniki['mobility_affected_support'] = is_null( $odp->KROK3_2_16 ) ? null : round(
      (float) (
        (int) $odp->KROK3_2_16 + (int) $odp->KROK3_2_17 + (int) $odp->KROK3_2_18 + (int) $odp->KROK3_2_19
        + (int) $odp->KROK3_2_20 + (int) $odp->KROK3_2_21 + (int) $odp->KROK3_2_22
      ) / 7, 2 );


    return $wyniki;
  }

  public function badanie_wynik_3_3( $badanie_ID ) {
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
        "SELECT * FROM `{$this->table_name}_wyniki_krok3_3` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok3_3,
        )
      )
    );

    $wyniki['social_involvement'] = is_null( $odp->KROK3_3_1 ) ? null : round(
      (float) (
        (int) $odp->KROK3_3_1 + (int) $odp->KROK3_3_2 + (int) $odp->KROK3_3_3 + (int) $odp->KROK3_3_4
        + (int) $odp->KROK3_3_5 + (int) $odp->KROK3_3_6 + (int) $odp->KROK3_3_7 + (int) $odp->KROK3_3_8
        + (int) $odp->KROK3_3_9 + (int) $odp->KROK3_3_10 + (int) $odp->KROK3_3_11 + (int) $odp->KROK3_3_12
        + (int) $odp->KROK3_3_13 + (int) $odp->KROK3_3_87
      ) / 14, 2 );

    $perceive_aspects_questions_nr           = is_null( $odp->KROK3_3_38 ) ? 11 : 12;
    $wyniki['perceive_aspects_after_return'] = is_null( $odp->KROK3_3_22 ) ? null : round(
      (float) (
        (int) $odp->KROK3_3_22 + (int) $odp->KROK3_3_23 + (int) $odp->KROK3_3_24 + (int) $odp->KROK3_3_25
        + (int) $odp->KROK3_3_28 + (int) $odp->KROK3_3_29 + (int) $odp->KROK3_3_30 + (int) $odp->KROK3_3_31
        + (int) $odp->KROK3_3_32 + (int) $odp->KROK3_3_33 + (int) $odp->KROK3_3_34
        + ( is_null( +(int) $odp->KROK3_3_38 ) ? 0 : (int) $odp->KROK3_3_92 )
      ) / $perceive_aspects_questions_nr
      , 2 );

    return $wyniki;
  }

  function badanie_wynik_4_2( $badanie_ID ) {
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
        "SELECT * FROM `{$this->table_name}_wyniki_krok4_2` WHERE `wynik_ID` = %d",
        array(
          $badanie->badanie_wyniki_krok4_2,
        )
      )
    );

    $wyniki = [
      'average_financial_literacy' => false === is_null( $odp->KROK4_2_4 ) ? null :
        (float) (
          (int) $odp->KROK4_2_5 + (int) $odp->KROK4_2_6 + (int) $odp->KROK4_2_7 + (int) $odp->KROK4_2_8 + (int) $odp->KROK4_2_9
          + (int) $odp->KROK4_2_10 + (int) $odp->KROK4_2_11 + (int) $odp->KROK4_2_12 + (int) $odp->KROK4_2_13 + (int) $odp->KROK4_2_14
        ) / 10,
    ];

    return $wyniki;
  }
}
