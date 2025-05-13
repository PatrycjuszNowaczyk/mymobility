<?php

// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  exit;
}


include_once( plugin_dir_path( __FILE__ ) . '/dashboard/questions.php' );

class Badania_Dashboard extends Badanie {

  private $questions;

  private $wpdb;
  private $table_name;

  public function __construct() {
    global $wpdb;
    $this->wpdb       = $wpdb;
    $this->table_name = $this->wpdb->prefix . 'badania';

    // actions
    add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts_styles' ] );
    add_action( 'admin_menu', [ $this, 'badania_admin_menu' ] );
    add_action( 'wp_ajax_badania_wyniki_usun', [ $this, 'badania_wyniki_usun' ] );
    add_action( 'wp_ajax_badania_wyniki_lista', [ $this, 'badania_wyniki_lista' ] );
    add_action( 'wp_ajax_generuj_csv', [ $this, 'badania_generuj_csv' ] );

    $this->questions = new Pytania;

  }

  public function admin_scripts_styles( $hook ) {
    $ver = '1.0.6';
    if ( $hook == 'badania_page_badania_wyniki' ) {
      wp_enqueue_script( 'badania-results-scripts', get_stylesheet_directory_uri() . '/php/badania/js/results.js', array( 'jquery' ), $ver );
    }
    wp_enqueue_script( 'badania-questions-scripts', get_stylesheet_directory_uri() . '/php/badania/js/questions.js', array( 'jquery' ), $ver );
    wp_enqueue_script( 'badania-questions-csv', get_stylesheet_directory_uri() . '/php/badania/js/csv.js', array( 'jquery' ), $ver );
    wp_enqueue_style( 'badania-style', get_stylesheet_directory_uri() . '/php/badania/css/admin.css', array(), $ver );
  }

  public function badania_admin_menu() {
    add_menu_page(
      'Badania',
      'Badania',
      'edit_posts',
      'badania',
      [ $this, 'badania_glowna_handler' ],
      'dashicons-groups',
      6
    );
    add_submenu_page(
      'badania',
      'Wyniki',
      'Wyniki',
      'edit_posts',
      'badania_wyniki',
      [ $this, 'badania_wyniki_handler' ],
    );
    add_submenu_page(
      'badania',
      'Pytania wstępne',
      'Pytania wstępne',
      'edit_posts',
      'badania_pytania_wstepne',
      [ $this->questions, 'badania_pytania_wstepne_handler' ],
    );
    add_submenu_page(
      'badania',
      'Kapitał ludzki',
      'Kapitał ludzki',
      'edit_posts',
      'badania_pytania_krok1',
      [ $this->questions, 'badania_pytania_krok1_handler' ],
    );
    add_submenu_page(
      'badania',
      'Kapitał psychologiczny',
      'Kapitał psychologiczny',
      'edit_posts',
      'badania_pytania_krok2',
      [ $this->questions, 'badania_pytania_krok2_handler' ],
    );
    add_submenu_page(
      'badania',
      'Kapitał społeczny',
      'Kapitał społeczny',
      'edit_posts',
      'badania_pytania_krok3',
      [ $this->questions, 'badania_pytania_krok3_handler' ],
    );
    add_submenu_page(
      'badania',
      'Kapitał ekonomiczny',
      'Kapitał ekonomiczny',
      'edit_posts',
      'badania_pytania_krok4',
      [ $this->questions, 'badania_pytania_krok4_handler' ],
    );
    add_submenu_page(
      'badania',
      'Metryczka końcowa',
      'Metryczka końcowa',
      'edit_posts',
      'badania_pytania_metryczka',
      [ $this->questions, 'badania_pytania_metryczka_handler' ],
    );
  }

  public function badania_glowna_handler() {
    // empty
  }

