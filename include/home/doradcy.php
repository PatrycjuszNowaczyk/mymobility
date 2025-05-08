<?php
$advisors       = get_field( 'doradcy', 'options' );
$heading        = $advisors['naglowek'];
$advisors_array = $advisors['doradca'];
?>

<section
  id="advisors"
  class="section bg-blue"
>
  <div class="wrapper">
    <header class="text-white advisors__heading">
      <h1><?= $heading ?></h1>
    </header>

    <div class="grid grid-column-sm-3 grid-column-gap-20 grid-row-gap-20">
      <?php foreach ( $advisors_array as $advisor ) : ?>
        <address class="advisor">
          <p class="advisor__name">
            <?= $advisor['imie'] ?>
          </p>
          <p class="advisor__role">
            <?= $advisor['rola'] ?>
          </p>
          <a href="tel:<?= $advisor['telefon'] ?>" class="advisor__phone">
            <?= $advisor['telefon'] ?>
          </a>
          <a href="mailto:<?= $advisor['email'] ?>" class="advisor__email">
            <?= $advisor['email'] ?>
          </a>
        </address>
      <?php endforeach ?>
    </div>
  </div>
</section>
