<?php

//Exit if accessed directly
if (!defined('ABSPATH'))
	exit;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

require_once plugin_dir_path(dirname(__FILE__)) . 'vendor/autoload.php';

/**
 * Class Name: Locations_Maps_API
 * Description: The API specifically for the locations map
 * Class Locations_Maps_API
 *
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/admin
 * @author     Nick Makris <nick@makris.io>
 */
class Locations_Maps_API
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
	 * The Google Request URL
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $google_api_url The URL request from Google
	 */
	private $google_api_url;
	
	/**
	 * The Google API key
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $apiKey THe API key for Google Maps
	 */
	private $apiKey;
	
	/**
	 *
	 * The address to gather data from
	 *
	 * @since       1.0.0
	 * @access      private
	 * @var string $address The address to gather data from
	 */
	private $address;
	
	/**
	 * The client call
	 *
	 * @var
	 */
	private $client;
	
	/**
	 * The error message for REST API
	 *
	 * @var array
	 */
	private $error;
	
	protected $namespace;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct($plugin_name, $version)
	{
		$options = get_option('lm_options');
		$get_key = isset($options['google_geocode_api_key']) ? $options['google_geocode_api_key'] : null;
		
		$this->namespace      = 'locations/v1';
		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->google_api_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
		$this->address        = 'address=';
		$this->apiKey         = !is_null($get_key) ? sanitize_text_field($get_key) : '';
		$this->client         = new Client(['verify' => false]);
		$this->error          = [
			"code" => "error_cannot_access",
			"message" => "The route request is inaccessible",
			"data" => [
				"status" => 404
			]
		];
	}
	
	public function register_routes()
	{
		register_rest_route($this->namespace, '/places', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => [
				$this,
				'get_locations',
			],
			'args'     => [],
		]);
		
		register_rest_route($this->namespace, '/nearby', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => [
				$this,
				'get_nearby_locations',
			],
			'args'     => [
				'zip'    => [
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_title',
					'validate_callback' => [
						$this,
						'validate_zip',
					],
				],
				'radius' => [
					'type'              => 'number',
					'sanitize_callback' => 'absint',
				],
			],
		]);
	}
	
	private function get_posts($tax_terms = [])
	{
		$args = [
			'post_type'       => 'locations',
			'posts_per_parge' => - 1,
			'post_status'     => 'publish',
			'orderby'         => 'menu_order',
			'order'           => 'ASC',
			'hide_empty'      => true,
		];
		
		if (isset($tax_terms) && !empty($tax_terms) && is_array($tax_terms))
		{
			$terms_query = [];
			foreach ($tax_terms as $tax_term)
			{
				$term_query = [
					'taxonomy' => $tax_term['tax'],
					'field'    => 'slug',
					'terms'    => $tax_term['terms'],
				];
				array_push($terms_query, $term_query);
			}
			$args['tax_query'] = $terms_query;
		}
		
		$query = get_posts($args);
		
		return $query;
	}
	
	public function get_locations()
	{
		
		$no_posts_text = get_field('no_locations_text', 'option') ?: 'No Locations Found';
		$locations     = [];
		$posts         = $this->get_posts();
		
		if (!empty($posts))
		{
			$i = 0;
			global $post;
			foreach ($posts as $post)
			{
				setup_postdata($post);
				$post_id     = get_the_ID();
				$locations[] = [
					'id'      => $post_id,
					'slug'    => $post->post_name,
					'title'   => get_the_title(),
					'address' => [
						'address'  => get_post_meta($post_id, '_map_address', true) ?: null,
						'address2' => get_post_meta($post_id, '_map_address2', true) ?: null,
						'city'     => get_post_meta($post_id, '_map_city', true) ?: null,
						'state'    => get_post_meta($post_id, '_map_state', true) ?: null,
						'zip'      => get_post_meta($post_id, '_map_zip', true) ?: null,
						'return'   => $this->address($post_id),
					],
					'phone'   => get_post_meta($post_id, '_map_phone', true) ?
						[
							'clean' => preg_replace('/\s+/', '', preg_replace('/[^a-zA-Z0-9\']/', '', get_post_meta($post_id, '_map_phone', true))),
							'text'  => get_post_meta($post_id, '_map_phone', true),
						] : '',
					'email'   => get_post_meta($post_id, '_map_email', true) ?: '',
					'website' => get_post_meta($post_id, '_map_website', true) ?: '',
					'lat'     => get_post_meta($post_id, '_map_lat', true) ?: null,
					'lng'     => get_post_meta($post_id, '_map_lng', true) ?: null,
				];
				
				$i ++;
			}
			wp_reset_postdata();
		}
		
		if (empty($locations))
		{
			$locations['no_posts_found'] = $no_posts_text;
		}
		
		return rest_ensure_response($locations);
	}
	
	public function get_nearby_locations(WP_REST_Request $request)
	{
		$locations = $this->error;
		$address   = $request->get_param('zip');
		$distance = $request->get_param('radius');
		
		if (isset($address) && $address !== '')
		{
			$locationCoords = self::get_google_location($address);
		}
		else
		{
			// Denver Office coords
			$locationCoords = (object)[
				'lat' => 39.705435,
				'lng' => - 104.937444,
			];
		}
		
		if (isset($locationCoords->lat) && isset($locationCoords->lng))
		{
			
			// The client doesn't want to filter results and so to always show all locations and just order them,
			// I setup the radius distance to 10000 miles (which should cover the US).
			$locations = $this->nearby_query($locationCoords->lat, $locationCoords->lng, $distance);
			
			if (count($locations) > 0)
			{
				$locations = array_map(
					function ($post)
					{
						return [
							'title'    => get_the_title($post['id']),
							'slug'      => get_post_field('post_name', $post['id']),
							'address'  => [
								'address'  => get_post_meta($post['id'], '_map_address', true) ?: '',
								'address2' => get_post_meta($post['id'], '_map_address2', true) ?: '',
								'city'     => get_post_meta($post['id'], '_map_city', true) ?: '',
								'state'    => get_post_meta($post['id'], '_map_state', true) ?: '',
								'zip'      => get_post_meta($post['id'], '_map_zip', true) ?: '',
								'return'   => $this->address($post['id']),
							],
							'phone'    => get_post_meta($post['id'], '_map_phone', true) ?
								[
									'clean' => preg_replace('/\s+/', '', preg_replace('/[^a-zA-Z0-9\']/', '', get_post_meta($post['id'], '_map_phone', true))),
									'text'  => get_post_meta($post['id'], '_map_phone', true),
								] : '',
							'website'  => get_post_meta($post['id'], '_map_website', true) ?: '',
							'lat'      => get_post_meta($post['id'], '_map_lat', true) ? (float) get_post_meta($post['id'], '_map_lat', true) : '',
							'lng'      => get_post_meta($post['id'], '_map_lng', true) ? (float) get_post_meta($post['id'], '_map_lng', true) : '',
							'distance' => (float) number_format((float) $post['distance'], 2),
						];
					},
					$locations
				);
			}
		}
		
		echo json_encode( [
			'locations' => $locations,
			'center'    => $locationCoords
			]);
		
		exit;
	}
	
	private function nearby_query($lat, $lng, $distance = 1, $unit = 'mi')
	{
		global $wpdb;
		
		// radius of earth; @note: the earth is not perfectly spherical, but this is considered the 'mean radius'
		if ($unit === 'km')
			$radius = 6371.009; // in kilometers
		else
			$radius = 3958.761; // in miles
		
		// get results ordered by distance (approx)
		$query = $wpdb->prepare(
			"
        SELECT DISTINCT
            p.ID as id,
            p.post_title as title,
            lat.meta_value as lat,
            lon.meta_value as lon,
            ( %d * acos(
            cos( radians( %s ) )
            * cos( radians( lat.meta_value ) )
            * cos( radians( lon.meta_value ) - radians( %s ) )
            + sin( radians( %s ) )
            * sin( radians( lat.meta_value ) )
            ) )
            AS distance
        FROM $wpdb->posts p
        INNER JOIN $wpdb->postmeta lat ON p.ID = lat.post_id
        INNER JOIN $wpdb->postmeta lon ON p.ID = lon.post_id
        WHERE 1 = 1
        AND p.post_type = 'locations'
        AND p.post_status = 'publish'
        AND lat.meta_key = '_map_lat'
        AND lon.meta_key = '_map_lng'
        HAVING distance < %s
        ORDER BY distance ASC",
			$radius,
			$lat,
			$lng,
			$lat,
			$distance
		);
		
		return ($wpdb->get_results($query, ARRAY_A));
	}
	
	private function get_google_location($address)
	{
		$coords = [];
		
		if (!empty($address))
		{
			try
			{
				$response = $this->client->request('GET', $this->google_api_url . urlencode($address) . '&key=' . $this->apiKey);
				
				if ($response->getStatusCode() == '200')
				{
					$body = (string) $response->getBody();
					$body = json_decode($body);
					
					if (!property_exists($body, 'error_message') && isset($body->results) && count($body->results) > 0)
					{
						
						$lat = $body->results[0]->geometry->location->lat ?: null;
						$lng = $body->results[0]->geometry->location->lng ?: null;
						
						$coords = (object) [
							'lat' => !is_null($lat) ? (float) $lat : null,
							'lng' => !is_null($lng) ? (float) $lng : null,
						];
					}
					else
					{
						throw new \Exception('Google Map API - ' . $body->error_message);
					}
				}
			}
			catch (RequestException $e)
			{
			}
		}
		else {
			$coords = $this->error;
		}
		
		return $coords;
	}
	
	public function validate_zip($value)
	{
		
		if (!is_string($value) || !\preg_match('/^[0-9]{5}(?:-[0-9]{4})?$/', $value))
		{
			return new \WP_Error('rest_invalid_param', esc_html__('Must be a valid zip code.', 'united-way-theme'), ['status' => 400]);
		}
		
		return $value;
	}
	
	private function address($loc_id)
	{
		
		if (!$loc_id || get_post_type($loc_id) !== 'locations')
			return;
		
		$address = get_post_meta($loc_id, '_map_address', true) ? '<span property="v:streetAddress">' . get_post_meta($loc_id, '_map_address', true) . (get_post_meta($loc_id, '_map_address2', true) ? '<span class="address2">' . get_post_meta($loc_id, '_map_address2', true) . '</span>' : null) . '</span><br />' : '';
		$city    = get_post_meta($loc_id, '_map_city', true) ? '<span property="v:addressLocality">' . get_post_meta($loc_id, '_map_city', true) . '</span>' : '';
		$state   = get_post_meta($loc_id, '_map_state', true) ? (get_post_meta($loc_id, '_map_city', true) ? ', ' : null) . '<span property="v:addressRegion">' . get_post_meta($loc_id, '_map_state', true) . '</span>' : '';
		$zip     = get_post_meta($loc_id, '_map_zip', true) ? (get_post_meta($loc_id, '_map_city', true) || get_post_meta($loc_id, '_map_state', true) ? ' ' : null) . '<span property="v:postalCode">' . get_post_meta($loc_id, '_map_zip', true) . '</span>' : '';
		
		return sprintf(
			'<address>%s</address>',
			$address . $city . $state . $zip
		);
	}
	
}
