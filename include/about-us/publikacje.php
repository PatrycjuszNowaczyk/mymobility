<?php
global $o_nas;
$publikacje = $o_nas['nasze_publikacje'];
if( isset($publikacje['naglowek']) || isset($publikacje['dodatkowy_naglowek']) ) :
?>
<section id="publikacje">
    <div class="wrapper">
        <div class="row">
            <div class="col-xs-12">
                <header class="section-title text-blue">
                    <?php if(isset($publikacje['naglowek'])) : ?>
                    <h1 class="<?php if(isset($publikacje['dodatkowy_naglowek'])) { echo 'claim'; } else { echo 'title'; } ?>"><?= $publikacje['naglowek']; ?></h1>
                    <?php endif; ?>
                    <?php if( $publikacje['dodatkowy_naglowek'] ) : ?>
                    <span class="title"><?= $publikacje['dodatkowy_naglowek']; ?></span>
                    <?php endif; ?>
                </header>

                <?php 
                while(have_rows('o_nas')) : the_row();  
                while(have_rows('nasze_publikacje')) : the_row();  
                    if(have_rows('publikacje')) :
                        echo '<div class="items">';
                        while(have_rows('publikacje')) : the_row();
                        $publikacja = get_sub_field('publikacja');
                        ?>
                        <article class="item"<?php if( isset($publikacja['hashtag']) ) { echo ' id="'. $publikacja['hashtag'] . '"'; } ?>>
                            <?php if( isset($publikacja['naglowek']) ) : ?>
                            <header>
                                <h3 class="title"><?= $publikacja['naglowek']; ?></h3>
                            </header>
                            <?php endif; ?>
                            <?php if( isset($publikacja['wstep']) ) : ?>
                            <div class="less editor"><?= $publikacja['wstep']; ?></div>
                            <?php endif; ?>
                            <?php if( isset($publikacja['rozwiniecie']) ) : ?>
                            <div class="more editor"><?= $publikacja['rozwiniecie']; ?></div>
                            <div class="line-btn">
                                <a href="#" title="<?= __('Pokaż więcej','migracja'); ?>" class="btn btn-blue-line link-more">
                                    <?= __('Pokaż więcej','migracja'); ?>
                                </a>
                                <a href="#" title="<?= __('Pokaż mniej','migracja'); ?>" class="btn btn-blue-line link-less">
                                    <?= __('Pokaż mniej','migracja'); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </article>
                        <?php
                        endwhile; 
                        echo '</div>';
                    endif; 
                endwhile; 
                endwhile; 
                ?>                

            </div>
        </div>
    </div>
</section>
<?php endif; ?>