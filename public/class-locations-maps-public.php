<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/public
 * @author     Nick Makris <nick@makris.io>
 */
class Locations_Maps_Public
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
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		
		add_shortcode('map', [ $this, 'map_module_short_code' ]);
		
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
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
	}
	
	public function add_async_defer_google_maps( $tag, $handle ) {
		
		return $this->plugin_name . '-google-maps' === $handle ? str_replace( ' src', ' defer src', $tag ) : $tag;
		
	}
	
	/**
	 * Function: map_module_short_code
	 * Description:
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 * Author URI: https://makris.io
	 *
	 * @package MDH
	 *
	 * @param $atts
	 * @shortcode [map map_id="" zoom="15"]
	 * @return
	 *
	 */
	public function map_module_short_code($atts)
	{
		
		
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/locations-maps-public.css', [], $this->version, 'all');
		
		$api_key = get_option('locations_maps_google_api_key');
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/locations-maps-public.js', ['jquery'], $this->version, true);
		
		wp_enqueue_script($this->plugin_name . '-google-maps', "https://maps.googleapis.com/maps/api/js?key={$api_key}&callback=initMap", ['jquery'], '', true);
		
		$fallback_map_style = plugin_dir_url(__FILE__) . 'map-styles/dark.json';
		
		$map_style = get_option('locations_map_map_style') && ( !in_array('none', get_option('locations_map_map_style') ) && !in_array('other', get_option('locations_map_map_style') ) )
			? plugin_dir_url(__FILE__) . 'map-styles/' . get_option('locations_map_map_style' )[0] . '.json'
			: ( in_array('other', get_option('locations_map_map_style') ) && get_option('locations_maps_style_override')
				?  wp_get_attachment_url(get_option('locations_maps_style_override') )
				: $fallback_map_style );
		
		// Attributes
		$atts = shortcode_atts([
			// Our Shortcode parameters
			'map_id'        => 'lm_map',
			'zoom'          => 8,
			'disable_popup' => 'false',
			'disabled_info' => 'false',
		],
			$atts
		);
		
		$atts['disable_popup'] !== 'false' ? $atts['disable_popup'] = 'disable' : null;
		$atts['disabled_info'] !== 'false' ? $atts['disabled_info'] = 'disable' : null;
		
		$map_vars = [
			'mapStyling' => $map_style,
			'mapZoom' =>  $atts['zoom'],
			'mapIcon' =>  get_option('locations_maps_map_icon') ?: plugin_dir_url(__FILE__) . 'media/pin.svg',
			'mapPopup'  =>  $atts['disable_popup'],
			'mapDisableInfoWindow'  =>  $atts['disabled_info'],
			'mapCenterLat'  =>  get_option('locations_map_center_lat') ? get_option('locations_map_center_lat') : '',
			'mapCenterLng'  =>  get_option('locations_map_center_lng') ? get_option('locations_map_center_lng') : '',
		];
		
		wp_localize_script($this->plugin_name, 'MAP_VARS', $map_vars);
		
		printf('<div id="%s" class="map-container"></div>',
			$atts['map_id']
		);
		
		wp_reset_postdata();
	}
	
}
