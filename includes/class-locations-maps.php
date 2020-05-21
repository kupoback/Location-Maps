<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Locations_Maps
 * @subpackage Locations_Maps/includes
 * @author     Nick Makris <nick@makris.io>
 */
class Locations_Maps {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Locations_Maps_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	/**
	 * The meta fields passed through to the options page
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $opt_meta_fields The return array of meta fields to add
	 *
	 */
	protected $opt_meta_fields;
	
	/**
	 * The meta fields passed through to the options page
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $meta_fields The return array of meta fields to add
	 *
	 */
	protected $meta_fields;
	
	/**
	 * The meta fields passed through to locations post type
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $meta_fields The return array of meta fields to add
	 *
	 */
	protected $post_fields;
	
	/**
	 * The meta fields passed through to save function
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $meta_fields The return array of meta fields to save
	 *
	 */
	protected $save_fields;
	
	/**
	 * The meta fields passed through to add to various core metaboxes
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $non_core_fields The return array of meta fields to add
	 *
	 */
	protected $non_core_fields;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LOCATIONS_MAPS_VERSION' ) ) {
			$this->version = LOCATIONS_MAPS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'locations-maps';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Locations_Maps_Loader. Orchestrates the hooks of the plugin.
	 * - Locations_Maps_i18n. Defines internationalization functionality.
	 * - Locations_Maps_Admin. Defines all hooks for the admin area.
	 * - Locations_Maps_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		/**
		 * The declaration of custom fields added to the options page
		 */
		$this->opt_meta_fields = require_once plugin_dir_path(dirname(__FILE__)) . 'admin/meta_fields/option-fields.php';
		
		/**
		 * The declaration of custom fields added to the locations post type
		 */
		$this->meta_fields = require_once plugin_dir_path(dirname(__FILE__)) . 'admin/meta_fields/post-meta-fields.php';
		
		/**
		 * The declaration of custom fields used for saving via submit or ajax
		 */
		$this->save_fields = require_once plugin_dir_path(dirname(__FILE__)) . 'admin/meta_fields/post-save-fields.php';
		
		/**
		 * The declaration of custom fields added to the core wordpress metaboxes
		 */
		$this->non_core_fields = require_once plugin_dir_path(dirname(__FILE__)) . 'admin/meta_fields/non-metabox-custom-fields.php';
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locations-maps-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-locations-maps-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-locations-maps-admin.php';
		
		/**
		 * The class responsible for adding the maps custom post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-locations-maps-custom-post-type.php';

		/**
		 * The class responsible for adding metaboxes to the maps post type
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-locations-maps-metaboxes.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-locations-maps-admin-api.php';
		
		/**
		 * This class allows for a use of get_template_part in the theme
		 */
		require_once LOCATIONS_MAPS_PLUGIN_PATH . 'public/class-gamajo-template-loader.php';
		
		/**
		 * This class extends the plugin to allow for overrideable templates
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-lm-template-loader.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-locations-maps-public.php';
		
		/**
		 * The class responsible for building the API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-locations-maps-api.php';
		
		/**
		 * The class responsible for defining all accessible functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/locations-maps-public-functions.php';

		$this->loader = new Locations_Maps_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Locations_Maps_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Locations_Maps_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Locations_Maps_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_option_meta_fields());
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('admin_menu', $plugin_admin, 'locations_maps_menu_page');
		$this->loader->add_action('admin_init', $plugin_admin, 'locations_maps_setup_sections', 1);
		$this->loader->add_action('admin_init', $plugin_admin, 'locations_maps_setup_fields');
		$this->loader->add_filter('upload_mimes', $plugin_admin, 'locations_maps_add_json_mime', 1, 1 );
		
		$plugin_cpt = new Locations_Maps_Custom_Post_Type( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_cpt, 'custom_post_type_locations' );
		
		$plugin_mb = new Locations_Maps_Metaboxes( $this->get_plugin_name(), $this->get_version(), $this->get_post_meta_fields(), $this->get_save_meta_fields(), $this->get_non_core_meta_fields() );
		$this->loader->add_action( 'add_meta_boxes', $plugin_mb, 'locations_maps_metabox' );
		$this->loader->add_action( 'save_post', $plugin_mb, 'locations_maps_metabox_save', 10, 2 );
		$this->loader->add_action( 'wp_ajax_nopriv_geo_cb', $plugin_mb, 'locations_maps_metabox_ajax');
		$this->loader->add_action( 'wp_ajax_geo_cb', $plugin_mb, 'locations_maps_metabox_ajax');
		$this->loader->add_action( 'rest_api_init', $plugin_mb, 'locations_maps_add_custom_fields');
		// $this->loader->add_action( 'post_submitbox_misc_actions', $plugin_mb, 'locations_maps_update_box_custom_fields');
	
		$plugin_api = new Locations_Maps_Admin_API($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('rest_api_init', $plugin_api, 'register_routes');
	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Locations_Maps_Public( $this->get_plugin_name(), $this->get_version() );
		
		$plugin_locations_api = new Locations_Maps_API($this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('rest_api_init', $plugin_locations_api, 'register_routes');

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Locations_Maps_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 * Function Name: get_option_meta_fields
	 * Description: The option meta fields
	 *
	 * @return array
	 */
	public function get_option_meta_fields()
	{
		return $this->opt_meta_fields;
	}
	
	/**
	 * Function Name: get_post_meta_fields
	 * Description: The post type meta fields
	 *
	 * @return array
	 */
	public function get_post_meta_fields()
	{
		return $this->meta_fields;
	}
	
	/**
	 * Function Name: save_meta_fields
	 * Description: The common saved meta fields
	 *
	 * @return array
	 */
	public function get_save_meta_fields() {
		return $this->save_fields;
	}
	
	/**
	 * Function Name: non_core_meta_fields
	 * Description: Non core meta fields
	 *
	 * @return array
	 */
	public function get_non_core_meta_fields() {
		return $this->non_core_fields;
	}

}
