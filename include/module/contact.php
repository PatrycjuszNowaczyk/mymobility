<?php while(have_rows('kontakt','options')) : the_row(); ?>
<section class="section" id="kontakt">
	<div class="wrapper">
            <header class="section-title text-blue">
                <h2 class="claim">
                    <?= __('Kontakt','migracja'); ?>
                </h2>
            </header>
            <div class="section-content">
                <div class="col-left">
                    <?php 
                    while(have_rows('kontakty')) : the_row();  
                    $data = get_sub_field('dane');
                    ?>
                    <article class="item">
                        <?php if(isset($data['zdjecie']) && $data['zdjecie']) : ?>
                        <div class="img">
                            <?= wp_get_attachment_image($data['zdjecie'], 'img_120_120_true'); ?> 
                        </div>
                        <?php endif; ?>
                        <div class="text">
                            <?php if(isset($data['naglowek'])) : ?>
                            <h3><?= $data['naglowek']; ?></h3>
                            <?php endif; ?> 
                            <?php if(isset($data['tresc'])) : ?>
                            <div class="desc"><?= $data['tresc']; ?></div>
                            <?php endif; ?> 
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                <?php if(get_sub_field('dane_kontaktowe')) : ?>
                <div class="col-right">
                    <?= get_sub_field('dane_kontaktowe'); ?>
                </div>
                <?php endif; ?>
            </div>
	</div>
</section>
<?php endwhile; ?>