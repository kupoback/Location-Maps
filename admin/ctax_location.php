<?php
if(!defined('ABSPATH')){
	exit;//Exit if accessed directly
}

/**
 * File: ctax_map_category.php
 * Description:
 * Version:
 * Author: Nick Makris @kupoback
 * Author URI: https://makris.io
 *
 * @package
 */

class locations_maps_custom_taxonomy
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
	
	public function maps_module_category_taxonomy() {
		
		$name   = 'Location Categor';
		$plural = $name . 'ies';
		$name   = $name . 'y';
		
		$labels = array(
			'name'                       => _x( $name, $name . ' General Name', 'text_domain' ),
			'singular_name'              => _x( $name, $name . ' Singular Name', 'text_domain' ),
			'menu_name'                  => __( $plural, 'text_domain' ),
			'all_items'                  => __( 'All ' , $plural, 'text_domain' ),
			'parent_item'                => __( 'Parent ' . $name, 'text_domain' ),
			'parent_item_colon'          => __( 'Parent ' . $name . ':', 'text_domain' ),
			'new_item_name'              => __( 'New ' . $name . ' Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New ' . $name, 'text_domain' ),
			'edit_item'                  => __( 'Edit ' . $name, 'text_domain' ),
			'update_item'                => __( 'Update ' . $name, 'text_domain' ),
			'view_item'                  => __( 'View ' . $name, 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate companiess with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove companies', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular ' . $name, 'text_domain' ),
			'search_items'               => __( 'Search ' . $name, 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No Companies', 'text_domain' ),
			'items_list'                 => __( $name . ' list', 'text_domain' ),
			'items_list_navigation'      => __( $name . ' list navigation', 'text_domain' ),
		);
		
		$args = array(
			'labels'                => $labels,
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => false,
			'rewrite'               => false,
			'show_in_rest'          =>  true,
			'rest_base'             =>  'map-cat',
			'rest_controller_class' =>  'WP_REST_Terms_Controller'
		);
		
		register_taxonomy( 'map_category', array( 'portfolio' ), $args );
		
	}
	
	public function maps_module_category_terms() {
		
		// Set the Taxonomy slug
		$taxonomy = 'map_category';
		
		// Create each of the premade terms we're to insert
		$terms = array (
			array (
				'name'        =>  'Home',
				'slug'        =>  'home',
				'description' =>  'This is the center marker on the map'
			),
		);
		
		if ( empty( $terms ) ) return;
		
		if ( get_option( "initial_map_cats_created" ) ) return;
		
		// Run through the loop
		foreach ( $terms as $term_key => $term ) {
			
			// Using wp_insert_term we're default setting the description, the slug and the name
			$n_term = wp_insert_term(
				$term['name'],
				$taxonomy,
				array (
					'description' =>  $term['description'],
					'slug'        =>  $term['slug']
				)
			);
			
		}
		
		unset($term);
		
		update_option( "initial_map_cats_created", 1 );
		
	}
	
	public function maps_module_add_default_posts() {
		
		// Setup the Post Type and the Taxonomy if you're using it
		$post_type = 'portfolio';
		$taxonomy = 'map_category';
		
		// Set up the pages that we want to be included by default
		$pages = array (
			array (
				'page_name' =>  'Home',
				'cat'       =>  'home'
			)
		);
		
		// If pages is empty, let's stop
		if ( empty( $pages ) ) return;
		
		// If this function has already fired, let's stop
		if ( get_option( "initial_map_post_created" ) ) return;
		
		
		foreach ( $pages as $idp => $page ) {
			
			// Empty Term and Term Array in case there are no cat names
			$term = '';
			$term_array = array();
			
			$page_name = $page['page_name'];
			$cat_name  = $page['cat'];
			
			// Check if there's a cat name set up
			if ( $cat_name )
				$term = get_term_by( 'slug', $cat_name, $taxonomy );
			
			// If there are terms, lets add the term_id to the array under the ID name
			if ( !empty($term ) )
				$term_array['ID'] = $term->term_id;
			
			// The start of creating pages
			$parent_id = wp_insert_post(
				array (
					'post_title'  =>  $page_name,
					'post_type'   =>  $post_type,
					'post_status' =>  'publish',
				)
			);
			
			// Since we're using a custom taxonomy, we'll need to force the object terms on
			// the page through the wp_set_object_terms instead of 'tax_input' in the
			// wp_insert_post() array arguments
			wp_set_object_terms( $parent_id, $term_array, $taxonomy );
			
		}
		
		unset( $page );
		
		// Let's set up an option that this checks at the start
		update_option( "initial_map_post_created", 1 );
		
	}
	
	
}
//add_action( 'init', 'maps_module_category_taxonomy' );
//add_action( 'init', 'maps_module_category_terms' );
//add_action( 'init', 'maps_module_add_default_posts', 10 );