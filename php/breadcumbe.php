<?php

function yoast_seo_breadcrumb_append_link( $links ) {
    global $post;
/*
    if ( is_singular ( 'maszyny' ) ) {
        $breadcrumb[] = array(
            'url' => get_post_type_archive_link('oferta'),
            'text' => __('Oferta','wsk'),
        );
        $breadcrumb[] = array(
            'url' => get_permalink(67),
            'text' => __('Usługi produkcyjne','wsk'),
        );

        array_splice( $links, 1, -2, $breadcrumb );
    }
*/
    return $links;
}
add_filter( 'wpseo_breadcrumb_links', 'yoast_seo_breadcrumb_append_link' );