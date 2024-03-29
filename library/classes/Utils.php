<?php

namespace Nord;

class Utils {

	/**
	 * Display navigation to next/previous pages when applicable
	 *
	 * @param $nav_id
	 */
	function content_nav( $nav_id ) {
		global $wp_query, $post;

		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
		}

		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

		?>
        <nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
            <ul class="pager">
				<?php if ( is_single() ) : ?>
					<?php previous_post_link( '<li class="nav-previous previous">%link</li>',
						'<span class="meta-nav">' . _x( '&larr;', 'Previous post link',
							TEXT_DOMAIN ) . '</span> %title' ); ?>
					<?php next_post_link( '<li class="nav-next next">%link</li>',
						'%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link',
							TEXT_DOMAIN ) . '</span>' ); ?>

				<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
					<?php if ( get_next_posts_link() ) : ?>
                        <li class="nav-previous previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts',
								TEXT_DOMAIN ) ); ?></li>
					<?php endif; ?>
					<?php if ( get_previous_posts_link() ) : ?>
                        <li class="nav-next next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>',
								TEXT_DOMAIN ) ); ?></li>
					<?php endif; ?>
				<?php endif; ?>
            </ul>
        </nav>
		<?php
	}

	/**
	 * Get pagination
	 */
	public function pagination() {
		if ( is_singular() ) {
			return;
		}

		global $wp_query;
		$paged             = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max               = intval( $wp_query->max_num_pages );
		$default_classname = 'pagination--item';

		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}

		if ( $paged >= 1 ) {
			$links[] = $paged;
		}

		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}

		echo '<ul class="pagination__list">' . "\n";

		if ( get_previous_posts_link() ) {
			printf( '<li class="post--link pagination--item">%s</li>' . "\n",
				get_previous_posts_link( '<i class="fa fa-angle-left"></i>' ) );
		}

		if ( ! in_array( 1, $links ) ) {
			$class = 1 == $paged ? ' class="pagination--item current"' : ' class="pagination--item"';

			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

			if ( ! in_array( 2, $links ) ) {
				echo '<li class="pagination--item"><a href="#">&hellip;</a></li>';
			}
		}

		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = $paged == $link ? ' class="pagination--item current"' : ' class="pagination--item"';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
		}

		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) ) {
				echo '<li class="pagination--item"><a href="#">&hellip;</a></li>' . "\n";
			}

			$class = $paged == $max ? ' class="current pagination--item"' : ' class="pagination--item"';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
		}

		if ( get_next_posts_link() ) {
			printf( '<li class="post--link pagination--item">%s</li>' . "\n",
				get_next_posts_link( '<i class="fa fa-angle-right"></i>' ) );
		}

		echo '</ul>' . "\n";
	}

	/**
	 * Get custom CPTs
	 *
	 * @return array
	 */
	public function get_custom_post_types() {
		return get_post_types( [
			'public'   => true,
			'_builtin' => false
		] );
	}

	/**
	 * Get first category-item
	 *
	 * @param string $taxonomy
	 *
	 * @return mixed
	 */
	public function get_category( $taxonomy = 'category' ) {
		$categories = wp_get_object_terms( get_the_ID(), $taxonomy );

		return ! empty( $categories ) ? wp_get_object_terms( get_the_ID(), $taxonomy )[0] : null;
	}

	/**
	 * Get whole category-hierarchy
	 *
	 * @param string $taxonomy
	 *
	 * @return array
	 */
	public function get_category_hierarchy( $taxonomy = 'category' ) {

		$cats     = [];
		$category = wp_get_object_terms( get_the_ID(), $taxonomy )[0];
		$cat_tree = get_ancestors( $category->term_id, $taxonomy );
		array_push( $cat_tree, $category->term_id );
		asort( $cat_tree );

		foreach ( $cat_tree as $cat ) {
			$cats[] = get_term_by( 'id', $cat, $taxonomy );
		}

		return $cats;
	}

	/**
	 * Get parent-most category
	 *
	 * @param string $taxonomy
	 *
	 * @return mixed
	 */
	public function get_parent_category( $taxonomy = 'category' ) {
		$cats = self::get_category_hierarchy( $taxonomy );

		return $cats[0];
	}

	/**
	 * Get build images uri
	 *
	 * @return string
	 */
	public function get_image_uri() {
		return asset_uri( 'images' );
	}

	/**
	 * Get default image
	 *
	 * @param string $size
	 *
	 * @return array|false
	 */
	public function get_default_image( $size = 'full' ) {
		$image_id = isset( get_option( 'nattours_general_options' )['nattours_default_image_id'] ) ? get_option( 'nattours_general_options' )['nattours_default_image_id'] : null;

		return wp_get_attachment_image_src( $image_id, $size )[0];
	}

	/**
	 * Get city slug
	 *
	 * @return string
	 */
	function get_city() {
		global $post;
		$city_slug = '';

		if ( isset($post->post_type) && $post->post_type === 'location' ) {
			$terms = get_the_terms( $post, 'location-city' );

			if ( ! is_wp_error( $terms ) ) {
				if ( $terms ) {
					foreach ( $terms as $term ) {
						$city_slug = $term->slug;
					}
				}
			}
		} elseif ( is_page_template( 'custom-templates/locations-front-page.php' ) ) {
			$term = get_term( get_field( 'city_location', $post->ID ), 'location-city' );

			if ( ! is_wp_error( $term ) ) {
				$city_slug = $term->slug;
			}
		}

		if ( $city_slug === 'tallinna' ) {
			$city_slug = 'tallinn';
		}

		return $city_slug;
	}

	/**
	 * Get city term id
	 *
	 * @return integer
	 */
	function get_city_term_id() {
		global $post;
		$city_term_id = '';

		if ( $post->post_type === 'location' ) {
			$terms = get_the_terms( $post, 'location-city' );

			if ( ! is_wp_error( $terms ) ) {
				if ( $terms ) {
					foreach ( $terms as $term ) {
						$city_term_id = $term->term_id;
					}
				}
			}
		} elseif ( is_page_template( 'custom-templates/locations-front-page.php' ) ) {
			$term = get_term( get_field( 'city_location', $post->ID ), 'location-city' );

			if ( ! is_wp_error( $term ) ) {
				$city_term_id = $term->term_id;
			}
		} else {
			$city_term_id = null;
		}

		return $city_term_id;
	}

	/**
	 * Get city locations link
	 *
	 * @return integer
	 */
	function get_city_link( $term_id = null ) {
		$term = get_term( $term_id, 'location-city' );

		if ( ! is_wp_error( $term ) ) {
			$city_page_object = get_field( 'city_page', $term );

			return get_permalink( $city_page_object->ID );
		} else {
			return null;
		}
	}

	/**
	 * Modifies home url, if term found
	 *
	 * @param $term_id
	 *
	 * @return string
	 */
	function get_home_link( $term_id ) {
		if ( $term_id === null ) {
			return get_home_url();
		} else {
			return $this->get_city_link( $term_id );
		}
	}

	function get_map_tileurl() {
        $tile_url = 'https://b.tile.openstreetmap.org/{z}/{x}/{y}.png';

        $api_key = $this->digitransit_api_key();
        if ($api_key && $this->get_city() !== 'tallinn') {
            $tile_url = "https://cdn.digitransit.fi/map/v2/hsl-map/{z}/{x}/{y}.png?digitransit-subscription-key={$api_key}";
        }

		return $tile_url;
	}

	function digitransit_api_key() {
        $options = get_option( 'nattours_general_options' );

        return $options['nattours_digitransit_api_key'] ?? '';
	}

	function get_social_media_links( $contactOptions = [] ) {
		$socialMedias = [ 'facebook', 'twitter', 'youtube', 'instagram' ];

		foreach ( $socialMedias as $socialMedia ) {
			$field = 'nattours_' . $socialMedia . '_url';

			if ( ! empty( $contactOptions[ $field ] ) ) {
				$faClass = 'fa-' . $socialMedia . '-square';
				if ( $socialMedia === 'instagram' ) {
					$faClass = 'fa-' . $socialMedia;
				}
				?>
                <a class="some-link" href="<?php echo $contactOptions[ $field ] ?>" target="_blank">
                    <i class="fa <?php echo $faClass ?>" aria-hidden="true"></i>
                </a>
				<?php
			}
		}
	}
}
