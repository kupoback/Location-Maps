<?php
//Exit if accessed directly
if (!defined('ABSPATH')) { exit; }

/**
 * File: class-locations-maps-custom-post-type.php
 * Description: Our Custom Post Type for locations
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/admin
 * @author     Nick Makris <nick@makris.io>
 */

class Locations_Maps_Custom_Post_Type
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
	 * Function Name: custom_post_type_locations
	 * Description: Create a custom post type named locations
	 * Version: 1.0
	 * Author: Nick Makris - Clique Studios
	 * Author URI: buildsomething@cliquestudios.com
	 *
	 * @package Locations_Maps
	 *
	 */
	public function custom_post_type_locations() {
		
		$labels = array(
			'name'                  => _x( 'Locations', 'Post Type General Name', 'locations-maps' ),
			'singular_name'         => _x( 'Locations', 'Post Type Singular Name', 'locations-maps' ),
			'menu_name'             => __( 'Map', 'locations-maps' ),
			'name_admin_bar'        => __( 'Locations', 'locations-maps' ),
			'archives'              => __( 'Locations', 'locations-maps' ),
			'attributes'            => __( 'Location Attributes', 'locations-maps' ),
			'parent_item_colon'     => __( 'Parent Location:', 'locations-maps' ),
			'all_items'             => __( 'All Locations', 'locations-maps' ),
			'add_new_item'          => __( 'Add New Location', 'locations-maps' ),
			'add_new'               => __( 'Add New Location', 'locations-maps' ),
			'new_item'              => __( 'New Location', 'locations-maps' ),
			'edit_item'             => __( 'Edit Location', 'locations-maps' ),
			'update_item'           => __( 'Update Location', 'locations-maps' ),
			'view_item'             => __( 'View Location', 'locations-maps' ),
			'view_items'            => __( 'View Locations', 'locations-maps' ),
			'search_items'          => __( 'Search Locations', 'locations-maps' ),
			'not_found'             => __( 'Not found', 'locations-maps' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'locations-maps' ),
			'featured_image'        => __( 'Featured Image', 'locations-maps' ),
			'set_featured_image'    => __( 'Set featured image', 'locations-maps' ),
			'remove_featured_image' => __( 'Remove featured image', 'locations-maps' ),
			'use_featured_image'    => __( 'Use as featured image', 'locations-maps' ),
			'insert_into_item'      => __( 'Place into location', 'locations-maps' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Location', 'locations-maps' ),
			'items_list'            => __( 'Location list', 'locations-maps' ),
			'items_list_navigation' => __( 'Location list navigation', 'locations-maps' ),
			'filter_items_list'     => __( 'Filter Location list', 'locations-maps' ),
		);
		$args = array(
			'label'                 => __( 'Locations', 'locations-maps' ),
			'description'           => __( 'This post type controls is used to define locations and show them on a map..', 'locations-maps' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'custom-fields' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 25,
			'menu_icon'             => 'dashicons-building',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          =>  true,
			'rest_base'             =>  'locations',
			'rest_controller_class' =>  'WP_REST_Posts_Controller',
		);
		register_post_type( 'locations', $args );
		
	}
	
}
