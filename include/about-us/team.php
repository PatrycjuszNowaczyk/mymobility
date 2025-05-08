<?php
global $o_nas;
$args = array(
    'post_type' => 'zespol',
    'posts_per_page' => -1,
);
$team = new WP_Query($args);
$nasz_zespol = $o_nas['nasz_zespol'];
if( isset($nasz_zespol['naglowek']) || isset($nasz_zespol['dodatkowy_naglowek']) || $team->have_posts() ) :
?>
<section class="bg-blue" id="nasz-zespol">
    <div class="wrapper">
        <div class="row">
            <div class="col-xs-12">
                <header class="section-title text-white">
                    <?php if(isset($nasz_zespol['naglowek'])) : ?>
                    <h1 class="<?php if(isset($nasz_zespol['dodatkowy_naglowek'])) { echo 'claim'; } else { echo 'title'; } ?>"><?= $nasz_zespol['naglowek']; ?></h1>
                    <?php endif; ?>
                    <?php if( $nasz_zespol['dodatkowy_naglowek'] ) : ?>
                    <span class="title"><?= $nasz_zespol['dodatkowy_naglowek']; ?></span>
                    <?php endif; ?>
                </header>

                <?php if( isset($nasz_zespol['opis']) ) : ?>
                <div class="content editor text-white">
                    <?= $nasz_zespol['opis']; ?>
                </div>
                <?php endif; ?>

                <?php 
                if($team->have_posts()) :
                echo '<div class="items">';
                while($team->have_posts()) : $team->the_post();
                $osoba = get_field('osoba');
                global $post;
                ?>
                    <article class="item" id="<?= $post->post_name; ?>">
                        <?php if(isset($osoba['zdjecie']) && $osoba['zdjecie']) : ?>
                        <div class="img">
                            <?= wp_get_attachment_image($osoba['zdjecie'], 'img_340_340_true'); ?> 
                        </div>
                        <?php endif; ?>
                        <div class="text">
                            <header class="title">
                                <h3><?php the_title() ?></h3>
                            </header>
                            <?php if(isset($osoba['stanowisko'])) : ?>
                            <span class="position"><?= $osoba['stanowisko']; ?></span>
                            <?php endif; ?> 
                            <?php if(isset($osoba['opis'])) : ?>
                            <div class="desc"><?= $osoba['opis']; ?></div>
                            <?php endif; ?> 

                            <?php if(isset($osoba['strona_www']) && $osoba['strona_www']) : ?>
                            <div class="line-btn">
                                <a href="<?= $osoba['strona_www']; ?>" class="link" title="<?= __('Strona WWW','migracja'); ?>" target="_blank" rel="nofollow">
                                    <span><?= __('Strona WWW','migracja'); ?></span>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php 
                endwhile; 
                wp_reset_query();
                echo '</div>'; 
                endif; ?>       

            </div>
        </div>
    </div>
</section>
<?php endif; ?>