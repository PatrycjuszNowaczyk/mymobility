<?php get_header() ?> 
<section id="page-single" class="box main">
	<div class="wrapper">
	    <?php
	    if ( function_exists('yoast_breadcrumb') ) {
	      yoast_breadcrumb( '<div id="breadcrumbs">','</div>' );
	    }
	    ?>  
		<header class="box_header">
        	<h1><?php echo __('404 Nie ma takiej strony','migracja'); ?></h1>
		</header>
		<div class="box_content">
		   <?php 
		   echo sprintf( __('Nie odnaleziono strony. Wróć do <a href="%s" rel="nofollow" title="Wróć do strony głównej"><strong>strony głównej</strong></a>.','migracja'), esc_url(home_url('/')) ); ?>
		</div>
	</div>
</section> 
<?php get_footer() ?>