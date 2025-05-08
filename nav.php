<header class="main">
  <div class="row middle-xs flex-justify-between">
    <div class="col-logo">
      <a href="<?= get_home_url(); ?>" title="<?php wp_title(); ?>">
        <?php if ( ICL_LANGUAGE_CODE == 'pl' ) { ?>
          <img src="<?= get_stylesheet_directory_uri() ?>/img/logo-moja-migracja.svg" alt="<?php wp_title(); ?>">
        <?php } elseif ( ICL_LANGUAGE_CODE == 'en' ) { ?>
          <img src="<?= get_stylesheet_directory_uri() ?>/img/logo-moja-migracja-en.svg" alt="<?php wp_title(); ?>">
        <?php } else { ?>
          <img src="<?= get_stylesheet_directory_uri() ?>/img/logo-moja-migracja-uk.svg" alt="<?php wp_title(); ?>">
        <?php } ?>
      </a>

      <a href="https://www.euonair.eu/" title="EUonAIR" rel="nofollow noreferrer noopener" target="_blank">
        <?php if ( ICL_LANGUAGE_CODE == 'pl' ) { ?>
          <img src="<?= get_stylesheet_directory_uri() ?>/img/logo-euonair-pl.svg" alt="<?php wp_title(); ?>">
        <?php } elseif ( ICL_LANGUAGE_CODE == 'en' ) { ?>
          <img src="<?= get_stylesheet_directory_uri() ?>/img/logo-euonair-en.svg" alt="<?php wp_title(); ?>">
        <?php } else { ?>
          <img src="<?= get_stylesheet_directory_uri() ?>/img/logo-euonair-uk.svg" alt="<?php wp_title(); ?>">
        <?php } ?>
      </a>
    </div>
    <div class="col-right">
      <div class="nav-line">
        <nav class="main">
          <?php wp_nav_menu( array(
            'theme_location' => 'main-menu',
            'link_before'    => '<span>',
            'link_after'     => '</span>'
          ) ); ?>
          <a class="menu-link" href="#mobile-menu" rel="nofollow"><span></span></a>
        </nav>
        <div class="lang">
          <?php wp_nav_menu( array(
            'theme_location' => 'lang-menu',
            'link_before'    => '<span>',
            'link_after'     => '</span>'
          ) ); ?>
        </div>
      </div>
    </div>
  </div>
</header>