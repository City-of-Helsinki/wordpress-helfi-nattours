<?php

class REST_Places extends WP_REST_Controller {

	public function __construct() {
		$this->namespace = 'wp/v2';
		$this->rest_base = 'places';
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		register_rest_route( $this->namespace, '/' . $this->rest_base, [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_items' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
				'args'                => [],
			]
		] );
	}

	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {

		$city_page_id = $_GET['cityid'] ? $_GET['cityid'] : null;

		$args = [
			'post_type'      => 'location',
			'orderby'        => 'title',
			'order'          => 'asc',
			'posts_per_page' => - 1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'location-city',
					'field'    => 'term_id',
					'terms'    => get_field( 'city_location', $city_page_id ),
				),
			),
		];

		$items = ( new WP_Query( $args ) )->get_posts();
		$data  = [];
		foreach ( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );

			if ( $itemdata !== null ) {
				$data[] = $this->prepare_response_for_collection( $itemdata );
			}
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed $item WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {

		$post_meta = get_post_meta( $item->ID );

		/**
		 * Get route map file into array
		 */
		$map_file_id  = $post_meta['map_file'][0];
		$map_file_url = wp_get_attachment_url( $map_file_id );
		$json         = $map_file_url ? file_get_contents( $map_file_url ) : '';
		$map_json     = $json ? json_decode( $json, true ) : array();

		/**
		 * Get route points
		 */
		$points = [];
		if ( have_rows( 'services_markers', $item->ID ) ) {
			while ( have_rows( 'services_markers', $item->ID ) ) {
				the_row();
				$location = get_sub_field( 'location' );
				$content  = apply_filters( 'the_content', get_sub_field( 'content' ) );
				$content  = strip_tags( $content, '<p><img>' ); //leave only p/img-tags to content
				array_push( $points, [
						'locationPoint' => [
							'lat'       => $location['lat'],
							'lng'       => $location['lng'],
							'pointInfo' => $content,
						],
					]
				);
			}
		}


		return [
			'ID'     => $item->ID,
			'title'  => $item->post_title,
			'url'    => get_permalink( $item->ID ),
			'routes' => $map_json,
			'points' => $points,
			//'description'         => apply_filters( 'the_content', $item->post_content ),
			//'description_routes'  => apply_filters( 'the_content', $post_meta['services_text'][0] ),
			//'description_history' => apply_filters( 'the_content', $post_meta['history_text'][0] ),
		];
	}

	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Check if a given request has access to get items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		return true;
	}

	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return [
			'page'     => [
				'description'       => 'Current page of the collection.',
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
			],
			'per_page' => [
				'description'       => 'Maximum number of items to be returned in result set.',
				'type'              => 'integer',
				'default'           => 10,
				'sanitize_callback' => 'absint',
			],
			'search'   => [
				'description'       => 'Limit results to those matching a string.',
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			],
		];
	}
}

add_action( 'rest_api_init', function () {
	$places = new REST_Places();

	return $places->register_routes();
} );
