<?php
if ( function_exists( 'acf_add_options_page' ) ) {

  acf_add_options_page( array(
    'page_title'      => 'Strona główna',
    'menu_title'      => 'Strona główna',
    'menu_slug'       => 'homepage',
    'icon_url'        => 'dashicons-admin-home',
    'position'        => '33',
    'update_button'   => __( 'Zaaktualizuj', 'migracja' ),
    'updated_message' => __( "Zmiany zapisane", 'migracja' ),
    'capability'      => 'edit_posts',
    'redirect'        => false
  ) );

  acf_add_options_page( array(
    'page_title'  => 'Slider',
    'menu_title'  => 'Slider',
    'menu_slug'   => 'homepage-slide',
    'parent_slug' => 'homepage',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );

  acf_add_options_page( array(
    'page_title'  => 'O badaniu',
    'menu_title'  => 'O badaniu',
    'menu_slug'   => 'homepage-o-badaniu',
    'parent_slug' => 'homepage',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );

//  acf_add_options_page( array(
//    'page_title'  => 'Skróty do stron',
//    'menu_title'  => 'Skróty do stron',
//    'menu_slug'   => 'homepage-page',
//    'parent_slug' => 'homepage',
//    'capability'  => 'edit_posts',
//    'redirect'    => false
//  ) );

//  acf_add_options_page( array(
//    'page_title'  => 'Zespół',
//    'menu_title'  => 'Zespół',
//    'menu_slug'   => 'homepage-team',
//    'parent_slug' => 'homepage',
//    'capability'  => 'edit_posts',
//    'redirect'    => false
//  ) );



//  acf_add_options_page( array(
//    'page_title'  => 'Publikacje',
//    'menu_title'  => 'Publikacje',
//    'menu_slug'   => 'homepage-publications',
//    'parent_slug' => 'homepage',
//    'capability'  => 'edit_posts',
//    'redirect'    => false
//  ) );

//  acf_add_options_page( array(
//    'page_title'  => 'Współpraca',
//    'menu_title'  => 'Współpraca',
//    'menu_slug'   => 'homepage-partnership',
//    'parent_slug' => 'homepage',
//    'capability'  => 'edit_posts',
//    'redirect'    => false
//  ) );

  acf_add_options_page( array(
    'page_title'  => 'Partnerzy',
    'menu_title'  => 'Partnerzy',
    'menu_slug'   => 'homepage-logos',
    'parent_slug' => 'homepage',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );

  acf_add_options_page( array(
    'page_title'  => 'Doradcy',
    'menu_title'  => 'Doradcy',
    'menu_slug'   => 'homepage-advisors',
    'parent_slug' => 'homepage',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );

  acf_add_options_page( array(
    'page_title'      => 'Stopka',
    'menu_title'      => 'Stopka',
    'menu_slug'       => 'footer',
    'icon_url'        => 'dashicons-list-view',
    'position'        => '33',
    'update_button'   => __( 'Aktualizuj', 'migracja' ),
    'updated_message' => __( "Zmiany zapisane", 'migracja' ),
    'capability'      => 'edit_posts',
    'redirect'        => false
  ) );

  acf_add_options_page( array(
    'page_title'      => 'Moduły',
    'menu_title'      => 'Moduły',
    'menu_slug'       => 'module',
    'icon_url'        => 'dashicons-table-col-after',
    'position'        => '33',
    'update_button'   => __( 'Zaaktualizuj', 'migracja' ),
    'updated_message' => __( "Zmiany zapisane", 'migracja' ),
    'capability'      => 'edit_posts',
    'redirect'        => false
  ) );

  acf_add_options_page( array(
    'page_title'  => 'Inne projekty',
    'menu_title'  => 'Inne projekty',
    'menu_slug'   => 'homepage-projects',
    'parent_slug' => 'module',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );

  acf_add_options_page( array(
    'page_title'  => 'O projekcie',
    'menu_title'  => 'O projekcie',
    'menu_slug'   => 'module-about-project',
    'parent_slug' => 'module',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );


  acf_add_options_page( array(
    'page_title'  => 'Kontakt',
    'menu_title'  => 'Kontakt',
    'menu_slug'   => 'module-contact',
    'parent_slug' => 'module',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );


  acf_add_options_page( array(
    'page_title'  => 'Weź udział',
    'menu_title'  => 'Weź udział',
    'menu_slug'   => 'module-we-udzial',
    'parent_slug' => 'module',
    'capability'  => 'edit_posts',
    'redirect'    => false
  ) );

  acf_add_options_page( array(
    'page_title'      => 'Badania',
    'menu_title'      => 'Badania',
    'menu_slug'       => 'badania',
    'icon_url'        => 'dashicons-groups',
    'position'        => '33',
    'update_button'   => __( 'Zaaktualizuj', 'migracja' ),
    'updated_message' => __( "Zmiany zapisane", 'migracja' ),
    'capability'      => 'edit_posts',
    'redirect'        => false
  ) );
}
