<?php 
while(have_rows('wez_udzial','options')) : the_row(); 
$btn = get_sub_field('button');
?>
<section class="section" id="wez-udzial">
	<div class="wrapper">
		<div class="flex flex-nowrap flex-align-center">
			<div class="col-left">
				<header class="section-title text-blue">
                    <?php if(get_sub_field('naglowek')) : ?>
					<h2 class="title"><?= get_sub_field('naglowek'); ?></h2>
                    <?php endif; ?>
				</header>
                <?php if(get_sub_field('tresc')) : ?>
				<div class="section-content">
				    <?= get_sub_field('tresc'); ?>				 
				</div>
                <?php endif; ?>
			</div>
            <?php if(isset($btn['link']) || isset($btn['tresc'])) { ?>
			<div class="col-right"> 
				<div class="line-btn">
					<a href="<?= $btn['link']; ?>" class="btn btn-blue" title="<?= $btn['tresc']; ?>">
						<?= $btn['tresc']; ?>
					</a>
                </div>
            </div>
            <?php } ?>
		</div>
	</div>
</section>
<?php endwhile; ?>