<?php 
if(have_rows('slider','options')) :
	echo '<div id="slider">';
	echo '<div class="swiper">';
	echo '<div class="swiper-wrapper">';
	while(have_rows('slider','options')) : the_row();
		while(have_rows('slajd')) : the_row();
			$btn = get_sub_field('button');
			?>
				<div class="swiper-slide flex flex-align-center flex-height-full slide bg-gray">
					<div class="slide-col-left">
						<div class="wrapper wrapper-half">
							<?php if(get_sub_field('tekst')) : ?>
							<h2><?= get_sub_field('tekst'); ?></h2>
							<?php endif; ?>
							<?php if((isset($btn['link']) && !empty($btn['link'])) || (isset($btn['tresc']) && !empty($btn['tresc']))) : ?>
							<div class="slide-col-left-btn">
								<a href="<?= $btn['link']; ?>" class="btn btn-big btn-blue" title="<?= $btn['tresc']; ?>">
									<?= $btn['tresc']; ?>
								</a>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="slide-col-right">
						<?= wp_get_attachment_image(get_sub_field('grafika'), 'img_730_590_false'); ?>
					</div> 
				</div>
			<?php 
		endwhile; 
	endwhile; 
	echo '</div>';
	echo '</div>';
	echo '</div>';
endif;
?>