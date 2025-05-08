<?php

function post_type_register() {
  $labels = array(
    'name'          => __( 'Zespół', 'migracja' ),
    'singular_name' => __( 'Zespół', 'migracja' ),
    /*
        'add_new'            => 'Dodaj nowy',
        'add_new_item'       => 'Dodaj nowy lookbook',
        'edit_item'          => 'Edytuj lookbook',
        'new_item'           => 'Nowy lookbook',
        'view_item'          => 'Zobacz lookbook',
        'search_items'       => 'Szukaj lookbooka',
        'not_found'          => 'Nie znaleziono',
        'not_found_in_trash' => 'Nie znaleziono w koszu',
        'parent_item_colon'  => '',
    */
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => false,
    'show_ui'            => true,
    'query_var'          => true,
    'menu_icon'          => 'dashicons-admin-users',
    'rewrite'            => true,
//        'capability_type'      => 'post',
    'hierarchical'       => false,
    'has_archive'        => false,
    'supports'           => array( 'title' ),
    'rewrite'            => array( "slug" => "zespol" ),
    'menu_position'      => 40,
  );
  register_post_type( 'zespol', $args );


}

add_action( 'init', 'post_type_register' );