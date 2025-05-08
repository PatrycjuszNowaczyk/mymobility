<?php 
if(have_rows('skroty','options')) : 
$strona = get_sub_field('strona');
?>
<section class="section bg-gray" id="skroty-do-stron">
	<div class="wrapper">
        <div class="grid grid-column-2">
        <?php 
        while(have_rows('skroty','options')) : the_row(); 
        $strona = get_sub_field('strona');
        ?>
        <article class="item">
            <?php if(isset($strona['naglowek']) || isset($strona['claim'])) : ?>
            <header class="title">
                <?php if(isset($strona['claim'])) : ?>
                <span class="claim"><?= $strona['claim']; ?></span>
                <?php endif; ?>
                <?php if($strona['naglowek']) : ?>
                <h3><?= $strona['naglowek']; ?></h3>
                <?php endif; ?>
            </header>
            <?php endif; ?>
            <?php if(isset($strona['wstep'])) : ?>
            <div class="content">
                <?= $strona['wstep']; ?>
            </div>
            <?php endif; ?>
            <?php if(isset($strona['link'])) : ?>
            <div class="line-btn">
                <a href="<?= $strona['link']; ?>" class="btn btn-blue-line" title="<?= __('Czytaj więcej','migracja'); ?>">
                    <?= __('Czytaj więcej','migracja'); ?>
                </a>
            </div>
            <?php endif; ?>
        </article>
        <?php endwhile; ?>
        </div>
	</div>
</section>
<?php endif; ?>