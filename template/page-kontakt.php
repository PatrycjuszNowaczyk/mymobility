<?php
/* Template name: Kontakt */
get_header(); 
while ( have_posts()) : the_post();
?>
<section class="section" id="page-kontakt">
    <div class="wrapper">
        <div class="row">
            <?php while(have_rows('kontakt','options')) : the_row(); ?>
            <div class="col-md-6 col-xs-12 col-left">
                <header class="section-title text-blue">
                    <h1 class="claim">
                        <?= __('Kontakt','migracja'); ?>
                    </h1>
                </header>
                <div class="section-content text-blue">
                    <?php if(get_sub_field('dane_kontaktowe')) : ?>
                    <div class="data-contact">
                        <?= get_sub_field('dane_kontaktowe'); ?>
                    </div>
                    <?php endif; ?>
                    <?php 
                    while(have_rows('kontakty')) : the_row();  
                        get_template_part('include/contact/item');
                    endwhile; 
                    ?>
                </div>
            </div>
            <?php endwhile; ?>
            <?php if(get_field('formularz')) : ?>
            <div class="col-md-6 col-xs-12 col-right">
                <header class="section-title text-blue">
                    <h2 class="claim">
                        <?= __('Napisz do nas','migracja'); ?>
                    </h2>
                </header>
                <div class="section-content">
                <?= do_shortcode(get_field('formularz')); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php 
endwhile;
get_template_part('include/module/about-project');
get_template_part('include/module/wez-udzial');
get_footer();
?>