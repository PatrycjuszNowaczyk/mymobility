<?php
/* Template name: Badanie */
get_header(); 
while ( have_posts()) : the_post();
$badanie = get_field('badania');
$przed_badaniem = $badanie['przed_badaniem'];
?>
<section class="section" id="page-badanie">
    <div class="wrapper">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-lg-7 col-left">
                <?php if(isset($przed_badaniem['naglowek'])) : ?>
                <header class="header">
                    <h1><?= $przed_badaniem['naglowek']; ?></h1>
                </header>
                <?php endif; ?>
                <div class="content">
                        <?php if(isset($przed_badaniem['opis'])) : ?>
                        <div class="desc">
                            <?= $przed_badaniem['opis']; ?>
                        </div>
                        <?php endif; ?>
                        <?php while(have_rows('badania')) : the_row(); ?>
                        <?php while(have_rows('przed_badaniem')) : the_row(); ?>
                            <?php if(have_rows('harmonijka')) : ?>
                            <div class="accordions">
                                <?php 
                                while(have_rows('harmonijka')) : the_row(); 
                                    $accordion = get_sub_field('pozycja');
                                    if(isset($accordion['naglowek'])) : 
                                    ?> 
                                    <article class="accordions-item">
                                        <header>
                                            <h3><?= $accordion['naglowek']; ?></h3>
                                        </header>
                                        <?php if(isset($accordion['tresc'])) : ?>
                                        <div><?= $accordion['tresc']; ?></div>
                                        <?php endif; ?>
                                    </article>
                                    <?php 
                                    endif;
                                endwhile; 
                                ?>
                            </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                        <?php endwhile; ?>
                        <h4><?= $przed_badaniem['dodatkowy_naglowek']; ?></h4>
                        <p class="start">
                            <a href="#" class="start-link"><span><?= __('Start','migracja'); ?></span></a>
                        </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 col-lg-5 col-right">
                <form action="#" method="POST" id="form-code">
                    <label for="badanie_code"><?= __('Jeśli posiadasz już kod, wpisz go poniżej, aby kontynuować badanie','migracja'); ?>:</label>
                    <input type="text" name="badanie_code" id="badanie_code" placeholder="<?= __('Wpisz kod','migracja'); ?>">
                    <button class="btn btn-green"><?= __('Kontynuuj badanie','migracja'); ?></button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="steps-container" id="badanie-formularz">
                    <?= get_template_part('include/badanie/nav'); ?> 
                    <div class="steps-content">
                        <?= get_template_part('include/badanie/start'); ?> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php 
endwhile;
get_template_part('include/module/about-project');
get_footer();
?>