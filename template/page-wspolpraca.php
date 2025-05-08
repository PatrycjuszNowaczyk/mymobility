<?php
/* Template name: Współpraca */
get_header(); 
while ( have_posts()) : the_post();
$wspolpraca = get_field('wspolpraca');
$praktyki = get_field('praktyki');
?>
<main class="section" id="page-wspolpraca">
    <div class="wrapper">
        <div class="row" id="section-wspolpraca">
            <div class="col-lg-8 col-md-10 col-xs-12">
                <header class="section-title text-blue">
                    <h1 class="<?php if(get_field('dodatkowy_naglowek')) { echo 'claim'; } else { echo 'title'; } ?>"><?php the_title(); ?></h1>
                    <?php if(get_field('dodatkowy_naglowek')) : ?>
                    <span class="title"><?= get_field('dodatkowy_naglowek'); ?></span>
                    <?php endif; ?>
                </header>
                <?php if($wspolpraca['tresc']) : ?>
                <div class="content editor">
	                <?= $wspolpraca['tresc']; ?>
                </div>
                <?php endif; ?>


                <?php 
                while(have_rows('wspolpraca')) : the_row();  
                    if(have_rows('kontakty')) :
                        echo '<div class="items">';
                        while(have_rows('kontakty')) : the_row();  
                            get_template_part('include/contact/item');
                        endwhile; 
                        echo '</div>';
                    endif; 
                endwhile; 
                ?>     
            </div>
        </div>
        <div class="row" id="section-praktyki">
            <div class="col-lg-8 col-md-10 col-xs-12">
                <header class="section-title text-blue">
                    <h1 class="<?php if($praktyki['dodatkowy_naglowek']) { echo 'claim'; } else { echo 'title'; } ?>"><?= $praktyki['naglowek'] ?></h1>
                    <?php if($praktyki['dodatkowy_naglowek']) : ?>
                    <span class="title"><?= $praktyki['dodatkowy_naglowek']; ?></span>
                    <?php endif; ?>
                </header>
                <?php if($praktyki['tresc']) : ?>
                <div class="content editor">
	                <?= $praktyki['tresc']; ?>
                </div>
                <?php endif; ?>

                <?php 
                while(have_rows('praktyki')) : the_row();  
                    if(have_rows('kontakty')) :
                        echo '<div class="items">';
                        while(have_rows('kontakty')) : the_row();  
                            get_template_part('include/contact/item');
                        endwhile; 
                        echo '</div>';
                    endif; 
                endwhile; 
                ?>                
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