<?php 
$args = array(
    'post_type' => 'zespol',
    'posts_per_page' => -1,
);
$team = new WP_Query($args);
while(have_rows('zespol','options')) : the_row();  
$btn = get_sub_field('button');
?>
<section class="section bg-blue text-white" id="zespol">
	<div class="wrapper">
        <header class="section-title">
            <?php if(get_sub_field('claim')) : ?>
            <span class="claim"><?= get_sub_field('claim'); ?></span>
            <?php endif; ?>
            <?php if(get_sub_field('naglowek')) : ?>
            <h2 class="title">
                <?= get_sub_field('naglowek'); ?>
            </h2>
            <?php endif; ?>
        </header>
        <div class="section-content">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php 
                    while($team->have_posts()) : $team->the_post();
                    $osoba = get_field('osoba');
                    ?>
                    <div class="swiper-slide">
                        <article class="item">
                            <div class="img">
                                <?= wp_get_attachment_image($osoba['zdjecie'], 'img_340_340_true'); ?> 
                            </div>
                            <header class="title">
                                <h3><?php the_title() ?></h3> 
                                <?php if(isset($osoba['stanowisko'])) : ?>
                                <span class="position"><?= $osoba['stanowisko']; ?></span>
                                <?php endif; ?> 
                            </header>
                        </article>
                    </div>
                    <?php endwhile; wp_reset_query(); ?>
                </div>
                <span class="swiper-next"></span>
                <span class="swiper-prev"></span>
            </div>
            <?php if(isset($btn['link']) || isset($btn['tresc'])) : ?>
            <div class="line-btn">
                <a href="<?= $btn['link']; ?>" class="btn btn-white-line" title="<?= $btn['tresc']; ?>">
                    <?= $btn['tresc']; ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
	</div>
</section>
<?php endwhile; ?>