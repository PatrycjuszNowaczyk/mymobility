<?php
get_header(); 
while ( have_posts()) : the_post();
?>
<main class="section" id="page-template">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-8 col-md-10 col-xs-12">
                <header class="section-title text-blue">
                    <h1 class="<?php if(get_field('dodatkowy_naglowek')) { echo 'claim'; } else { echo 'title'; } ?>"><?php the_title(); ?></h1>
                    <?php if(get_field('dodatkowy_naglowek')) : ?>
                    <span class="title"><?= get_field('dodatkowy_naglowek'); ?></span>
                    <?php endif; ?>
                </header>
                <div class="content editor">
	                <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php 
endwhile;
get_template_part('include/module/about-project');
get_template_part('include/module/wez-udzial');
get_footer();
?>