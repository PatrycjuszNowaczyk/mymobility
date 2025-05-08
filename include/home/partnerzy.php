<?php
$is_template = is_page_template( 'template/page-partnerzy.php' );
?>

<section
  class="<?= ( $is_template ? 'section bg-blue' : '' ) ?>" id="partnerzy"
>
  <div class="wrapper">
    <?php
    if ( have_rows( 'partnerzy', 'options' ) ) :
      while ( have_rows( 'partnerzy', 'options' ) ) : the_row();
        $heading          = get_sub_field( 'naglowek' );
        $link_to_partners = get_sub_field( 'link_do_partnerow' );
        $text_to_partners = get_sub_field( 'tekst_do_partnerow' );
        $partners_acf     = get_sub_field( 'partnerzy' );
        $partners_acf     = array_filter( $partners_acf, function ( $item ) {
          if ( !isset( $item['partner']['czy_jest_widoczny'] ) ) {
            return true;
          }

          return !empty( $item['partner']['czy_jest_widoczny'] );
        } );
        $partners_to_show = (
        function () use (
          $partners_acf, $link_to_partners, $text_to_partners
        ) {
          if ( false === is_home() ) {
            return $partners_acf;
          }

          $partners_count       = count( $partners_acf );
          $partners_per_row     = 4;
          $partners_max_rows    = 3;
          $partners_max_to_show = $partners_per_row * $partners_max_rows;
          $length               = $partners_max_to_show;

          if ( $partners_count > $partners_max_to_show ) {
            $partners                  = [];
            $length                    -= 1;
            $partners                  = array_slice( $partners_acf, 0, $length );
            $additional_partners_count = $partners_count - $length;

            $partners = array_slice( $partners_acf, 0, $length );

            $partners[] = [
              'partner' => [
                'is_last' => true,
                'text'    => sprintf(
                  __(
                    !empty( $text_to_partners ) ? $text_to_partners :
                      <<<TEXT
                      And %d additional
                      associated partners,
                      including municipalities,
                      chambers of commerce,
                      and organizations in AI, IT,
                      education, and research.
                      TEXT,
                    'migracja'
                  ),
                  $additional_partners_count
                ),
                'link'    => !empty( $link_to_partners ) ? $link_to_partners : null,
              ]
            ];

            return $partners;
          }

          return $partners_acf;
        } )();

        ?>
        <div class="section-partners">
          <header
            class="section-title<?= ( $is_template ? ' text-white' : ' text-blue' ) ?>"
          >
            <?php if ( $heading ) : ?>
              <h2 class="claim">
                <?= $heading ?>
              </h2>
            <?php endif; ?>
          </header>
          <div class="section-content">
            <div class="grid grid-column-sm-2 grid-column-md-3 grid-column-lg-4 grid-column-gap-10 grid-row-equal grid-row-gap-10">
              <?php
              foreach ( $partners_to_show as $index => $item ) :
                $partner = $item['partner'];
                ?>
                <a
                  <?= ( !empty( $partner['link'] ) ? 'href="' . $partner['link'] . '"' : '' ) ?>
                  title="<?= __( 'Zobacz', 'migracja' ); ?>"
                  <?= empty( $partner['is_last'] ) ? 'target="_blank"' : '' ?>
                  rel="noreferrer nofollow noopener"
                  class="item"
                >
                  <article>
                    <?php if ( !empty( $partner['naglowek'] ) ) : ?>
                      <h3><?= $partner['naglowek']; ?></h3>
                    <?php endif; ?>

                    <?php if ( isset( $partner['logo'] ) && !empty( $partner['logo'] ) ) : ?>
                      <div class="img">
                        <?= wp_get_attachment_image( $partner['logo'], 'img_210_90_false' ); ?>
                      </div>
                    <?php endif; ?>

                    <?php if ( isset( $partner['opis'] ) && !empty( $partner['opis'] ) ) : ?>
                      <div class="text"><?= $partner['opis']; ?></div>
                    <?php endif; ?>

                    <?php if ( isset( $partner['is_last'] ) && !empty( $partner['is_last'] ) ) : ?>
                      <div class="additional_text">
                        <?= $partner['text'] ?>
                      </div>
                    <?php endif; ?>
                  </article>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php
      endwhile;
    endif;
    ?>
    <?php
    while ( have_rows( 'ambasadorzy', 'options' ) ) : the_row();
      $title = get_sub_field( 'naglowek' );
      if ( have_rows( 'ambasadorzy' ) ) :
        ?>
        <div class="section-ambasadors">
          <header
            class="section-title<?php if ( !is_page_template( 'template/page-partnerzy.php' ) ) {
              echo " text-white";
            } else {
              echo " text-blue";
            } ?>"
          >
            <?php if ( isset( $title ) ) : ?>
              <h2 class="claim">
                <?= $title; ?>
              </h2>
            <?php endif; ?>
          </header>
          <div class="section-content">
            <div class="swiper">
              <div class="swiper-wrapper">
                <?php
                while ( have_rows( 'ambasadorzy' ) ) : the_row();
                  $partner = get_sub_field( 'partner' );
                  ?>
                  <div class="swiper-slide">
                    <?php if ( isset( $partner['link'] ) ) { ?>
                    <a
                      href="<?= $partner['link']; ?>"
                      title="<?= __( 'Zobacz', 'migracja' ); ?>"
                      target="_blank"
                      rel="nofollow"
                      class="item"
                    >
                      <?php } ?>
                      <article<?php if ( !isset( $partner['link'] ) ) {
                        echo ' class="item"';
                      } ?>>
                        <?php if ( isset( $partner['naglowek'] ) ) : ?>
                          <h3><?= $partner['naglowek']; ?></h3>
                        <?php endif; ?>
                        <?php if ( isset( $partner['logo'] ) ) : ?>
                          <div class="img">
                            <?= wp_get_attachment_image( $partner['logo'], 'img_210_90_false' ); ?>
                          </div>
                        <?php endif; ?>
                        <?php if ( isset( $partner['opis'] ) ) : ?>
                          <div class="text"><?= $partner['opis']; ?></div>
                        <?php endif; ?>
                      </article>
                      <?php if ( isset( $partner['link'] ) ) { ?>
                    </a>
                  <?php } ?>
                  </div>
                <?php endwhile; ?>
              </div>
              <span class="swiper-next"></span>
              <span class="swiper-prev"></span>
            </div>
          </div>
        </div>
      <?php
      endif;
    endwhile;
    ?>
  </div>
</section>
