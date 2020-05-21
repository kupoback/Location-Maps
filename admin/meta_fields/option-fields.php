<?php
$meta_fields = [
	// @TODO Integrate with the site
	/*
	[
		'uid'     => 'locations_maps_toggle_location_taxonomy',
		'title'   => 'Location Category',
		'label_for' =>  'locations_maps_toggle_location_taxonomy',
		'section' => 'locations_maps_section_one',
		'type'    => 'checkbox',
		'options' => [
			'add_button' => __('Activate Taxonomy', 'textdomain'),
		],
		'default' => [],
	],
	*/
	[
		'uid'       => 'google_api_key',
		'title'     => 'Google Maps API Key',
		'section'   => 'locations_maps_section_one',
		'type'      => 'text',
		'default'   => '',
		'helper'    => 'Please obtain an API key from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="noopener">here</a>. Click on "Get Started" when landing on the page.',
	],
	[
		'uid'       => 'google_geocode_api_key',
		'title'     => 'Google Maps GeoCode API Key',
		'section'   => 'locations_maps_section_one',
		'type'      => 'text',
		'default'   => '',
		'helper'    => 'Please obtain an API key from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="noopener">here</a>. Click on "Get Started" when landing on the page.',
	],
	[
		'uid'       =>  'no_results_text',
		'title'     =>  'No Results Text',
		'section'   =>  'locations_maps_section_two',
		'type'      =>  'wysiwyg',
		'default'   =>  'No results found.',
		'helper'    =>  ''
	],
	[
		'uid'       => 'map_icon',
		'title'     => 'Map Icon',
		'section'   => 'locations_maps_section_one',
		'type'      => 'image',
		'default'   => '',
	],
	// [
	// 	'uid'       => 'map_style',
	// 	'title'     => 'Map Style',
	// 	'section'   => 'locations_maps_section_one',
	// 	'type'      => 'select',
	// 	'options'   => [
	// 		'none'      => 'Select',
	// 		'aubergine' => 'Aubergine',
	// 		'dark'      => 'Dark',
	// 		'night'     => 'Night',
	// 		'retro'     => 'Retro',
	// 		'silver'    => 'Silver',
	// 		'other'     => 'Other',
	// 	],
	// 	'default'   => [],
	// ],
	// [
	// 	'uid'       => 'style_override',
	// 	'title'     => 'Map Style',
	// 	'section'   => 'locations_maps_section_one',
	// 	'type'      => 'file',
	// 	'helper'    => 'Please use JSON files only.',
	// 	'default'   => '',
	// ],
];

return $meta_fields;
