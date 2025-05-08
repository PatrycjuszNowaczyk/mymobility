<?php
/** USTAWIENIA POSTÓW NA GŁÓWNEJ **/
function settings_query_posts( $query ) {

    if ( is_admin() || ! $query->is_main_query() )
        return;
/*
    if ( is_post_type_archive( 'product' ) || is_tax('product_cat') ) :
        $tax_query = array( 'relation'=>'AND' );
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array('pepco'),
            'operator' => 'NOT IN'
        );
        $query->set( 'tax_query', $tax_query); 
    endif;
*/

}
add_action( 'pre_get_posts', 'settings_query_posts', 1 );
