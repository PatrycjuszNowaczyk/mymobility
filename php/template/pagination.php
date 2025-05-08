<?php
/** PAGINACJA **/
function wpbeginner_numeric_posts_nav() {
	if( is_singular() )
	  return;

	global $wp_query;
	if( $wp_query->max_num_pages <= 1 )
	  return;
	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );
	if ( $paged >= 1 )
	  $links[] = $paged;
	if ( $paged >= 3 ) {
	  $links[] = $paged - 1;
	  $links[] = $paged - 2;
	}
	if ( ( $paged + 2 ) <= $max ) {
	  $links[] = $paged + 2;
	  $links[] = $paged + 1;
	}
	echo '<div class="navigation"><ul>' . "\n";
	if ( get_previous_posts_link() ) {
	  printf( '<li class="prev">%s</li>' . "\n", get_previous_posts_link('<span>&laquo; Poprzednie</span>') );
	} else {
	  printf('<li class="prev_no"> </li>');
	}
	if ( ! in_array( 1, $links ) ) {
	  $class = 1 == $paged ? ' active' : '';
	  printf( '<li class="num%s"><a href="%s"><span>%s</span></a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
	  if ( ! in_array( 2, $links ) )
		echo '<li class="num">…</li>';
	}
	sort( $links );
	foreach ( (array) $links as $link ) {
	  $class = $paged == $link ? ' active' : '';
	  printf( '<li class="num%s"><a href="%s"><span>%s</span></a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}
	if ( ! in_array( $max, $links ) ) {
	  if ( ! in_array( $max - 1, $links ) )
		echo '<li class="num">…</li>' . "\n";
	  $class = $paged == $max ? ' active' : '';
	  printf( '<li class="num%s"><a href="%s"><span>%s</span></a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}
	if ( get_next_posts_link() ) { 
	  printf( '<li class="next">%s</li>' . "\n", get_next_posts_link('<span>Następne &raquo;</span>') );
	} else {
	  printf('<li class="next_no"> </li>');
	}
	echo '</ul></div>' . "\n";
}