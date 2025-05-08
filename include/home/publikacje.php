<?php 
while(have_rows('nasze_publikacje','options')) : the_row();  
$btn = get_sub_field('button');
?>
<section class="section bg-gray text-blue" id="nasze-publikacje">
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
                while(have_rows('publikacje')) : the_row();  
                $publikacja = get_sub_field('publikacja');
                ?>
                <a href="<?= $publikacja['link']; ?>" title="<?= __('Czytaj więcej','migracja'); ?>" target="_blank" rel="nofollow" class="item">
                    <article>
                        <?php if(isset($publikacja['naglowek'])) : ?>
                        <header class="title">
                            <h3><?= $publikacja['naglowek']; ?></h3>
                        </header>
                        <?php endif; ?>
                    </article>
                </a>
                <?php endwhile; ?>
            </div>
            <?php if(isset($btn['link']) || isset($btn['tresc'])) : ?>
            <div class="line-btn">
                <a href="<?= $btn['link']; ?>" class="btn btn-blue-line" title="<?= $btn['tresc']; ?>">
                    <?= $btn['tresc']; ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
	</div>
</section>
<?php endwhile; ?>