<?php

/**
 * The main footer-template
 *
 * @package Nattours
 */

$someOptions = get_option( 'nattours_some_options_' . \UTILS()->get_city() );

if ( \UTILS()->get_city() === 'helsinki' ) {
	echo '<div class="helsinki-pulse"></div>';
}
?>
<footer<?php echo is_front_page() ? ' class="front-page-footer"' : '' ?>>

	<?php
	if ( \UTILS()->get_city() !== '' ) {
		?>
        <div class="container">
            <div class="row">
                <div class="footer-logo-column">
                    <img class="footer-logo-img"
                         src="<?php echo \UTILS()->get_image_uri() . '/' . \UTILS()->get_city() . '_logo.png' ?>"
                         alt="<?php echo \UTILS()->get_city() . ' logo' ?>"/>
                </div>
            </div>
        </div>
		<?php
	}
	?>
	<?php if ( ! is_front_page() ): ?>
        <div class="container">
            <div class="row">
                <div class="footer-menu-column">
                    <ul class="footer-links-menu">
						<?php nattours_footer_menu() ?>
						<?php nattours_footer_menu_right() ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer__bottom-wrapper">
            <div class="container">
                <div class="row">
                    <div class="footer-menu-column">
                        <ul class="footer-bottom-menu">
                            <li>
								<?php \UTILS()->get_social_media_links( $someOptions ) ?>
                            </li>
                            <li class="bottom-logos">
                                <div class="logos-wrapper logos-wrapper--footer">
                                    <img src="<?php echo \UTILS()->get_image_uri() . '/interreg_logo.jpg' ?>"
                                         alt="Interreg logo"/>
                                    <img src="<?php echo \UTILS()->get_image_uri() . '/eu_logo.jpg' ?>" alt="EU logo"/>
                                </div>
                            </li>
							<?php nattours_footer_menu_bottom() ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
	<?php else: ?>
        <div class="container">
            <div class="row">
                <div class="footer-text-column">
					<?php pll_e( 'Front page footer text' ); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
</footer>
<?php wp_footer(); ?>
<!-- <script src="http://code.responsivevoice.org/responsivevoice.js"></script> -->
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css'
      rel='stylesheet'/>
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.62.0/dist/L.Control.Locate.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.62.0/dist/L.Control.Locate.min.css">
<!-- Matomo -->
<script type="text/javascript">
  var _paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(["setCookieDomain", "*.citynature.eu"]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//analytics.hel.ninja/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '26']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->
</body>
</html>

