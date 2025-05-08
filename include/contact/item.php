<?php
$data = get_sub_field('dane');
?>
<article class="item">
    <?php if(isset($data['zdjecie'])) : ?>
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