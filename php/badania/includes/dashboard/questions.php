<?php
// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

class Pytania {

  public $wpdb;
  public $table_name;

  public function __construct() {
    global $wpdb;
    $this->wpdb = $wpdb;

    $this->table_name = $this->wpdb->prefix . 'badania';

    add_action( 'wp_ajax_badania_pytania_dodaj', [ $this, 'badania_pytania_dodaj' ] );
    add_action( 'wp_ajax_badania_pytania_usun', [ $this, 'badania_pytania_usun' ] );
    add_action( 'wp_ajax_badania_pytania_edytuj', [ $this, 'badania_pytania_edytuj' ] );
    add_action( 'wp_ajax_badania_edycja_pytania', [ $this, 'badania_edycja_pytania' ] );

    add_action( 'wp_ajax_badania_odpowiedz_dodaj', [ $this, 'badania_odpowiedz_dodaj' ] );
    add_action( 'wp_ajax_badania_odpowiedz_form', [ $this, 'badania_odpowiedz_form' ] );
    add_action( 'wp_ajax_badania_odpowiedz_usun', [ $this, 'badania_odpowiedz_usun' ] );

    add_action( 'wp_ajax_badania_pytania_lista_row', [ $this, 'badania_pytania_lista_row' ] );
    add_action( 'wp_ajax_nopriv_badania_odpowiedz_form', [ $this, 'badania_pytania_lista_row' ] );

    add_action( 'wp_ajax_badania_pytania_lista_row_odpowiedzi', [ $this, 'badania_pytania_lista_row_odpowiedzi' ] );
    add_action( 'wp_ajax_nopriv_badania_odpowiedz_form_odpowiedzi', [ $this, 'badania_pytania_lista_row_odpowiedzi' ] );

    add_action( 'wp_ajax_badania_pytania_warunek_pytanie_odpowiedzi', [
      $this,
      'badania_pytania_warunek_pytanie_odpowiedzi'
    ] );
    add_action( 'wp_ajax_nopriv_badania_pytania_warunek_pytanie_odpowiedzi', [
      $this,
      'badania_pytania_warunek_pytanie_odpowiedzi'
    ] );

    add_action( 'wp_ajax_badania_pytania_gora', [ $this, 'badania_pytania_gora' ] );
    add_action( 'wp_ajax_badania_pytania_dol', [ $this, 'badania_pytania_dol' ] );

  }

