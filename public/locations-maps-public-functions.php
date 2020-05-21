<?php

/**
 * The public-accessible functionality of the plugin that utilizes the plugins classes.
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/public
 * @author     Nick Makris <nick@makris.io>
 */

/**
 * Returns the stored Google API key
 *
 * @return string
 */
function lm_google_api_key()
{
	$options =  get_option('lm_options');
	$get_key = isset( $options['google_api_key']) ? $options['google_api_key'] : null;
	return !is_null($get_key) ? sanitize_text_field($get_key) : '';
}

/**
 * Returns the ID of the set Map Icon
 *
 * @return false|string
 */
function lm_map_icon()
{
	$options =  get_option('lm_options');
	$map_icon = isset( $options['map_icon']) ? $options['map_icon'] : '';
	return $map_icon;
}

/**
 * Returns the URL of the saved map style
 *
 * @return string
 */
function lm_map_style()
{
	$options = get_option('lm_options');
	return isset($options['map_style']) && !in_array('other', $options['map_style']) ? $options['map_style'] : ( (isset($options['style_override']) && $options['map_style']) && $options['map_style'] === 'other' ? $options['style_override'] : '' );
}

/**
 * Returns the formated No Results entered text
 *
 * @return mixed|string|void
 */
function lm_no_results()
{
	$options =  get_option('lm_options');
	return isset($options['no_results_text']) ? apply_filters( 'the_content', $options['no_results_text'] ) : '';
}

/**
 * Returns the lat and lng values
 *
 * @return null|object
 */
function lm_main_location() {
	$loc = [];
	isset(get_option('_main_location')['lat']) ? $loc['lat'] = get_option('_main_location')['lat'] : null;
	isset(get_option('_main_location')['lng']) ? $loc['lng'] = get_option('_main_location')['lng'] : null;
	
	return $loc ? (object) $loc : null;
}
