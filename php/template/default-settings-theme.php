<?php
if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', get_stylesheet_directory_uri()."/js/lib/jquery.js", false, null);
   wp_enqueue_script('jquery');
}

/** ATRYBUTY POSTU **/
function atrybuty_postu() {
    add_post_type_support('post', 'page-attributes');
}
add_action('init', 'atrybuty_postu');


/** ZMIANA MIEJSCA PASKA ADMINA **/
function panel_admina_strona() {
	if ( is_admin_bar_showing() ) { ?>
		<style type="text/css" media="screen">
			html { margin-top: 0 !important; }
			* html body { margin-top: 0 !important; }
		</style>
	<?php }
}
add_action( 'wp_head', 'panel_admina_strona', 11 );


/** USUWA INFO O WERSJI **/
function usun_info_wersji() {
  return '';
}
add_filter('the_generator', 'usun_info_wersji');
remove_action('wp_head', 'wp_generator');


/** ZABEZPIECZENIA **/
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
function wrong_login() {
  return 'Zły login lub hasło.';
}
add_filter('login_errors', 'wrong_login');


/** USUWANIE FONTU Z HEAD **/
function usun_open_sans() {
  wp_deregister_style( 'open-sans' );
  wp_register_style( 'open-sans', false );
}
add_action('wp_enqueue_scripts', 'usun_open_sans');


/** ILOSC ZNAKOW **/
function ilosc_znakow_postu( $length ) {
	return 500;
}
add_filter ('excerpt_length', 'ilosc_znakow_postu', 999);


/** CZYTAJ WIĘCEJ **/
function dodaj_czytaj_wiecej($more) {
   global $post;
   return ' ...';
}
add_filter('excerpt_more', 'dodaj_czytaj_wiecej');


function naglowek_strony(){ ?>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php 
}
add_action('wp_head', 'naglowek_strony');

