<?php
function register_own_taxonomy(){
    $args = array( 
        'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => false,
        'rewrite'           => array('slug' => 'oferta-kategorie'),    
    );
    register_taxonomy('kat_oferta', 'oferta', $args);      
}
add_action('init','register_own_taxonomy'); 