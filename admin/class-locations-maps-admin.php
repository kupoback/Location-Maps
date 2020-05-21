<?php

if (!defined('ABSPATH'))
{
	exit;//Exit if accessed directly
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		
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
		
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/locations-maps-admin.css', [], $this->version, 'all');
		
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
		
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/locations-maps-admin.js', ['jquery'], $this->version, true);
		
		$screen = get_current_screen();
		if ((is_object($screen) || is_array($screen)) && $screen->id === 'locations_page_location-maps-settings')
		{
			wp_enqueue_media();
			wp_enqueue_script($this->plugin_name . '-settings', plugin_dir_url(__FILE__) . 'js/location-maps-settings.js', ['jquery'], $this->version, true);
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
	 * @package saws
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
	 * Description: Sets up the sections for the Options Page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package saws
	 *
	 *
	 */
	public function locations_maps_setup_sections()
	{
		
		$sections = [
			[
				'id'       => 'locations_maps_section_one',
				'title'    => '',
				'callback' => [
					$this,
					'locations_maps_section_callback',
				],
				'function' => 'locations_maps_fields',
			],
		];
		
		// Run through each section and register them
		foreach ($sections as $section)
		{
			add_settings_section($section['id'],
				$section['title'],
				$section['callback'],
				$section['function']
			);
		}
	}
	
	/**
	 * Function Name: locations_maps_setup_fields
	 * Description: Sets up the fields for the Options Page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package saws
	 *
	 *
	 */
	public function locations_maps_setup_fields()
	{
		
		$fields = [
			// @TODO Integrate with the site
			//			[
			//				'uid'     => 'locations_maps_toggle_location_taxonomy',
			//				'title'   => 'Location Category',
			//				'label_for' =>  'locations_maps_toggle_location_taxonomy',
			//				'section' => 'locations_maps_section_one',
			//				'type'    => 'checkbox',
			//				'options' => [
			//					'add_button' => __('Activate Taxonomy', 'textdomain'),
			//				],
			//				'default' => [],
			//			],
			[
				'uid'       => 'locations_maps_google_api_key',
				'title'     => 'Google Maps API Key',
				'label_for' => 'locations_maps_google_api_key',
				'section'   => 'locations_maps_section_one',
				'type'      => 'text',
				'default'   => '',
				'helper'    => 'Please obtain an API key from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="noopener">here</a>. Click on "Get Started" when landing on the page.',
			],
			[
				'uid'       => 'locations_maps_map_icon',
				'title'     => 'Map Icon',
				'label_for' => 'locations_maps_map_icon',
				'section'   => 'locations_maps_section_one',
				'type'      => 'image',
				'default'   => '',
			],
			[
				'uid'       => 'locations_map_map_style',
				'title'     => 'Map Style',
				'section'   => 'locations_maps_section_one',
				'label_for' => 'locations_map_map_style',
				'type'      => 'select',
				'options'   => [
					'none'      => 'Select',
					'aubergine' => 'Aubergine',
					'dark'      => 'Dark',
					'night'     => 'Night',
					'retro'     => 'Retro',
					'silver'    => 'Silver',
					'other'     => 'Other',
				],
				'default'   => [],
			],
			[
				'uid'       => 'locations_maps_style_override',
				'title'     => 'Map Style Other',
				'label_for' => 'locations_maps_style_override',
				'section'   => 'locations_maps_section_one',
				'type'      => 'file',
				'helper'    => 'Please use JSON files only.',
				'default'   => '',
			],
		];
		
		// Run through each field array and register the setting
		foreach ($fields as $field)
		{
			add_settings_field($field['uid'],
				$field['title'],
				[
					$this,
					'locations_maps_field_callback',
				],
				'locations_maps_fields',
				$field['section'],
				$field
			);
			
			register_setting('locations_maps_fields', $field['uid']);
			
		}
		
	}
	
	/**
	 * Function Name: locations_maps_options_page
	 * Description: Grabs the options page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package saws
	 *
	 *
	 */
	public function locations_maps_options_page()
	{
		
		include(plugin_dir_path(__FILE__) . '/menu-pages/toggle-options.php');
	}
	
	/**
	 * Function Name: locations_maps_shortcode_menu_page
	 * Description: Grabs the menu page
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package saws
	 *
	 *
	 */
	public function locations_maps_shortcode_menu_page()
	{
		
		include(plugin_dir_path(__FILE__) . '/menu-pages/main-menu.php');
	}
	
	/**
	 * Function Name: locations_maps_section_callback
	 * Description: Creates the sections
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package saws
	 *
	 * @param $args
	 *
	 */
	public function locations_maps_section_callback($args)
	{
		include(plugin_dir_path(__FILE__) . '/partials/options-section.php');
	}
	
	/**
	 * Function Name: locations_maps_field_callback
	 * Description: Creates the fields
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package saws
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
