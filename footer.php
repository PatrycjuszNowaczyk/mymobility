<?php
$footer = get_field( 'stopka', 'options' );
?>
<footer class="main">
  <div class="wrapper">
    <div class="row">

      <div class="col-left col-12 col-xs-6 col-lg-4">
        <?php if ( isset( $footer['adres'] ) && !empty( $footer['adres'] ) ) { ?>
          <address class="text">
            <?= $footer['adres']; ?>
          </address>
        <?php } ?>
      </div>

      <div class="col-left col-12 col-xs-6 col-lg-4 flex flex-justify-lg-center">
        <nav class="menu">
          <?php wp_nav_menu( array(
            'theme_location' => 'footer-menu',
            'link_before'    => '<span>',
            'link_after'     => '</span>'
          ) ); ?>
        </nav>
      </div>

      <div class="col-social col-xs-12 col-lg-4 flex flex-align-start flex-wrap">
        <article class="description">
          <?php if ( isset( $footer['opis'] ) && !empty( $footer['opis'] ) ) {
            echo $footer['opis'];
          } ?>
        </article>
        <div class="social">
          <strong><?= __( 'Śledź nas na:', 'migracja' ); ?></strong>
          <?php wp_nav_menu( array(
            'theme_location' => 'social-menu',
            'link_before'    => '<span>',
            'link_after'     => '</span>'
          ) ); ?>
        </div>
      </div>

    </div>
  </div>
</footer>
<?php if ( is_home() ) {
  get_template_part( 'include/module/about-project' );
}
?>
<footer class="author">
  <div class="wrapper">
    <div class="row middle-md">
      <div class="col-xs-6 col-left">
        Created by
        <a href="https://brandma.pl" title="BRANDMA" target="_blank" rel="noopener noreferrer nofollow">
          <strong>BRANDMA™</strong></a>
      </div>
    </div>
  </div>
</footer>
</div>
<?php
if ( !is_admin() ) {
  wp_footer();
}
?>
</div>
</body>
</html>