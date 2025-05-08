<?php

// files php 
require_once __DIR__.'/php/aggregate.php';


/***************/

function ogolny_js_init() { 
	if (!is_admin() && $GLOBALS['pagenow'] != 'wp-login.php' ) {
		if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
			wp_enqueue_script( 'livereload', 'http://localhost:35729/livereload.js?snipver=1', null, false, true);
		}
	   wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.7', true );
	}
}
add_action('init', 'ogolny_js_init');


// SHOW ADMIN BAR
show_admin_bar(false);


// SWIPER
if (!is_admin()) add_action("wp_enqueue_scripts", "swiper_style_script", 11);
function swiper_style_script() {
   wp_enqueue_script('swiper-js', "https://unpkg.com/swiper@7/swiper-bundle.min.js", false, null);
   wp_enqueue_style('swiper-css', "https://unpkg.com/swiper@7/swiper-bundle.min.css", false, null);
}

add_filter('use_block_editor_for_post', '__return_false', 10);

/*
function remove_yoast_metabox_reservations(){
    remove_meta_box('wpseo_meta', 'o_mnie', 'normal');
}
add_action( 'add_meta_boxes', 'remove_yoast_metabox_reservations',11 );
*/




?>