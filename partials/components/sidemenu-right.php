<?php $map_file = get_field( 'map_file' ); ?>

<?php
$tile_url = UTILS()->get_map_tileurl();
?>
<section class="sidemenu<?php echo ( is_user_logged_in() ) ? ' sidemenu--logged-in' : ''; ?> sidemenu--right"
         id="rightMenu">
    <div class="sidemenu__header-container">
        <div class="sidemenu__header">
            <span> <?= pll__( 'Map' ) ?> </span>
            <i class="fa fa-times close-modal" id="rightClose" aria-hidden="false"></i>
        </div>
    </div>
    <div class="graphic-content">
		<?php
		$map     = "[leaflet-map scrollwheel=1 fit_markers=1 tileurl=$tile_url][leaflet-geojson src=$map_file]";
		$map_arr = [];
		array_push( $map_arr, $map );
		if ( have_rows( 'markers' ) ): while ( have_rows( 'markers' ) ): the_row();
			$location = get_sub_field( 'location', get_option( 'page_on_front' ) );
			$lat      = $location['lat'];
			$lng      = $location['lng'];
			$icon     = get_sub_field( 'icon', get_option( 'page_on_front' ) )['sizes']['map_marker'];
			$width    = get_sub_field( 'width', get_option( 'page_on_front' ) );
			$height   = get_sub_field( 'height', get_option( 'page_on_front' ) );
			$content  = get_sub_field( 'content', get_option( 'page_on_front' ) );
			array_push( $map_arr, "[leaflet-marker iconUrl=\"$icon\" iconSize=\"$width,$height\" lat=\"$lat\" lng=\"$lng\"] $content [/leaflet-marker]" );
		endwhile; endif;
		echo do_shortcode( implode( $map_arr ) );
		?>
    </div>
</section>
