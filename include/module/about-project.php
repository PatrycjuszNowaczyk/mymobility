<?php
$oprojekcie = get_field('o_projekcie','options');
if(isset($oprojekcie['logo']) && $oprojekcie['tekst']) :
?>
<section id="o-projekcie">
    <div class="wrapper">
        <div class="row">
            <?php if(isset($oprojekcie['logo'])) { ?>
            <div class="col-xs-12 col-sm-5 col-left">
                <?= wp_get_attachment_image($oprojekcie['logo'], 'img'); ?>
            </div>
            <?php } ?>
            <?php if(isset($oprojekcie['tekst'])) { ?>
            <div class="col-xs-12 col-sm-6 col-sm-offset-1 col-right">
                <?= $oprojekcie['tekst']; ?>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php endif; ?>