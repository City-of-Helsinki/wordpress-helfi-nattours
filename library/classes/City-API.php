<?php

class REST_Cities extends WP_REST_Controller {

	public function __construct() {
		$this->namespace = 'wp/v2';
		$this->rest_base = 'cities';
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

		$lang = $_GET['lang'] ? $_GET['lang'] : '';

		$args = [
			'post_type'      => 'page',
			'orderby'        => 'title',
			'order'          => 'asc',
			'posts_per_page' => - 1,
			'meta_query'     => [
				[
					'key'   => '_wp_page_template',
					'value' => 'custom-templates/locations-front-page.php'
				]
			],
			'lang'           => $lang,

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

		$city_location_meta = get_post_meta( $item->ID, 'city_location', true );
		$location_term = get_term_by('id', $city_location_meta, 'location-city');

		return [
			'id'    => $item->ID,
			'location' => $location_term->name,
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
	$places = new REST_Cities();

	return $places->register_routes();
} );
