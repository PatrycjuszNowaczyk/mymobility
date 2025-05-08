<?php 
/** WIDŻETY **/
function rejestracja_widgetow() {
	register_sidebar(array(
	  'name'		=>	'Pogoda',
	  'id'			=>	'weather',
	  'before_widget' => '<div id="%1$s">',
	  'after_widget' => '</div>',
	  'before_title' => '<span class="title">',
	  'after_title' => '</span>',
	)); 
}
add_action( 'widgets_init', 'rejestracja_widgetow');