  public function badania_wyniki_handler() {
    ?>
    <div class="content-wyniki">
      <div class="pobierz-wyniki">
        <a
          href="#"
          id="pobierz-badania"
          title="<?= __( 'Pobierz wyniki', 'migracja' ); ?>"
          data-current-time="1"
          class="button button-primary button-large"
        ><?= __( 'Pobierz wyniki', 'migracja' ); ?></a>
      </div>
      <table class="table-wyniki">
        <thead>
        <tr>
          <th class="badanie_id">ID</th>
          <th class="badanie_data">Data badania</th>
          <th class="badanie_etap badanie_etap_1">Etap 1</th>
          <th class="badanie_etap badanie_etap_2">Etap 2</th>
          <th class="badanie_etap badanie_etap_3">Etap 3</th>
          <th class="badanie_etap badanie_etap_4">Etap 4</th>
          <th class="badanie_status">Status</th>
          <th class="badanie_email">E-mail</th>
          <th class="badanie_code">Kod</th>
          <th class="badanie_actions">Akcje</th>
        </tr>
        </thead>
        <tbody>
        <?= $this->badania_wyniki_lista(); ?>
        </tbody>
      </table>
    </div>
    <?php
  }

  public function badania_wyniki_lista() {
    $result = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}` ORDER BY badanie_ID" );
    if ( $result != null ) {
      foreach ( $result as $row ) {
        ?>
        <tr
          class="<?php if ( $row->badanie_status == 'zamknięte' ) {
            echo 'zamkniete';
          } else {
            echo 'otwarte';
          } ?>"
        >
          <td class="badanie_id"><?= $row->badanie_ID; ?></td>
          <td class="badanie_data"><?= $row->badanie_date; ?></td>
          <td class="badanie_etap badanie_etap_1">
            <?php if ( $row->badanie_wyniki_krok1_1 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
          </td>
          <td class="badanie_etap badanie_etap_2">
            <?php if ( $row->badanie_wyniki_krok2_1 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
          </td>
          <td class="badanie_etap badanie_etap_3">
            <?php if ( $row->badanie_wyniki_krok3_1 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
            <?php if ( $row->badanie_wyniki_krok3_2 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
            <?php if ( $row->badanie_wyniki_krok3_3 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
          </td>
          <td class="badanie_etap badanie_etap_4">
            <?php if ( $row->badanie_wyniki_krok4_1 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
            <?php if ( $row->badanie_wyniki_krok4_2 > 0 ) {
              echo '<span class="dashicons dashicons-yes"></span>';
            } else {
              echo '<span class="dashicons dashicons-no"></span>';
            }; ?>
          </td>
          <td class="badanie_status"><?= $row->badanie_status; ?></td>
          <td class="badanie_email"><?= $row->badanie_email; ?></td>
          <td class="badanie_code"><?= $row->badanie_code; ?></td>
          <td class="badanie_actions">
            <a href="#" class="badanie_usun" data-badanie_id="<?= $row->badanie_ID; ?>">Usuń</a>
          </td>
        </tr>
        <?php
      }
    } else {
      echo '<tr><td colspan="11" style="text-align: center; font-size: 18px;">Brak wypełnionych badań</td></tr>';
    }
  }

  public function badania_wyniki_usun() {
    $table = $this->table_name;
    $this->wpdb->delete( $table, array( 'badanie_ID' => $_POST['badanie_id'] ) );
    wp_die();
  }


  public function badania_generuj_csv() {

    $array = array();

    $array[0][] = 'ID';
    $array[0][] = 'Data';
    $array[0][] = 'Status';
    $array[0][] = 'E-mail';
    $array[0][] = 'Język';


    $array[0][] = 'Kod';
    $array[0][] = 'Satysfakcja';
    $array[0][] = 'Źródło';
    $array[0][] = 'Nastrój';
    $array[0][] = 'Hasło';
    $array[0][] = 'Pseudonim';

    $krok_wstepne = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_wstepne` ORDER BY kolejnosc" );
    foreach ( $krok_wstepne as $krok_wstepne_row ) {

      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_wstepne_row->pytanie_ID )
        )
      );

      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'WSTEPNE_' . $krok_wstepne_row->ID;
      }
    }

    $array[0][] = '';

    $krok_1_1 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok1_1` ORDER BY kolejnosc" );
    foreach ( $krok_1_1 as $krok_1_1_row ) {

      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_1_1_row->pytanie_ID )
        )
      );

      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK1_1_' . $krok_1_1_row->ID;
      }
    }

    $array[0][] = '';

    $krok_2_1 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok2_1` ORDER BY kolejnosc" );
    foreach ( $krok_2_1 as $krok_2_1_row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_2_1_row->pytanie_ID )
        )
      );
      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK2_1_' . $krok_2_1_row->ID;
      }
    }

    $array[0][] = '';

    $krok_3_1 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok3_1` ORDER BY kolejnosc" );
    foreach ( $krok_3_1 as $krok_3_1_row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_3_1_row->pytanie_ID )
        )
      );
      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK3_1_' . $krok_3_1_row->ID;
      }
    }

    $array[0][] = '';

    $krok_3_2 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok3_2` ORDER BY kolejnosc" );
    foreach ( $krok_3_2 as $krok_3_2_row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_3_2_row->pytanie_ID )
        )
      );
      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK3_2_' . $krok_3_2_row->ID;
      }
    }

    $array[0][] = '';

    $krok_3_3 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok3_3` ORDER BY kolejnosc" );
    foreach ( $krok_3_3 as $krok_3_3_row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_3_3_row->pytanie_ID )
        )
      );
      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK3_3_' . $krok_3_3_row->ID;
      }
    }

    $array[0][] = '';

    $krok_4_1 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok4_1` ORDER BY kolejnosc" );
    foreach ( $krok_4_1 as $krok_4_1_row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_4_1_row->pytanie_ID )
        )
      );
      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK4_1_' . $krok_4_1_row->ID;
      }
    }

    $array[0][] = '';

    $krok_4_2 = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}_krok4_2` ORDER BY kolejnosc" );
    foreach ( $krok_4_2 as $krok_4_2_row ) {
      $pytanie = $this->wpdb->get_row(
        $this->wpdb->prepare(
          "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
          array( $krok_4_2_row->pytanie_ID )
        )
      );
      if ( $pytanie->pytanie_typ != 'text' ) {
        $array[0][] = 'KROK4_2_' . $krok_4_2_row->ID;
      }
    }


    $result = $this->wpdb->get_results( "SELECT * FROM `{$this->table_name}` ORDER BY badanie_ID" );
    if ( $result != null ) {

      $i = 0;
      foreach ( $result as $row ) {
        $i++;
        $array[ $i ][] = $row->badanie_ID;
        $array[ $i ][] = $row->badanie_date;
        $array[ $i ][] = $row->badanie_status;
        $array[ $i ][] = $row->badanie_email;
        $array[ $i ][] = $row->badanie_jezyk;
        $array[ $i ][] = $row->badanie_code;
        $array[ $i ][] = $row->badanie_satysfakcja;
        $array[ $i ][] = $row->badanie_zrodlo;
        $array[ $i ][] = $row->badanie_nastroj;
        $array[ $i ][] = $row->badanie_haslo;
        $array[ $i ][] = $row->badanie_pseudonim;


        $wyniki_wstepne = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_wstepne` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_wstepne )
          )
        );
        $wyniki_1_1     = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok1_1` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok1_1 )
          )
        );
        $wyniki_2_1 = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok2_1` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok2_1 )
          )
        );
        $wyniki_3_1 = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok3_1` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok3_1 )
          )
        );
        $wyniki_3_2 = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok3_2` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok3_2 )
          )
        );
        $wyniki_3_3 = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok3_3` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok3_3 )
          )
        );
        $wyniki_4_1 = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok4_1` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok4_1 )
          )
        );
        $wyniki_4_2 = $this->wpdb->get_row(
          $this->wpdb->prepare(
            "SELECT * FROM `{$this->table_name}_wyniki_krok4_2` WHERE `wynik_ID` = %d",
            array( $row->badanie_wyniki_krok4_2 )
          )
        );


        foreach ( $krok_wstepne as $krok_wstepne_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_wstepne_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_wstepne_row->ID;
            $nazwa_kolumny = 'WSTEPNE_' . $id_krok;
            if ( isset( $wyniki_wstepne->$nazwa_kolumny ) && $wyniki_wstepne->$nazwa_kolumny !== null ) {
              $value = $wyniki_wstepne->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value = str_replace( array( "\r", "\n" ), ' ', $value );


              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_1_1 as $krok_1_1_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_1_1_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_1_1_row->ID;
            $nazwa_kolumny = 'KROK1_1_' . $id_krok;
            if ( isset( $wyniki_1_1->$nazwa_kolumny ) && $wyniki_1_1->$nazwa_kolumny !== null ) {
              $value = $wyniki_1_1->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_2_1 as $krok_2_1_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_2_1_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_2_1_row->ID;
            $nazwa_kolumny = 'KROK2_1_' . $id_krok;
            if ( isset( $wyniki_2_1->$nazwa_kolumny ) && $wyniki_2_1->$nazwa_kolumny !== null ) {
              $value = $wyniki_2_1->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_3_1 as $krok_3_1_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_3_1_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_3_1_row->ID;
            $nazwa_kolumny = 'KROK3_1_' . $id_krok;
            if ( isset( $wyniki_3_1->$nazwa_kolumny ) && $wyniki_3_1->$nazwa_kolumny !== null ) {
              $value = $wyniki_3_1->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_3_2 as $krok_3_2_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_3_2_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_3_2_row->ID;
            $nazwa_kolumny = 'KROK3_2_' . $id_krok;
            if ( isset( $wyniki_3_2->$nazwa_kolumny ) && $wyniki_3_2->$nazwa_kolumny !== null ) {
              $value = $wyniki_3_2->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_3_3 as $krok_3_3_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_3_3_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_3_3_row->ID;
            $nazwa_kolumny = 'KROK3_3_' . $id_krok;
            if ( isset( $wyniki_3_3->$nazwa_kolumny ) && $wyniki_3_3->$nazwa_kolumny !== null ) {
              $value = $wyniki_3_3->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_4_1 as $krok_4_1_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_4_1_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_4_1_row->ID;
            $nazwa_kolumny = 'KROK4_1_' . $id_krok;
            if ( isset( $wyniki_4_1->$nazwa_kolumny ) && $wyniki_4_1->$nazwa_kolumny !== null ) {
              $value = $wyniki_4_1->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

        $array[ $i ][] = '';

        foreach ( $krok_4_2 as $krok_4_2_row ) {
          $pytanie = $this->wpdb->get_row(
            $this->wpdb->prepare(
              "SELECT * FROM `{$this->table_name}_pytania` WHERE `pytanie_ID` = %d",
              array( $krok_4_2_row->pytanie_ID )
            )
          );
          if ( $pytanie->pytanie_typ != 'text' ) {
            $id_krok       = $krok_4_2_row->ID;
            $nazwa_kolumny = 'KROK4_2_' . $id_krok;
            if ( isset( $wyniki_4_2->$nazwa_kolumny ) && $wyniki_4_2->$nazwa_kolumny !== null ) {
              $value = $wyniki_4_2->$nazwa_kolumny;
              if ( strpos( $value, '||' ) !== false ) {
                $value = str_replace( '||', ', ', $value );
              }
              $value         = str_replace( array( "\r", "\n" ), ' ', $value );
              $array[ $i ][] = $value;
            } else {
              $array[ $i ][] = '';
            }
          }
        }

      }
    }

    echo json_encode( $array );

    die();

  }


  // public function badania_generuj_csv2() {
  //     $array = [
  //         ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
  //         ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com']
  //     ];

  //     $columns = ['id', 'name', 'email'];

  //     $exporter = new Exporter();
  //     $exporter->build($array, $columns, 'users.csv')
  //              ->export();


  //              badanie_pdf
  // }
}
