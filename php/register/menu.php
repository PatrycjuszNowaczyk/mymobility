<?php
/** MENU **/
function rejestracja_menu() {
  register_nav_menus(
    array(
      'main-menu' => __( 'Nawigacja top' ),
      'footer-menu' => __( 'Nawigacja stopka' ),
      'lang-menu' => __( 'Język' ),  
      'social-menu' => __( 'Social' ),  
    )
  );
}
add_action( 'init', 'rejestracja_menu' );