<?php
$sidemenu_locations = new WP_Query( [
	'post_type'      => 'location',
	'orderby'        => 'title',
	'order'          => 'asc',
	'posts_per_page' => - 1,
	'tax_query'      => array(
		array(
			'taxonomy' => 'location-city',
			'field'    => 'slug',
			'terms'    => \UTILS()->get_city(),
		),
	),
] );
?>

<section class="sidemenu<?php echo ( is_user_logged_in() ) ? ' sidemenu--logged-in' : ''; ?> sidemenu--left"
         id="leftMenu">
    <div class="sidemenu__header-container">
        <div class="sidemenu__header">
            <span> <?= pll__( 'Menu' ) ?> </span>
            <i class="fa fa-times close-modal" id="leftClose" aria-hidden="false"></i>
        </div>
    </div>
    <div class="container-fluid">
        <div class="content--left">
            <h6 class="sidemenu__location"><?= pll__( 'Cities' ) ?></h6>
            <div class="graphic-content">
				<?php
				$terms = get_terms( array(
					'taxonomy'   => 'location-city',
					'hide_empty' => false,
				) );

				if ( ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$city_page_object = get_field( 'city_page', $term );

						if ( $city_page_object ) {
							?>
                            <a href="<?php echo get_the_permalink( $city_page_object->ID ); ?>">
                                <article class="menu-box-container">
                                    <div class="menu-box"
                                         style="background-image: url(<?php echo get_the_post_thumbnail_url( $city_page_object->ID, 'location_thumb' ); ?>);">
                                        <span class="hyphenate"
                                              style="padding-right: 10px;"><?php echo $term->name ?></span>
                                    </div>
                                </article>
                            </a>
							<?php
						}
					}
				}

				?>
            </div>

            <h6 class="sidemenu__location"><?= pll__( 'Locations' ) ?></h6>
            <div class="graphic-content visible-xs" id="locationGallery">
				<?php if ( $sidemenu_locations->have_posts() ) : while ( $sidemenu_locations->have_posts() ) : $sidemenu_locations->the_post();
					get_template_part( 'partials/content', 'excerpt-sidemenu' );
				endwhile; endif; ?>
            </div>
            <div class="graphic-content hidden-xs">
				<?php if ( $sidemenu_locations->have_posts() ) : while ( $sidemenu_locations->have_posts() ) : $sidemenu_locations->the_post();
					get_template_part( 'partials/content', 'excerpt-sidemenu' );
				endwhile; endif;
				wp_reset_postdata(); ?>
            </div>
        </div>
        <div class="content--right">
            <span class="language"> <?= pll__( 'Language' ) ?> </span>
			<?php nattours_main_menu(); ?>
        </div>
    </div>
</section>
