<?php
while ( have_rows( 'o_badaniu', 'options' ) ) : the_row();
  $btn = get_sub_field( 'button' );
  ?>
  <section class="section bg-blue text-white" id="o-badaniu">
    <div class="wrapper">
      <div class="flex">
        <div class="col-left">
          <header class="section-title">
            <?php if ( get_sub_field( 'naglowek' ) ) : ?>
              <h2 class="claim"><?= get_sub_field( 'naglowek' ); ?></h2>
            <?php endif; ?>
            <?php if ( get_sub_field( 'podtytul' ) ) : ?>
              <span class="title">
                        <?= get_sub_field( 'podtytul' ); ?>
					</span>
            <?php endif; ?>
          </header>
          <?php if ( get_sub_field( 'opis' ) ) : ?>
            <div class="section-content">
              <?= get_sub_field( 'opis' ); ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="col-right">
          <?= wp_get_attachment_image( get_sub_field( 'schemat' ), 'img_500' ); ?>
          <?php if (
            isset( $btn['link'] ) && false === empty( $btn['link'] )
          ) : ?>
            <div class="line-btn">
              <a href="<?= $btn['link']; ?>" class="btn btn-white-line" title="<?= $btn['tresc']; ?>">
                <?= $btn['tresc']; ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php endwhile; ?>