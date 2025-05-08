<?php 
while(have_rows('inne_projekty','options')) : the_row();  
?>
<section class="section bg-gray text-blue" id="inne-projekty">
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
            <div class="grid grid-column-2 grid-column-gap-20 grid-row-gap-20">
                <?php 
                while(have_rows('projekty')) : the_row();  
                $projekt = get_sub_field('projekt');
                ?>
                <a href="<?= $projekt['link']; ?>" title="<?= __('Zobacz','migracja'); ?>" target="_blank" rel="nofollow" class="item">
                    <article>
                        <?php if(isset($projekt['zdjecie'])) : ?>
                        <div class="img">
                            <?= wp_get_attachment_image($projekt['zdjecie'], 'img_500_260_true'); ?> 
                        </div>
                        <?php endif; ?>
                        <div class="content">
                            <header class="title">
                                <?php if(isset($projekt['naglowek'])) : ?>
                                <h3><?= $projekt['naglowek']; ?></h3>
                                <?php endif; ?> 
                            </header>
                            <?php if(isset($projekt['opis'])) : ?>
                            <div class="text"><?= $projekt['opis']; ?></div>
                            <?php endif; ?> 
                            <?php if(isset($projekt['link'])) : ?>
                            <div class="link">
                                <span>
                                    <?= __('Zobacz','migracja'); ?>
                                </span>
                            </div>
                            <?php endif; ?> 
                        </div>
                    </article>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
	</div>
</section>
<?php endwhile; ?>