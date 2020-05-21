<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makris.io
 * @since             1.0.0
 * @package           Locations_Maps
 *
 * @wordpress-plugin
 * Plugin Name:       Locations and Maps
 * Plugin URI:        #
 * Description:       This plugin incorporates a Google Map and integrates a [map] shortcode, as well as grabs the geolocation of a location when an address is inputted.
 * Version:           1.1.0
 * Author:            Nick Makris
 * Author URI:        https://makris.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       locations-maps
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LOCATIONS_MAPS_VERSION', '1.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-locations-maps-activator.php
 */
function activate_locations_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-locations-maps-activator.php';
	Locations_Maps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-locations-maps-deactivator.php
 */
function deactivate_locations_maps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-locations-maps-deactivator.php';
	Locations_Maps_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_locations_maps' );
register_deactivation_hook( __FILE__, 'deactivate_locations_maps' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-locations-maps.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_locations_maps() {

	$plugin = new Locations_Maps();
	$plugin->run();

}
run_locations_maps();
