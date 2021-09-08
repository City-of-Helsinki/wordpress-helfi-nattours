<div class="header__nav">
    <div class="header__nav__sidemenu">
    <span id="leftOpen">
      <i class="fa fa-bars" aria-hidden="true"></i>
      <span class="hidden-xs">&emsp; <?= pll__( 'Menu' ); ?></span>
    </span>
    </div>
    <a href="<?php echo UTILS()->get_home_link( UTILS()->get_city_term_id() ) ?>" class="header__nav__link">
        <span>citynature.eu</span>
    </a>
    <span class="header__nav__map">
        <?php if ( ! is_tax() ): ?>
            <span id="rightOpen">
              <span class="hidden-xs">
                  <?php
                  echo ( get_post_type() === 'location' || get_post_type() === 'route' || get_post_type() === 'service' ) ? pll__( 'Location on map' ) : pll__( 'Locations on map' );
                  ?>
              </span>
              <i class="fa fa-map-o" aria-hidden="true"></i>
            </span>
        <?php endif; ?>
  </span>
</div>
