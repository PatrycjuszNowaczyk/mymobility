<?php 
while(have_rows('wspolpraca','options')) : the_row(); 
$btn = get_sub_field('button');
?>
<section class="section" id="wspolpraca">
	<div class="wrapper">
		<div class="flex">
			<div class="col-left">
				<header class="section-title text-blue">
                    <?php if(get_sub_field('claim')) : ?>
					<span class="claim">
                        <?= get_sub_field('claim'); ?>
					</span>
                    <?php endif; ?>
                    <?php if(get_sub_field('naglowek')) : ?>
					<h2 class="title"><?= get_sub_field('naglowek'); ?></h2>
                    <?php endif; ?>
				</header>
                <?php if(get_sub_field('tresc')) : ?>
				<div class="section-content">
				    <?= get_sub_field('tresc'); ?>				 
				</div>
                <?php endif; ?>
                <?php if(isset($btn['link']) || isset($btn['tresc'])) : ?>
				<div class="line-btn">
					<a href="<?= $btn['link']; ?>" class="btn btn-blue-line" title="<?= $btn['tresc']; ?>">
						<?= $btn['tresc']; ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
            <?php if(get_sub_field('zdjecie')) { ?>
			<div class="col-right"> 
				<?= wp_get_attachment_image(get_sub_field('zdjecie'), 'img_500_325_true'); ?> 
			</div>
            <?php } ?>
		</div>
	</div>
</section>
<?php endwhile; ?>