  public function pytanie_ostatnie() {
    $ostatnie_pytanie = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}_pytania` ORDER BY pytanie_ID DESC LIMIT 1" );

    if ( $ostatnie_pytanie != null ) {
      $id = $ostatnie_pytanie->pytanie_ID + 1;
    } else {
      $id = 1;
    }

    return $id;
  }

  public function krok_ostatni( $krok ) {
    $krok_ostatni = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY ID DESC LIMIT 1" );

    if ( $krok_ostatni != null ) {
      $id = $krok_ostatni->ID + 1;
    } else {
      $id = 1;
    }

    return $id;
  }

  public function badania_pytania_gora() {
    $krok_id = $_POST['krok_id'];
    $krok    = $_POST['krok'];

    $row = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_{$krok}` WHERE `ID` = %d",
        array(
          $krok_id,
        )
      )
    );

    $order      = $row->kolejnosc;
    $order_prev = $order - 1;

    $krok_id_prev = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_{$krok}` WHERE `kolejnosc` = %d",
        array(
          $order_prev,
        )
      )
    );

    $this->wpdb->update( $this->table_name . '_' . $krok, array( 'kolejnosc' => $order_prev ), array( 'ID' => $krok_id ) );
    $this->wpdb->update( $this->table_name . '_' . $krok, array( 'kolejnosc' => $order ), array( 'ID' => $krok_id_prev->ID ) );

    wp_die();
  }

  public function badania_pytania_dol() {
    $krok_id = $_POST['krok_id'];
    $krok    = $_POST['krok'];

    $row = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_{$krok}` WHERE `ID` = %d",
        array(
          $krok_id,
        )
      )
    );

    $order      = $row->kolejnosc;
    $order_next = $order + 1;

    $krok_id_next = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_{$krok}` WHERE `kolejnosc` = %d",
        array(
          $order_next,
        )
      )
    );

    $this->wpdb->update( $this->table_name . '_' . $krok, array( 'kolejnosc' => $order_next ), array( 'ID' => $krok_id ) );
    $this->wpdb->update( $this->table_name . '_' . $krok, array( 'kolejnosc' => $order ), array( 'ID' => $krok_id_next->ID ) );

    wp_die();
  }

  public function badania_pytania_dodaj() {
    $pl  = $_POST['pytanie_pl'];
    $en  = $_POST['pytanie_en'];
    $uk  = $_POST['pytanie_uk'];
    $typ = $_POST['pytanie_typ'];
    if ( isset( $_POST['pytanie_nieobowiazkowe'] ) ) {
      $obowiazek = $_POST['pytanie_nieobowiazkowe'];
    } else {
      $obowiazek = 0;
    }

    if ( isset( $_POST['warunek_odpowiedzi'] ) ) {
      $warunek = implode( ',', $_POST['warunek_odpowiedzi'] );
    }

    $krok       = $_POST['krok'];
    $krok_upper = strtoupper( $_POST['krok'] );

    $pytanie_ID  = $this->pytanie_ostatnie();
    $pytanie_sql = array(
      'pytanie_ID' => $pytanie_ID,
    );
    if ( isset( $pl ) ) {
      $pytanie_sql['pytanie_pl'] = $pl;
    }
    if ( isset( $en ) ) {
      $pytanie_sql['pytanie_en'] = $en;
    }
    if ( isset( $uk ) ) {
      $pytanie_sql['pytanie_uk'] = $uk;
    }
    if ( isset( $typ ) ) {
      $pytanie_sql['pytanie_typ'] = $typ;
    }
    if ( isset( $warunek ) ) {
      $pytanie_sql['pytanie_warunek'] = $warunek;
    }
    if ( isset( $obowiazek ) ) {
      $pytanie_sql['pytanie_nieobowiazkowe'] = $obowiazek;
    }
    if ( isset( $_POST['pytanie_skala_min'] ) && ( ( $typ == 'skala-1-5' ) || ( $typ == 'skala-1-6' ) || ( $typ == 'skala-1-7' ) || ( $typ == 'skala-1-10' ) || ( $typ == 'skala-0-4' ) || ( $typ == 'skala-0-10' ) ) ) {
      $pytanie_sql['pytanie_skala_min'] = $_POST['pytanie_skala_min'];
    }
    if ( isset( $_POST['pytanie_skala_min_uk'] ) && ( ( $typ == 'skala-1-5' ) || ( $typ == 'skala-1-6' ) || ( $typ == 'skala-1-7' ) || ( $typ == 'skala-1-10' ) || ( $typ == 'skala-0-4' ) || ( $typ == 'skala-0-10' ) ) ) {
      $pytanie_sql['pytanie_skala_min_uk'] = $_POST['pytanie_skala_min_uk'];
    }
    if ( isset( $_POST['pytanie_skala_min_en'] ) && ( ( $typ == 'skala-1-5' ) || ( $typ == 'skala-1-6' ) || ( $typ == 'skala-1-7' ) || ( $typ == 'skala-1-10' ) || ( $typ == 'skala-0-4' ) || ( $typ == 'skala-0-10' ) ) ) {
      $pytanie_sql['pytanie_skala_min_en'] = $_POST['pytanie_skala_min_en'];
    }
    if ( isset( $_POST['pytanie_skala_max'] ) && ( ( $typ == 'skala-1-5' ) || ( $typ == 'skala-1-6' ) || ( $typ == 'skala-1-7' ) || ( $typ == 'skala-1-10' ) || ( $typ == 'skala-0-4' ) || ( $typ == 'skala-0-10' ) ) ) {
      $pytanie_sql['pytanie_skala_max'] = $_POST['pytanie_skala_max'];
    }
    if ( isset( $_POST['pytanie_skala_max_uk'] ) && ( ( $typ == 'skala-1-5' ) || ( $typ == 'skala-1-6' ) || ( $typ == 'skala-1-7' ) || ( $typ == 'skala-1-10' ) || ( $typ == 'skala-0-4' ) || ( $typ == 'skala-0-10' ) ) ) {
      $pytanie_sql['pytanie_skala_max_uk'] = $_POST['pytanie_skala_max_uk'];
    }
    if ( isset( $_POST['pytanie_skala_max_en'] ) && ( ( $typ == 'skala-1-5' ) || ( $typ == 'skala-1-6' ) || ( $typ == 'skala-1-7' ) || ( $typ == 'skala-1-10' ) || ( $typ == 'skala-0-4' ) || ( $typ == 'skala-0-10' ) ) ) {
      $pytanie_sql['pytanie_skala_max_en'] = $_POST['pytanie_skala_max_en'];
    }

    $this->wpdb->insert( $this->table_name . '_pytania', $pytanie_sql );

    $krok_ID  = $this->krok_ostatni( $krok );
    $krok_sql = array(
      'ID' => $krok_ID,
    );

    $order_row = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY kolejnosc DESC LIMIT 1" );
    if ( $order_row != null ) {
      $order = $order_row->kolejnosc + 1;
    } else {
      $order = 1;
    }
    if ( isset( $order ) ) {
      $krok_sql['kolejnosc'] = $order;
    }

    if ( isset( $krok ) ) {
      $krok_sql['pytanie_ID'] = $pytanie_ID;
      $this->wpdb->insert( $this->table_name . '_' . $krok, $krok_sql );
    }

    //UTWORZENIE KOLUMNY W WYNIKACH
    if ( isset( $typ ) && $typ != 'text' ) {
      $wyniki_kolumna = $this->wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = '{$this->table_name}_wyniki_{$krok}' AND column_name = '{$krok_upper}_{$krok_ID}'" );

      if ( empty( $wyniki_kolumna ) ) {
        $this->wpdb->query( "ALTER TABLE {$this->table_name}_wyniki_{$krok} ADD {$krok_upper}_{$krok_ID} LONGTEXT" );
      }
    }

    wp_die();
  }

  public function badania_edycja_pytania() {
    $change = array();
    if ( isset( $_POST['pytanie_typ'] ) ) {
      $change['pytanie_typ'] = $_POST['pytanie_typ'];
    }
    if ( isset( $_POST['pytanie_pl'] ) ) {
      $change['pytanie_pl'] = $_POST['pytanie_pl'];
    }
    if ( isset( $_POST['pytanie_en'] ) ) {
      $change['pytanie_en'] = $_POST['pytanie_en'];
    }
    if ( isset( $_POST['pytanie_uk'] ) ) {
      $change['pytanie_uk'] = $_POST['pytanie_uk'];
    }
    if ( isset( $_POST['pytanie_skala_min'] ) ) {
      $change['pytanie_skala_min'] = $_POST['pytanie_skala_min'];
    }
    if ( isset( $_POST['pytanie_skala_min_uk'] ) ) {
      $change['pytanie_skala_min_uk'] = $_POST['pytanie_skala_min_uk'];
    }
    if ( isset( $_POST['pytanie_skala_min_en'] ) ) {
      $change['pytanie_skala_min_en'] = $_POST['pytanie_skala_min_en'];
    }
    if ( isset( $_POST['pytanie_skala_max'] ) ) {
      $change['pytanie_skala_max'] = $_POST['pytanie_skala_max'];
    }
    if ( isset( $_POST['pytanie_skala_max_uk'] ) ) {
      $change['pytanie_skala_max_uk'] = $_POST['pytanie_skala_max_uk'];
    }
    if ( isset( $_POST['pytanie_skala_max_en'] ) ) {
      $change['pytanie_skala_max_en'] = $_POST['pytanie_skala_max_en'];
    }
    if ( isset( $_POST['pytanie_nieobowiazkowe'] ) ) {
      $change['pytanie_nieobowiazkowe'] = $_POST['pytanie_nieobowiazkowe'];
    } else {
      $change['pytanie_nieobowiazkowe'] = 0;
    };
    $this->wpdb->update( $this->table_name . '_pytania', $change, array( 'pytanie_ID' => $_POST['pytanie_ID'] ) );
    wp_die();
  }

  public function badania_pytania_edytuj() {
    $pytanie_ID = $_POST['pytanie_id'];
    $krok       = $_POST['krok'];
    $pytanie    = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
        array(
          $pytanie_ID,
        )
      )
    );
    ?>
    <tr>
      <td colspan="5">
        <form action="#" method="POST" id="form-pytania-zmien">
          <table style="border: 1px solid #c3c4c7">
            <tr>
              <th style="width: 1%; text-align: center; width: 1%; white-space: nowrap;"></th>
              <th class="pytanie-pl">PL</th>
              <th class="pytanie-en">EN</th>
              <th class="pytanie-uk">UK</th>
              <th
                class="pytanie-typ"
                style="width: 130px; text-align: center; white-space: nowrap; padding: 10px; white-space: nowrap;"
              >Typ
              </th>
              <th
                class="pytanie-nieobowiazkowe"
                style="width: 1%; text-align: center; white-space: nowrap; padding: 10px; white-space: nowrap;"
              >Nieobowiązkowe
              </th>
            </tr>
            <tr>
              <td style="white-space: nowrap;">Treść pytania:</td>
              <td>
                <input
                  type="text" name="pytanie_pl" style="width: 100%;"<?php if ( $pytanie->pytanie_pl ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_pl ) . '"';
                } ?>>
              </td>
              <td>
                <input
                  type="text" name="pytanie_en" style="width: 100%;"<?php if ( $pytanie->pytanie_en ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_en ) . '"';
                } ?>>
              </td>
              <td>
                <input
                  type="text" name="pytanie_uk" style="width: 100%;"<?php if ( $pytanie->pytanie_uk ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_uk ) . '"';
                } ?>>
              </td>
              <td style="width: 130px; white-space: nowrap;">
                <select name="pytanie_typ" id="pytanie_typ" style="width: 100%;">
                  <option
                    value="input"<?php if ( $pytanie->pytanie_typ == 'input' ) {
                    echo ' selected';
                  } ?>>input
                  </option>
                  <option
                    value="number"<?php if ( $pytanie->pytanie_typ == 'number' ) {
                    echo ' selected';
                  } ?>>number
                  </option>
                  <option
                    value="textarea"<?php if ( $pytanie->pytanie_typ == 'textarea' ) {
                    echo ' selected';
                  } ?>>textarea
                  </option>
                  <option
                    value="checkbox"<?php if ( $pytanie->pytanie_typ == 'checkbox' ) {
                    echo ' selected';
                  } ?>>checkbox
                  </option>
                  <option
                    value="radio"<?php if ( $pytanie->pytanie_typ == 'radio' ) {
                    echo ' selected';
                  } ?>>radio
                  </option>
                  <option
                    value="select"<?php if ( $pytanie->pytanie_typ == 'select' ) {
                    echo ' selected';
                  } ?>>select
                  </option>
                  <option
                    value="select-kraje"<?php if ( $pytanie->pytanie_typ == 'select-kraje' ) {
                    echo ' selected';
                  } ?>>select kraje
                  </option>
                  <option
                    value="select-urodziny"<?php if ( $pytanie->pytanie_typ == 'select-urodziny' ) {
                    echo ' selected';
                  } ?>>select urodziny
                  </option>
                  <option
                    value="skala-1-5"<?php if ( $pytanie->pytanie_typ == 'skala-1-5' ) {
                    echo ' selected';
                  } ?>>skala 1-5
                  </option>
                  <option
                    value="skala-1-6"<?php if ( $pytanie->pytanie_typ == 'skala-1-6' ) {
                    echo ' selected';
                  } ?>>skala 1-6
                  </option>
                  <option
                    value="skala-1-7"<?php if ( $pytanie->pytanie_typ == 'skala-1-7' ) {
                    echo ' selected';
                  } ?>>skala 1-7
                  </option>
                  <option
                    value="skala-1-10"<?php if ( $pytanie->pytanie_typ == 'skala-1-10' ) {
                    echo ' selected';
                  } ?>>skala 1-10
                  </option>
                  <option
                    value="skala-0-4"<?php if ( $pytanie->pytanie_typ == 'skala-0-4' ) {
                    echo ' selected';
                  } ?>>skala 0-4
                  </option>
                  <option
                    value="skala-0-10"<?php if ( $pytanie->pytanie_typ == 'skala-0-10' ) {
                    echo ' selected';
                  } ?>>skala 0-10
                  </option>
                  <option
                    value="bloki-powtarzalne"<?php if ( $pytanie->pytanie_typ == 'bloki-powtarzalne' ) {
                    echo ' selected';
                  } ?>>bloki powtarzalne
                  </option>
                  <option
                    value="text"<?php if ( $pytanie->pytanie_typ == 'text' ) {
                    echo ' selected';
                  } ?>>text
                  </option>
                  <option
                    value="jezyki"<?php if ( $pytanie->pytanie_typ == 'jezyki' ) {
                    echo ' selected';
                  } ?>>jezyki
                  </option>
                </select>
              </td>
              <td style="text-align: center;">
                <input
                  type="checkbox"
                  value="1"
                  name="pytanie_nieobowiazkowe"<?php if ( $pytanie->pytanie_nieobowiazkowe ) {
                  echo ' checked';
                } ?>>
              </td>
            </tr>
            <tr
              class="pytanie_skala"<?php if ( ( $pytanie->pytanie_typ != 'skala-1-5' ) && ( $pytanie->pytanie_typ != 'skala-1-6' ) && ( $pytanie->pytanie_typ != 'skala-1-7' ) && ( $pytanie->pytanie_typ != 'skala-1-10' ) && ( $pytanie->pytanie_typ != 'skala-0-4' ) && ( $pytanie->pytanie_typ != 'skala-0-10' ) ) {
              echo ' style="display:none;"';
            } ?>>
              <td colspan="3" style="padding-top: 10px;">
                <strong style="display:block; padding-bottom: 5px;">Skrajna minimalna odpowiedź</strong>
                <input
                  type="text"
                  name="pytanie_skala_min"
                  placeholder="PL"
                  style="width: 100%; max-width: 400px;"<?php if ( $pytanie->pytanie_skala_min ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_skala_min ) . '"';
                } ?>>
                <input
                  type="text"
                  name="pytanie_skala_min_uk"
                  placeholder="UK"
                  style="width: 100%; max-width: 400px;"<?php if ( $pytanie->pytanie_skala_min_uk ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_skala_min_uk ) . '"';
                } ?>>
                <input
                  type="text"
                  name="pytanie_skala_min_en"
                  placeholder="EN"
                  style="width: 100%; max-width: 400px;"<?php if ( $pytanie->pytanie_skala_min_en ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_skala_min_en ) . '"';
                } ?>>
              </td>
              <td colspan="3" style="padding-top: 10px; text-align: right;">
                <strong style="display:block; padding-bottom: 5px;">Skrajna maksymalna odpowiedź</strong>
                <input
                  type="text"
                  name="pytanie_skala_max"
                  placeholder="PL"
                  style="width: 100%; max-width: 400px;"<?php if ( $pytanie->pytanie_skala_max ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_skala_max ) . '"';
                } ?>>
                <input
                  type="text"
                  name="pytanie_skala_max_uk"
                  placeholder="UK"
                  style="width: 100%; max-width: 400px;"<?php if ( $pytanie->pytanie_skala_max_uk ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_skala_max_uk ) . '"';
                } ?>>
                <input
                  type="text"
                  name="pytanie_skala_max_en"
                  placeholder="EN"
                  style="width: 100%; max-width: 400px;"<?php if ( $pytanie->pytanie_skala_max_en ) {
                  echo ' value="' . $this->clear_special_char( $pytanie->pytanie_skala_max_en ) . '"';
                } ?>>
              </td>
            </tr>

            <tr style="background: transparent;">
              <td colspan="6" style="text-align: right; padding: 0 20px 20px;">
                <input type="hidden" name="pytanie_ID" value="<?= $pytanie_ID; ?>">
                <input type="hidden" name="krok" value="<?= $krok; ?>">
                <input type="submit" value="zapisz" style="margin-top: 0;">
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <?php

    wp_die();
  }

  public function badania_pytania_usun() {
    $pytanie_ID = $_POST['pytanie_id'];
    $krok_ID    = $_POST['krok_id'];
    $krok       = $_POST['krok'];
    $krok_upper = strtoupper( $_POST['krok'] );

    $pytanie = $this->wpdb->get_row(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
        array(
          $pytanie_ID,
        )
      )
    );

    $this->wpdb->delete( $this->table_name . '_' . $krok, array( 'ID' => $krok_ID ) );
    $this->wpdb->delete( $this->table_name . '_pytania', array( 'pytanie_ID' => $pytanie_ID ) );
    $this->wpdb->delete( $this->table_name . '_odpowiedzi', array( 'pytanie_ID' => $pytanie_ID ) );

    if ( isset( $typ ) && $pytanie->pytanie_typ != 'text' ) {
      $this->wpdb->query( "ALTER TABLE {$this->table_name}_wyniki_{$krok} DROP COLUMN {$krok_upper}_{$krok_ID}" );
    }

    wp_die();
  }

  public function badania_odpowiedz_usun() {
    if ( isset( $_POST['odpowiedz_ID'] ) ) :
      $odpowiedz_ID = $_POST['odpowiedz_ID'];
      $this->wpdb->delete( $this->table_name . '_odpowiedzi', array( 'odpowiedz_ID' => $odpowiedz_ID ) );
    endif;
    wp_die();
  }

  public function badania_odpowiedz_dodaj() {
    if ( isset( $_POST['pytanie_ID'] ) ) {
      $pl         = $_POST['odpowiedz_tresc_pl'];
      $en         = $_POST['odpowiedz_tresc_en'];
      $uk         = $_POST['odpowiedz_tresc_uk'];
      $pytanie_ID = $_POST['pytanie_ID'];
      $wartosc    = $_POST['odpowiedz_wartosc'];

      if ( isset( $_POST['odpowiedz_inne'] ) ) {
        $inne = $_POST['odpowiedz_inne'];
      }

      $odp_ostatni = $this->wpdb->get_row( "SELECT * FROM `{$this->table_name}_odpowiedzi` ORDER BY odpowiedz_ID DESC LIMIT 1" );

      if ( $odp_ostatni != null ) {
        $id = $odp_ostatni->odpowiedz_ID + 1;
      } else {
        $id = 1;
      }

      $odp_sql = array(
        'odpowiedz_ID' => $id,
      );
      if ( !empty( $pl ) ) {
        $odp_sql['odpowiedz_tresc_pl'] = $pl;
      }
      if ( !empty( $en ) ) {
        $odp_sql['odpowiedz_tresc_en'] = $en;
      }
      if ( !empty( $uk ) ) {
        $odp_sql['odpowiedz_tresc_uk'] = $uk;
      }
      if ( !empty( $pytanie_ID ) ) {
        $odp_sql['pytanie_ID'] = $pytanie_ID;
      }
      if ( !empty( $inne ) ) {
        $odp_sql['odpowiedz_inne'] = $inne;
      }
      if ( !empty( $wartosc ) ) {
        $odp_sql['odpowiedz_wartosc'] = $wartosc;
      }

      $this->wpdb->insert( $this->table_name . '_odpowiedzi', $odp_sql );

    } elseif ( isset( $_POST['odpowiedz_ID'] ) ) {

      if ( isset( $_POST['odpowiedz_inne'] ) ) {
        $odpowiedz_inne = $_POST['odpowiedz_inne'];
      } else {
        $odpowiedz_inne = 0;
      }

      $this->wpdb->update( $this->table_name . '_odpowiedzi', array(
        'odpowiedz_tresc_pl' => $_POST['odpowiedz_tresc_pl'],
        'odpowiedz_tresc_en' => $_POST['odpowiedz_tresc_en'],
        'odpowiedz_tresc_uk' => $_POST['odpowiedz_tresc_uk'],
        'odpowiedz_wartosc'  => $_POST['odpowiedz_wartosc'],
        'odpowiedz_inne'     => $odpowiedz_inne
      ), array( 'odpowiedz_ID' => $_POST['odpowiedz_ID'] ) );

    }

    wp_die();
  }

  public function badania_odpowiedz_form() {
    if ( isset( $_POST['pytanie_ID'] ) ) {
      $pytanie_ID        = $_POST['pytanie_ID'];
      $tresc_pl          = '';
      $tresc_en          = '';
      $tresc_uk          = '';
      $odpowiedz_wartosc = '';
      $odpowiedz_inne    = '';
    } elseif ( isset( $_POST['odpowiedz_ID'] ) ) {
      $odpowiedz         = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `odpowiedz_ID` = %d",
          array(
            $_POST['odpowiedz_ID'],
          )
        )
      );
      $tresc_pl          = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl );
      $tresc_en          = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_en );
      $tresc_uk          = $this->clear_special_char( $odpowiedz->odpowiedz_tresc_uk );
      $odpowiedz_wartosc = $odpowiedz->odpowiedz_wartosc;
      $odpowiedz_inne    = $this->clear_special_char( $odpowiedz->odpowiedz_inne );
    }

    $html = '<form action="#" method="POST" class="dodaj_odpowiedz">
        <table style="width: 100%; background: #f1f1f1;">
            <tr>
                <th class="pytanie-pl" style="width: 33.333%; text-align: center;">PL</th>
                <th class="pytanie-en" style="width: 33.333%; text-align: center;">EN</th>
                <th class="pytanie-uk" style="width: 33.333%; text-align: center;">UK</th>
                <th class="pytanie-uk" style="width: 1%; white-space: nowrap; text-align: center;">Wartość</th>
            </tr>
            <tr>
                <td><input type="text" name="odpowiedz_tresc_pl" style="width: 100%;" value="' . $tresc_pl . '"></td>
                <td><input type="text" name="odpowiedz_tresc_en" style="width: 100%;" value="' . $tresc_en . '"></td>
                <td><input type="text" name="odpowiedz_tresc_uk" style="width: 100%;" value="' . $tresc_uk . '"></td>
                <td><input type="text" name="odpowiedz_wartosc" style="width: 100px; text-align: center;" value="' . $odpowiedz_wartosc . '"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <label for="odpowiedz_inne">';
    if ( $odpowiedz_inne ) {
      $html .= '<input type="checkbox" value="1" name="odpowiedz_inne" id="odpowiedz_inne" checked>';
    } else {
      $html .= '<input type="checkbox" value="1" name="odpowiedz_inne" id="odpowiedz_inne">';
    }

    $html .= '<span>Dodaj pole do uzupełnienia (inne)</span>
                    </label>
                </td>
                <td colspan="2" style="text-align: right">';
    if ( isset( $pytanie_ID ) ) {
      $html .= '<input type="hidden" name="pytanie_ID" value="' . $pytanie_ID . '">';
    } elseif ( isset( $_POST['odpowiedz_ID'] ) ) {
      $html .= '<input type="hidden" name="odpowiedz_ID" value="' . $_POST['odpowiedz_ID'] . '">';
    }
    $html .= '
                    <input type="submit" value="wyślij">
                </td>
            </tr>
        </table>
        </form>';

    echo $html;
    wp_die();
  }

  public function badania_pytania_warunek_pytanie( $krok ) {
    ?>
    <strong>Pytanie ID:</strong>
    <select name="warunek_pytanie_ID">
      <option value="brak" selected>Wybierz</option>
      <?php
      $result = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY ID" );
      foreach ( $result as $row ) {
        $pytanie = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
            array(
              $row->pytanie_ID,
            )
          )
        );
        if ( $pytanie->pytanie_typ == 'checkbox' || $pytanie->pytanie_typ == 'radio' || $pytanie->pytanie_typ == 'select' ) : ?>
          <option value="<?= $row->pytanie_ID; ?>">
            <?= $row->ID; ?>
          </option>
        <?php
        endif;
      }
      ?>
    </select>
    <?php
  }

  public function badania_pytania_warunek_pytanie_odpowiedzi() {
    if ( isset( $_POST['pytanie_ID'] ) ) {
      $id = $_POST['pytanie_ID'];
    }

    $odpowiedzi = $this->wpdb->get_results(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `pytanie_ID` = %d",
        array(
          $id,
        )
      )
    );
    if ( $odpowiedzi ) {
      ?>
      <div class="warunek_odpowiedzi">
        <h3>Odpowiedzi</h3>
        <ul>
          <?php foreach ( $odpowiedzi as $odpowiedz ) { ?>
            <li>
              <label for="warunek_odpowiedzi">
                <input type="checkbox" name="warunek_odpowiedzi[]" value="<?= $odpowiedz->odpowiedz_ID; ?>">
                <span><?= $odpowiedz->odpowiedz_tresc_pl; ?></span>
              </label>
            </li>
          <?php } ?>
        </ul>
      </div>
      <?php
    }
    wp_die();
  }

  public function badania_pytania_form( $krok ) {
    ?>
    <div class="postbox acf-postbox">
      <div class="postbox-header">
        <h2>Dodaj pytanie</h2>
      </div>
      <div class="inside">
        <div class="badania-field">
          <form action="#" method="POST" id="form-pytania" class="form-pytania">
            <table>
              <tr>
                <th style="width: 1%; text-align: center; width: 1%; white-space: nowrap; padding: 20px;"></th>
                <th class="pytanie-pl" style="text-align: center; width: 33.33%;">PL</th>
                <th class="pytanie-en" style="text-align: center; width: 33.33%;">EN</th>
                <th class="pytanie-uk" style="text-align: center; width: 33.33%;">UK</th>
                <th
                  class="pytanie-typ"
                  style="width: 150px; text-align: center; white-space: nowrap; padding: 20px; white-space: nowrap;"
                >Typ
                </th>
                <th
                  class="pytanie-warunek"
                  style="text-align: center; width: 1%; white-space: nowrap; padding: 20px;"
                >Warunek
                </th>
                <th
                  class="pytanie-warunek"
                  style="text-align: center; width: 1%; white-space: nowrap; padding: 20px;"
                >Nieobowiązkowe
                </th>
              </tr>
              <tr>
                <td style="white-space: nowrap;">Treść pytania:</td>
                <td>
                  <input type="text" name="pytanie_pl" style="width: 100%;">
                </td>
                <td>
                  <input type="text" name="pytanie_en" style="width: 100%;">
                </td>
                <td>
                  <input type="text" name="pytanie_uk" style="width: 100%;">
                </td>
                <td style="width: 150px; white-space: nowrap;">
                  <select name="pytanie_typ" id="pytanie_typ" style="width: 100%;">
                    <option value="input">input</option>
                    <option value="number">number</option>
                    <option value="textarea">textarea</option>
                    <option value="checkbox">checkbox</option>
                    <option value="radio">radio</option>
                    <option value="select">select</option>
                    <option value="select-kraje">select kraje</option>
                    <option value="select-urodziny">select urodziny</option>
                    <option value="skala-1-5">skala 1-5</option>
                    <option value="skala-1-6">skala 1-6</option>
                    <option value="skala-1-7">skala 1-7</option>
                    <option value="skala-1-10">skala 1-10</option>
                    <option value="skala-0-4">skala 0-4</option>
                    <option value="skala-0-10">skala 0-10</option>
                    <option value="bloki-powtarzalne">bloki powtarzalne</option>
                    <option value="text">text</option>
                    <option value="jezyki">jezyki</option>
                  </select>
                </td>
                <td style="text-align: center;">
                  <input type="checkbox" value="tak" name="pytanie_warunek">
                </td>
                <td style="text-align: center;">
                  <input type="checkbox" value="1" name="pytanie_nieobowiazkowe">
                </td>
              </tr>
              <tr class="pytanie_skala" style="display:none;">
                <td colspan="3" style="padding-top: 10px;">
                  <strong style="display:block; padding-bottom: 5px;">Skrajna minimalna odpowiedź</strong>
                  <div>
                    <input type="text" name="pytanie_skala_min" placeholder="PL" style="width: 100%; max-width: 400px;">
                  </div>
                  <div>
                    <input
                      type="text"
                      name="pytanie_skala_min_uk"
                      placeholder="UK"
                      style="width: 100%; max-width: 400px;"
                    >
                  </div>
                  <div>
                    <input
                      type="text"
                      name="pytanie_skala_min_en"
                      placeholder="EN"
                      style="width: 100%; max-width: 400px;"
                    >
                  </div>
                </td>
                <td colspan="4" style="padding-top: 10px; text-align: right;">
                  <strong style="display:block; padding-bottom: 5px;">Skrajna maksymalna odpowiedź</strong>
                  <div>
                    <input type="text" name="pytanie_skala_max" placeholder="PL" style="width: 100%; max-width: 400px;">
                  </div>
                  <div>
                    <input
                      type="text"
                      name="pytanie_skala_max_uk"
                      placeholder="UK"
                      style="width: 100%; max-width: 400px;"
                    >
                  </div>
                  <div>
                    <input
                      type="text"
                      name="pytanie_skala_max_en"
                      placeholder="EN"
                      style="width: 100%; max-width: 400px;"
                    >
                  </div>
                </td>
              </tr>
              <tr id="warunek-lista" style="display:none;">
                <td colspan="6">
                  <?php $this->badania_pytania_warunek_pytanie( $krok ); ?>
                </td>
              </tr>
              <tr>
                <td colspan="7" style="text-align: right;">
                  <input type="hidden" name="krok" value="<?= $krok; ?>">
                  <input type="submit" value="zapisz">
                </td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
    <?php
  }

  public function badania_pytania_lista( $krok ) {
    ?>
    <div class="postbox acf-postbox">
      <div class="postbox-header">
        <h2>Lista pytań</h2>
      </div>
      <div class="inside">
        <div class="badania-field" id="pytania">
          <table class="badania-pytania-table table-<?= $krok; ?>">
            <thead>
            <tr>
              <th class="id">ID</th>
              <th class="name">Pytanie</th>
              <th class="type">Rodzaj</th>
              <th class="conditional">Warunki</th>
              <th class="actions">Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php $this->badania_pytania_lista_row( $krok ); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php
  }

  public function clear_special_char( $string ) {
    return str_replace( array( '\"', "\'" ), array( '"', "'" ), $string );
  }

  public function badania_pytania_lista_row( $krok = '' ) {
    if ( empty( $krok ) ) {
      $krok = $_POST['krok'];
    }

    $krok_upper = strtoupper( $krok );

    $result = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_{$krok}` ORDER BY kolejnosc" );
    foreach ( $result as $row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array(
            $row->pytanie_ID,
          )
        )
      );
      ?>
      <tr>
        <td class="id">
          <span><a
              href="#"
              data-krok="<?= $krok; ?>"
              data-pytanie_id="<?= $row->pytanie_ID; ?>"
              data-krok_id="<?= $row->ID; ?>"
              class="pytanie_przesun_gora"
            >do góry</a></span>
          <span><?= $krok_upper . '_' . $row->ID; ?></span>
          <span><a
              href="#"
              data-krok="<?= $krok; ?>"
              data-pytanie_id="<?= $row->pytanie_ID; ?>"
              data-krok_id="<?= $row->ID; ?>"
              class="pytanie_przesun_dol"
            >w dół</a></span>
        </td>
        <td class="name">
          <strong class="flex">
                        <span>
                            <?= $this->clear_special_char( $pytanie->pytanie_pl ); ?>
                            <?php if ( $pytanie->pytanie_nieobowiazkowe == 0 || $pytanie->pytanie_nieobowiazkowe == null ) {
                              echo '<span style="color: red;">*</span>';
                            } ?>
                        </span>
            <?php if ( !empty( $pytanie->pytanie_uk ) ) { ?>
              <span class="flag uk" data-text="<?= $this->clear_special_char( $pytanie->pytanie_uk ); ?>"></span>
            <?php } ?>
            <?php if ( !empty( $pytanie->pytanie_en ) ) { ?>
              <span class="flag en" data-text="<?= $this->clear_special_char( $pytanie->pytanie_en ); ?>"></span>
            <?php } ?>
          </strong>
          <?php if ( $pytanie->pytanie_typ == 'checkbox' || $pytanie->pytanie_typ == 'radio' || $pytanie->pytanie_typ == 'select' ) { ?>
            <div class="lista-odpowiedzi">
              <?php $this->badania_pytania_lista_row_odpowiedzi( $row->pytanie_ID ); ?>
            </div>
          <?php } elseif ( ( $pytanie->pytanie_typ == 'skala-1-5' ) || ( $pytanie->pytanie_typ == 'skala-1-6' ) || ( $pytanie->pytanie_typ == 'skala-1-7' ) || ( $pytanie->pytanie_typ == 'skala-1-10' ) || ( $pytanie->pytanie_typ == 'skala-0-4' ) || ( $pytanie->pytanie_typ == 'skala-0-10' ) ) {
            if ( $pytanie->pytanie_skala_min ) {
              echo '<div>Skala minimalna:</strong> ' . $pytanie->pytanie_skala_min;
            }
            if ( $pytanie->pytanie_skala_max ) {
              echo '<div>Skala maksymalna:</strong> ' . $pytanie->pytanie_skala_max;
            }
            ?>
          <?php } ?>
        </td>
        <td class="type">
          <?= $pytanie->pytanie_typ; ?>
        </td>
        <td class="conditional">
          <?= $pytanie->pytanie_warunek; ?>
        </td>
        <td class="actions">
          <ul>
            <li>
              <a
                href="#"
                class="pytanie_odpowiedzi"<?php if ( $pytanie->pytanie_typ != 'checkbox' && $pytanie->pytanie_typ != 'radio' && $pytanie->pytanie_typ != 'select' ) {
                echo ' style="display:none;"';
              } ?>
                data-pytanie_id="<?= $row->pytanie_ID; ?>"
              >Dodaj odp.
              </a>
            </li>
            <li>
              <a
                href="#"
                class="pytanie_edytuj"
                data-krok="<?= $krok; ?>"
                data-pytanie_id="<?= $row->pytanie_ID; ?>"
                data-krok_id="<?= $row->ID; ?>"
              >Edytuj
              </a>
            </li>
            <li>
              <a
                href="#"
                class="pytanie_usun"
                data-krok="<?= $krok; ?>"
                data-pytanie_id="<?= $row->pytanie_ID; ?>"
                data-krok_id="<?= $row->ID; ?>"
              >Usuń
              </a>
            </li>
          </ul>
        </td>
      </tr>
      <?php
    }
  }

  public function badania_pytania_lista_row_odpowiedzi( $pytanie_ID ) {
    if ( $pytanie_ID ) {
      $id = $pytanie_ID;
    } elseif ( isset( $_POST['pytanie_ID'] ) ) {
      $id = $_POST['pytanie_ID'];
    } elseif ( $_POST['odpowiedz_ID'] ) {
      $row = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `odpowiedz_ID` = %d",
          array(
            $_POST['odpowiedz_ID'],
          )
        )
      );
      $id  = $row->pytanie_ID;
    }
    $odpowiedzi = $this->wpdb->get_results(
      $this->wpdb->prepare(
        "SELECT * FROM `{$this->table_name}_odpowiedzi` WHERE `pytanie_ID` = %d",
        array(
          $id,
        )
      )
    );
    ?>
    <ol>
      <?php foreach ( $odpowiedzi as $odpowiedz ) { ?>
        <li>

                <span class="flex">
                    <span><?= $this->clear_special_char( $odpowiedz->odpowiedz_tresc_pl ); ?><?php if ( $odpowiedz->odpowiedz_wartosc != null ) {
                        echo ' (wartość: ' . $this->clear_special_char( $odpowiedz->odpowiedz_wartosc ) . ')';
                      } ?></span>
                    <?php if ( !empty( $odpowiedz->odpowiedz_tresc_uk ) ) { ?>
                      <span
                        class="flag uk"
                        data-text="<?= $this->clear_special_char( $odpowiedz->odpowiedz_tresc_uk ); ?>"
                      ></span>
                    <?php } ?>
                  <?php if ( !empty( $odpowiedz->odpowiedz_tresc_en ) ) { ?>
                    <span
                      class="flag en"
                      data-text="<?= $this->clear_special_char( $odpowiedz->odpowiedz_tresc_en ); ?>"
                    ></span>
                  <?php } ?>
                    <span><a
                        href="#"
                        class="odpowiedz-edytuj"
                        data-odpowiedz_ID="<?= $odpowiedz->odpowiedz_ID; ?>"
                      >[edytuj]</a></span>
                    <span><a
                        href="#"
                        class="odpowiedz-usun"
                        data-odpowiedz_ID="<?= $odpowiedz->odpowiedz_ID; ?>"
                      >[usuń]</a></span>
                </span>

        </li>
      <?php } ?>
    </ol>
    <?php
    if ( isset( $_POST['pytanie_ID'] ) || isset( $_POST['odpowiedz_ID'] ) ) {
      wp_die();
    }
  }

  public function badania_pytania_wstepne_handler() {
    ?>
    <div id="wpbody-content">
      <div class="wrap acf-settings-wrap">
        <h1 style="margin-bottom: 10px;">Pytania wstępne</h1>
        <div id="poststuff">
          <?php $this->badania_pytania_form( 'wstepne' ); ?>
          <?php $this->badania_pytania_lista( 'wstepne' ); ?>
        </div>
      </div>
    </div>
    <?php
  }

  public function badania_pytania_krok1_handler() {
    ?>
    <div id="wpbody-content">
      <div class="wrap acf-settings-wrap">
        <h1 style="margin-bottom: 10px;">1. Kapitał ludzki</h1>
        <div id="poststuff">
          <?php $this->badania_pytania_form( 'krok1_1' ); ?>
          <?php $this->badania_pytania_lista( 'krok1_1' ); ?>
        </div>
      </div>
    </div>
    <?php
  }

  public function badania_pytania_krok2_handler() {
    ?>
    <div id="wpbody-content">
      <div class="wrap acf-settings-wrap">
        <h1 style="margin-bottom: 10px;">2. Kapitał psychologiczny</h1>
        <div id="poststuff">
          <?php $this->badania_pytania_form( 'krok2_1' ); ?>
          <?php $this->badania_pytania_lista( 'krok2_1' ); ?>
        </div>
      </div>
    </div>
    <?php
  }

  public function badania_pytania_krok3_handler() {
    ?>
    <div id="wpbody-content">
      <div class="wrap acf-settings-wrap">
        <h1 style="margin-bottom: 10px;">3. Kapitał społeczny</h1>
        <div id="poststuff">
          <div class="questions-group">
            <h2>3.1 Relacje społeczne</h2>
            <div class="questions-group-content">
              <?php $this->badania_pytania_form( 'krok3_1' ); ?>
              <?php $this->badania_pytania_lista( 'krok3_1' ); ?>
            </div>
          </div>
          <div class="questions-group">
            <h2>3.2 Spostrzegane wsparcie społeczne</h2>
            <div class="questions-group-content">
              <?php $this->badania_pytania_form( 'krok3_2' ); ?>
              <?php $this->badania_pytania_lista( 'krok3_2' ); ?>
            </div>
          </div>
          <div class="questions-group">
            <h2>3.3 Zaangażowanie społeczno-obywatelskie</h2>
            <div class="questions-group-content">
              <?php $this->badania_pytania_form( 'krok3_3' ); ?>
              <?php $this->badania_pytania_lista( 'krok3_3' ); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public function badania_pytania_krok4_handler() {
    ?>
    <div id="wpbody-content">
      <div class="wrap acf-settings-wrap">
        <h1 style="margin-bottom: 10px;">4. Kapitał ekonomiczny</h1>
        <div id="poststuff">
          <div class="questions-group">
            <h2>4.1 Sposób gospodarowania pieniędzmi</h2>
            <div class="questions-group-content">
              <?php $this->badania_pytania_form( 'krok4_1' ); ?>
              <?php $this->badania_pytania_lista( 'krok4_1' ); ?>
            </div>
          </div>
          <div class="questions-group">
            <h2>4.2 Stabilność zawodowa</h2>
            <div class="questions-group-content">
              <?php $this->badania_pytania_form( 'krok4_2' ); ?>
              <?php $this->badania_pytania_lista( 'krok4_2' ); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public function badania_pytania_metryczka_handler() {
    ?>
    <div id="wpbody-content">
      <div class="wrap acf-settings-wrap">
        <h1 style="margin-bottom: 10px;">Metryczka końcowa</h1>
        <div id="poststuff">
          <?php $this->badania_pytania_form( 'metryczka' ); ?>
          <?php $this->badania_pytania_lista( 'metryczka' ); ?>
        </div>
      </div>
    </div>
    <?php
  }
}
