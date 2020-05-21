<?php
//Exit if accessed directly
if (!defined('ABSPATH')) { exit; }

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/admin
 * @author     Nick Makris <nick@makris.io>
 */
class Locations_Maps_Admin
{
	
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The meta_fields to register to the options page
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $meta_fields The meta fields to register
	 */
	private $meta_fields = [];
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 * @param array  $fields      An array of fields for the otpions page
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct($plugin_name, $version, $fields)
	{
		
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->meta_fields = $fields;
		
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Locations_Maps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Locations_Maps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/locations-maps-admin.min.css', [], $this->version, 'all');
		
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Locations_Maps_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Locations_Maps_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/locations-maps-admin.min.js', ['jquery'], $this->version, true);
		
		$screen = get_current_screen();
		if ((is_object($screen) || is_array($screen)) && $screen->id === 'locations_page_location-maps-settings')
		{
			wp_enqueue_media();
			wp_enqueue_script($this->plugin_name . '-settings', plugin_dir_url(__FILE__) . 'js/location-maps-settings.min.js', ['jquery'], $this->version, true);
		}
		
		if (is_object($screen) && $screen->id === 'locations')
		{
			$nonce    = wp_create_nonce('map_ajax_nonce');
			$map_vars = [
				//				'root'  => esc_url_raw( rest_url() ),
				'nonce'       => $nonce,
				'adminURL'    => admin_url('admin-ajax.php'),
			];
			
			wp_localize_script($this->plugin_name, 'MAP_ADMIN', $map_vars);
		}
		
	}
	
	/**
	 * Function Name: locations_maps_menu_page
	 * Description: Declares the Menu Page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 *
	 */
	public function locations_maps_menu_page()
	{
		
		$icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgNTIyLjQ2OCA1MjIuNDY5IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyMCAyMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGRlZnM+PHN0eWxlPi5ncmV5e2ZpbGw6I2EwYTVhYTt9PC9zdHlsZT48L2RlZnM+PHBhdGggY2xhc3M9ImdyZXkiIGQ9Ik0zMjUuNzYyLDcwLjUxM2wtMTcuNzA2LTQuODU0Yy0yLjI3OS0wLjc2LTQuNTI0LTAuNTIxLTYuNzA3LDAuNzE1Yy0yLjE5LDEuMjM3LTMuNjY5LDMuMDk0LTQuNDI5LDUuNTY4TDE5MC40MjYsNDQwLjUzIGMtMC43NiwyLjQ3NS0wLjUyMiw0LjgwOSwwLjcxNSw2Ljk5NWMxLjIzNywyLjE5LDMuMDksMy42NjUsNS41NjgsNC40MjVsMTcuNzAxLDQuODU2YzIuMjg0LDAuNzY2LDQuNTIxLDAuNTI2LDYuNzEtMC43MTIgYzIuMTktMS4yNDMsMy42NjYtMy4wOTQsNC40MjUtNS41NjRMMzMyLjA0Miw4MS45MzZjMC43NTktMi40NzQsMC41MjMtNC44MDgtMC43MTYtNi45OTkgQzMzMC4wODgsNzIuNzQ3LDMyOC4yMzcsNzEuMjcyLDMyNS43NjIsNzAuNTEzeiIvPjxwYXRoIGNsYXNzPSJncmV5IiBkPSJNMTY2LjE2NywxNDIuNDY1YzAtMi40NzQtMC45NTMtNC42NjUtMi44NTYtNi41NjdsLTE0LjI3Ny0xNC4yNzZjLTEuOTAzLTEuOTAzLTQuMDkzLTIuODU3LTYuNTY3LTIuODU3IHMtNC42NjUsMC45NTUtNi41NjcsMi44NTdMMi44NTYsMjU0LjY2NkMwLjk1LDI1Ni41NjksMCwyNTguNzU5LDAsMjYxLjIzM2MwLDIuNDc0LDAuOTUzLDQuNjY0LDIuODU2LDYuNTY2bDEzMy4wNDMsMTMzLjA0NCBjMS45MDIsMS45MDYsNC4wODksMi44NTQsNi41NjcsMi44NTRzNC42NjUtMC45NTEsNi41NjctMi44NTRsMTQuMjc3LTE0LjI2OGMxLjkwMy0xLjkwMiwyLjg1Ni00LjA5MywyLjg1Ni02LjU3IGMwLTIuNDcxLTAuOTUzLTQuNjYxLTIuODU2LTYuNTYzTDUxLjEwNywyNjEuMjMzbDExMi4yMDQtMTEyLjIwMUMxNjUuMjE3LDE0Ny4xMywxNjYuMTY3LDE0NC45MzksMTY2LjE2NywxNDIuNDY1eiIvPjxwYXRoIGNsYXNzPSJncmV5IiBkPSJNNTE5LjYxNCwyNTQuNjYzTDM4Ni41NjcsMTIxLjYxOWMtMS45MDItMS45MDItNC4wOTMtMi44NTctNi41NjMtMi44NTdjLTIuNDc4LDAtNC42NjEsMC45NTUtNi41NywyLjg1N2wtMTQuMjcxLDE0LjI3NSBjLTEuOTAyLDEuOTAzLTIuODUxLDQuMDktMi44NTEsNi41NjdzMC45NDgsNC42NjUsMi44NTEsNi41NjdsMTEyLjIwNiwxMTIuMjA0TDM1OS4xNjMsMzczLjQ0MiBjLTEuOTAyLDEuOTAyLTIuODUxLDQuMDkzLTIuODUxLDYuNTYzYzAsMi40NzgsMC45NDgsNC42NjgsMi44NTEsNi41N2wxNC4yNzEsMTQuMjY4YzEuOTA5LDEuOTA2LDQuMDkzLDIuODU0LDYuNTcsMi44NTQgYzIuNDcxLDAsNC42NjEtMC45NTEsNi41NjMtMi44NTRMNTE5LjYxNCwyNjcuOGMxLjkwMy0xLjkwMiwyLjg1NC00LjA5NiwyLjg1NC02LjU3IEM1MjIuNDY4LDI1OC43NTUsNTIxLjUxNywyNTYuNTY1LDUxOS42MTQsMjU0LjY2M3oiLz48L3N2Zz4=';
		//$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = ''
		add_submenu_page('edit.php?post_type=locations',
			'Settings', //page_title
			'Settings',//menu_title
			'manage_options', //capability
			'location-maps-settings', //menu_slug
			[
				$this,
				'locations_maps_options_page',
			] //function
		);
	}
	
