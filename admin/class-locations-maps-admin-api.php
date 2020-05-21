<?php

//Exit if accessed directly
if (!defined('ABSPATH'))
	exit;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
require_once plugin_dir_path(dirname(__FILE__)) . 'vendor/autoload.php';

/**
 * Class Name: Locations_Maps_Admin_API
 * Description: The API specifically for the locations map getlocate
 *
 * @link       https://makris.io
 * @since      1.0.0
 *
 * @package    Locations_Maps
 * @subpackage Locations_Maps/admin
 * @author     Nick Makris <nick@makris.io>
 */
class Locations_Maps_Admin_API
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
		$options =  get_option('lm_options');
		$get_key = isset( $options['google_geocode_api_key']) ? $options['google_geocode_api_key'] : null;
		
		$this->namespace      = 'locations/v1';
		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->google_api_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
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
	
	/**
	 * Registration of our API endpoint
	 */
	public function register_routes()
	{
		
		register_rest_route($this->namespace, '/place', [
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => [$this, 'get_google_location',],
			'args'                => [
				'address' => [
					'type'              => 'array',
					'sanitize_callback' => [$this, 'sanitize_address'],
				],
			]
		]);
	}
	
	/**
	 * Grabbing the latitude and longitude of the API
	 *
	 * @param WP_REST_Request $response
	 *
	 * @return mixed|WP_REST_Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function get_google_location(WP_REST_Request $response)
	{
		$coords = [];
		$param  = $response->get_param('address');
		
		if (!empty($param))
		{
			try
			{
				$address  = implode('+', $param);
				$response = $this->client->request('GET', $this->google_api_url . \urlencode($address) . '&key=' . $this->apiKey);
				
				if ($response->getStatusCode() == '200')
				{
					$body = (string) $response->getBody();
					$body = \json_decode($body);
					
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
		
		return rest_ensure_response($coords);
	}
	
	/**
	 * String replace the address field spaces
	 *
	 * @param $address
	 *
	 * @return array
	 */
	public function sanitize_address($address)
	{
		return array_map(function ($field)
		{
			return !empty($field) ? str_replace(' ', '+', $field) : null;
		}, $address);
	}
	
}
