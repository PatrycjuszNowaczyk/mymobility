<?php
global $o_nas;
$o_crash = $o_nas['o_crash'];
if( isset($o_crash['naglowek']) || isset($o_crash['dodatkowy_naglowek']) || isset($o_crash['edytor']) ) :
?>
<section id="o-crash">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-8 col-md-10 col-xs-12">
                <header class="section-title text-blue">
                    <?php if(isset($o_crash['naglowek'])) : ?>
                    <h1 class="<?php if(isset($o_crash['dodatkowy_naglowek'])) { echo 'claim'; } else { echo 'title'; } ?>"><?= $o_crash['naglowek']; ?></h1>
                    <?php endif; ?>
                    <?php if( $o_crash['dodatkowy_naglowek'] ) : ?>
                    <span class="title"><?= $o_crash['dodatkowy_naglowek']; ?></span>
                    <?php endif; ?>
                </header>
                <?php if( isset($o_crash['edytor']) ) : ?>
                <div class="content editor">
                    <?= $o_crash['edytor']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>