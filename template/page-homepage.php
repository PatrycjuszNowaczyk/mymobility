<?php
/**
* Template Name: Strona główna
*/
get_header(); 
while ( have_posts()) : the_post();
?>
<section class="section" id="main">
    <div class="wrapper">
        <div class="row middle-md">
            <!-- <div class="col-xs-12">
                <header class="header">
                    <h1><?php the_title(); ?></h1>
                </header>
            </div> -->
            <div class="col-left col-xs-12 col-md-5">
                <div class="content editor">
                <?php if(get_field('text1')) : ?>
                        <div class="text text-1">
                            <?php echo get_field('text1'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if(get_field('text2')) : ?>
                        <div class="text text-2">
                            <?php echo get_field('text2'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if(get_field('formularz')) : ?>
            <div class="col-right col-xs-12 col-md-6 col-md-offset-1">
                <?= do_shortcode(get_field('formularz')); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php 
endwhile;
get_footer();
?>