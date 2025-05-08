<?php
get_header(); 
while ( have_posts()) : the_post();
//  $img = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'img_924_400_true' );
	$title = get_field('single_naglowek');
?>
<section id="single-post" class="box main">
	<div class="section_head">
		<div class="wrapper">
			<?php get_template_part('include/breadcumbe'); ?>
			<header>
				<h1><?php the_title(); ?></h1>
			</header>
			<?php if(!empty($title['opis'])) { ?>
			<div class="text">
				<div class="content">
					<?= $title['opis']; ?>
				</div>
			</div>
			<?php } ?>

			<?php if(!empty($title['kompozycja'])) { ?>
			<div class="img">
				<?= wp_get_attachment_image( $title['kompozycja'], 'img_530_280_false' ); ?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<?php 
endwhile;
get_footer();
?>