	/**
	 * Function Name: locations_maps_setup_sections
	 * Description: Sets up the sections for the Options Page based on their callback
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 *
	 */
	public function locations_maps_setup_sections()
	{
		$sections = [
			[
				'id'       => 'locations_maps_section_one',
				'title'    => 'Google Maps Settings',
				'callback' => [
					$this,
					'locations_maps_section_callback',
				],
				'function' => 'locations_maps_fields',
			],
			[
				'id'       => 'locations_maps_section_two',
				'title'    => 'Content Settings',
				'callback' => [
					$this,
					'locations_maps_section_callback',
				],
				'function' => 'locations_maps_fields',
			]
		];
		// Run through each section and register them
		foreach ($sections as $section)
		{
			add_settings_section(
				$section['id'],
				$section['title'],
				$section['callback'],
				$section['function']
			);
		}
	}
	
	/**
	 * Function Name: locations_maps_setup_fields
	 * Description: Sets up the fields for the Options Page based on their callback
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 *
	 */
	public function locations_maps_setup_fields()
	{
		
		if (!empty($this->meta_fields))
		foreach ( (array) $this->meta_fields as $field)
		{
			/**
			 * @param array                 $field      The passing array of fields you're registering
			 * @param int|string|bool|array $id         The ID of your field
			 * @param string                $title      The title of your field, used for labeling
			 * @param callable              $callback   The function call back for adding the settings
			 * @param callable              $page       The page this will live on
			 * @param callable              $section    Which section of the options page this exists
			 * @param array                 $args       An array of additional args for this field
			 */
			add_settings_field(
				$field['uid'],
				$field['title'],
				[ $this, 'locations_maps_field_callback' ],
				'locations_maps_fields',
				$field['section'],
				$field
			);
		}
		
		register_setting('locations_maps_fields', 'lm_options', [$this, 'lm_options_validate']);
		
	}
	
	private function lm_options_validate($input) {
		// Sanitize input or textarea input (strip html tags, and escape characters)
		//$input['uid'] = wp_filter_nohtml_kses($field['uid']);
		$input['google_api_key'] = wp_filter_nohtml_kses($input['google_api_key']);
		$input['google_geocode_api_key'] = wp_filter_nohtml_kses($input['google_geocode_api_key']);
		return $input;
	}
	
	/**
	 * Function Name: locations_maps_options_page
	 * Description: The actual form HTML for the page, minus the declared function sections
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 *
	 */
	public function locations_maps_options_page()
	{
		
		include(plugin_dir_path(__FILE__) . '/menu-pages/toggle-options.php');
	}
	
	/**
	 * Function Name: locations_maps_shortcode_menu_page
	 * Description: The base holder for each option page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 *
	 */
	public function locations_maps_shortcode_menu_page()
	{
		
		include(plugin_dir_path(__FILE__) . '/menu-pages/main-menu.php');
	}
	
	
	/**
	 * Function Name: locations_maps_section_callback
	 * Description: Creates the sections for the options page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 */
	public function locations_maps_section_callback($args)
	{
		include(plugin_dir_path(__FILE__) . '/partials/options-section.php');
	}
	
	/**
	 * Function Name: locations_maps_field_callback
	 * Description: Creates the fields for the options page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 * @param $args
	 *
	 */
	public function locations_maps_field_callback($args)
	{
		include(plugin_dir_path(__FILE__) . '/partials/options-fields.php');
	}
	
	public function locations_maps_add_json_mime($mime_types) {
		$user = wp_get_current_user();
		
		if (in_array('administrator', (array)$user->roles)) {
			$mime_types['json'] = 'application/json';
		}
		
		return $mime_types;
		
	}
	
}
