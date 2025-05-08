<?php
global $o_nas;
$o_projekcie = $o_nas['o_projekcie'];
if( isset($o_projekcie['naglowek']) || isset($o_projekcie['dodatkowy_naglowek']) || isset($o_projekcie['edytor']) ) :
?>
<section id="o-projekcie-informacje">
    <div class="wrapper">
        <div class="row">
            <div class="col-lg-8 col-md-10 col-xs-12">
                <header class="section-title text-blue">
                    <?php if(isset($o_projekcie['naglowek'])) : ?>
                    <h1 class="<?php if(isset($o_projekcie['dodatkowy_naglowek'])) { echo 'claim'; } else { echo 'title'; } ?>"><?= $o_projekcie['naglowek']; ?></h1>
                    <?php endif; ?>
                    <?php if( $o_projekcie['dodatkowy_naglowek'] ) : ?>
                    <span class="title"><?= $o_projekcie['dodatkowy_naglowek']; ?></span>
                    <?php endif; ?>
                </header>

                <?php 
                while(have_rows('o_nas')) : the_row();  
                while(have_rows('o_projekcie')) : the_row();  
                    if(have_rows('dane_informacyjne')) :
                        echo '<ul class="items">';
                        while(have_rows('dane_informacyjne')) : the_row();
                        $box = get_sub_field('box');
                        ?>
                        <li>
                            <?php if( isset($box['naglowek']) ) : ?>
                            <strong class="title"><?= $box['naglowek']; ?></strong>
                            <?php endif; ?>
                            <?php if( isset($box['opis']) ) : ?>
                            <div class="desc"><?= $box['opis']; ?></div>
                            <?php endif; ?>
                        </li>
                        <?php
                        endwhile; 
                        echo '</ul>';
                    endif; 
                endwhile; 
                endwhile; 
                ?>                

                <?php if( isset($o_projekcie['edytor']) ) : ?>
                <div class="content editor">
                    <?= $o_projekcie['edytor']; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>