<?php

namespace Nord;

class Settings {

	/**
	 * Default Option key
	 * @var string
	 */
	private $key = 'nattours_options';

	/**
	 * Array of metaboxes/fields
	 * @var array
	 */
	protected $option_metabox = [ ];

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Tab Pages
	 * @var array
	 */
	protected $options_pages = [ ];

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->title = __( 'Theme Options', TEXT_DOMAIN );
	}

	/**
	 * Initiate our hooks
	 */
	public function hooks() {
		add_action( 'admin_init', [ $this, 'init' ] );
		add_action( 'admin_menu', [ $this, 'add_options_page' ] ); //create tab pages
	}

	/**
	 * Register the setting tabs to WP
	 */
	public function init() {
		$option_tabs = self::option_fields();
		foreach ( $option_tabs as $index => $option_tab ) {
			register_setting( $option_tab['id'], $option_tab['id'] );
		}
	}

	/**
	 * Add menu options page
	 */
	public function add_options_page() {
		$option_tabs = self::option_fields();
		foreach ( $option_tabs as $index => $option_tab ) {
			if ( $index == 0 ) {
				$this->options_pages[] = add_menu_page( $this->title, $this->title, 'manage_options', $option_tab['id'],
					[ $this, 'admin_page_display' ], 'dashicons-admin-tools' ); //Link admin menu to first tab
				add_submenu_page( $option_tabs[0]['id'], $this->title, $option_tab['title'], 'manage_options',
					$option_tab['id'], [ $this, 'admin_page_display' ] ); //Duplicate menu link for first submenu page
			} else {
				$this->options_pages[] = add_submenu_page( $option_tabs[0]['id'], $this->title, $option_tab['title'],
					'manage_options', $option_tab['id'], [ $this, 'admin_page_display' ] );
			}
		}
	}

	/**
	 * Admin page markup. Mostly handled by CMB
	 */
	public function admin_page_display() {
		$option_tabs = self::option_fields();
		$tab_forms   = [ ];
		?>
		<div class="wrap cmb_options_page <?php echo $this->key; ?>">
			<h2><i class="fa fa-globe"></i> <?php echo esc_html( get_admin_page_title() ); ?></h2>

			<h2 class="nav-tab-wrapper">
				<?php foreach ( $option_tabs as $option_tab ) :
					$tab_slug = $option_tab['id'];
					$nav_class = 'nav-tab';
					if ( $tab_slug == $_GET['page'] ) {
						$nav_class .= ' nav-tab-active';
						$tab_forms[] = $option_tab;
					}
					?>
					<a class="<?php echo $nav_class; ?>"
					   href="<?php menu_page_url( $tab_slug ); ?>"><?php esc_attr_e( $option_tab['title'] ); ?></a>
				<?php endforeach; ?>
			</h2>


			<?php foreach ( $tab_forms as $tab_form ) : //render all tab forms (normaly just 1 form) ?>
				<div id="<?php esc_attr_e( $tab_form['id'] ); ?>" class="group">
					<?php cmb2_metabox_form( $tab_form, $tab_form['id'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Defines the theme option metabox and field configuration
	 *
	 * @return array
	 */
	public function option_fields() {

		if ( ! empty( $this->option_metabox ) ) {
			return $this->option_metabox;
		}

		/**
		 * General options
		 */
		$this->option_metabox[] = [
			'id'         => 'nattours_general_options',
			'title'      => __( 'General options', TEXT_DOMAIN ),
			'show_on'    => [ 'key' => 'options-page', 'value' => [ 'nattours_general_options' ], ],
			'show_names' => true,
			'fields'     => [
				[
					'name'    => __( 'Tag-manager', TEXT_DOMAIN ),
					'desc'    => __( 'Add here the full Tag-manager -snippet.', TEXT_DOMAIN ),
					'id'      => 'nattours_tagmanager',
					'default' => '',
					'type'    => 'textarea_code',
				],
				[
					'name'    => __( 'Default post image', TEXT_DOMAIN ),
					'desc'    => __( 'define default post image if no images are attached to post / page',
						TEXT_DOMAIN ),
					'id'      => 'nattours_default_image',
					'default' => '',
					'type'    => 'file',
				],
				[
					'name'    => __( 'Digitransit API key', TEXT_DOMAIN ),
					'id'      => 'nattours_digitransit_api_key',
					'default' => '',
					'type'    => 'text',
				],
			]
		];

		$this->option_metabox[] = [
			'id'         => 'nattours_some_options_helsinki',
			'title'      => __( 'Helsinki some-settings', TEXT_DOMAIN ),
			'show_on'    => [ 'key' => 'options-page', 'value' => [ 'nattours_some_options_helsinki' ], ],
			'show_names' => true,
			'fields'     => [
				[
					'name'    => __( 'Facebook URL', TEXT_DOMAIN ),
					'id'      => 'nattours_facebook_url',
					'default' => '',
					'type'    => 'text',
				],
				[
					'name'    => __( 'Twitter URL', TEXT_DOMAIN ),
					'id'      => 'nattours_twitter_url',
					'default' => '',
					'type'    => 'text',
				],
				[
					'name'    => __( 'Youtube URL', TEXT_DOMAIN ),
					'id'      => 'nattours_youtube_url',
					'default' => '',
					'type'    => 'text',
				],
				[
					'name'    => __( 'Instagram URL', TEXT_DOMAIN ),
					'id'      => 'nattours_instagram_url',
					'default' => '',
					'type'    => 'text',
				],
			]
		];

		$this->option_metabox[] = [
			'id'         => 'nattours_some_options_tallinn',
			'title'      => __( 'Tallinn some-settings', TEXT_DOMAIN ),
			'show_on'    => [ 'key' => 'options-page', 'value' => [ 'nattours_some_options_tallinn' ], ],
			'show_names' => true,
			'fields'     => [
				[
					'name'    => __( 'Facebook URL', TEXT_DOMAIN ),
					'id'      => 'nattours_facebook_url',
					'default' => '',
					'type'    => 'text',
				],
				[
					'name'    => __( 'Twitter URL', TEXT_DOMAIN ),
					'id'      => 'nattours_twitter_url',
					'default' => '',
					'type'    => 'text',
				],
				[
					'name'    => __( 'Youtube URL', TEXT_DOMAIN ),
					'id'      => 'nattours_youtube_url',
					'default' => '',
					'type'    => 'text',
				],
				[
					'name'    => __( 'Instagram URL', TEXT_DOMAIN ),
					'id'      => 'nattours_instagram_url',
					'default' => '',
					'type'    => 'text',
				],
			]
		];

		return $this->option_metabox;
	}

	/**
	 * Returns the option key for a given field id
	 *
	 * @param $field_id
	 *
	 * @return string
	 */
	public function get_option_key( $field_id ) {
		$option_tabs = $this->option_fields();
		foreach ( $option_tabs as $option_tab ) { //search all tabs
			foreach ( $option_tab['fields'] as $field ) { //search all fields
				if ( $field['id'] == $field_id ) {
					return $option_tab['id'];
				}
			}
		}

		return $this->key; //return default key if field id not found
	}

	/**
	 * @param $field
	 *
	 * @return array
	 * @throws Exception
	 */
	public function __get( $field ) {

		// Allowed fields to retrieve
		if ( in_array( $field, [ 'key', 'fields', 'title', 'options_pages' ], true ) ) {
			return $this->{$field};
		}
		if ( 'option_metabox' === $field ) {
			return $this->option_fields();
		}

		throw new Exception( 'Invalid property: ' . $field );
	}
}

/**
 * Only construct class if CMB2 is loaded
 */
if ( defined( 'CMB2_LOADED' ) ) :

	$admin = new Settings;
	$admin->hooks();

	/**
	 *
	 * helper to get option values
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	function get_settings_field( $key = '' ) {
		global $admin;

		return cmb2_get_option( $admin->get_option_key( $key ), $key );
	}

endif;

?>
