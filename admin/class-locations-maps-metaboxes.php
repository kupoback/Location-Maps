<?php
/**
 * File: class-locations-maps-metaboxes.php
 * Description:
 * Version: 1.0
 * Author: Nick Makris @kupoback
 * Author URI: https://makris.io
 *
 * @package mdhpartners
 */

/**
 * Check if our function is defined before this file is called.
 */
class Locations_Maps_Metaboxes
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
	 * Function Name: locations_maps_metabox
	 * Description: Define the metabox
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 */
	function locations_maps_metabox()
	{
		
		$screens = ['locations', 'location-maps-settings'];
		foreach ($screens as $screen)
		{
			if ($screen === 'locations')
			{
				add_meta_box('locations_maps_metabox',
					'Location Information',
					[
						$this,
						'locations_maps_metabox_html',
					],
					$screen,
					'advanced',
					'high'
				);
			}
			
		}
	}
	
	/**
	 * Function Name: locations_maps_metabox_html
	 * Description: Create the metabox content. Includes a switch, state list, and loop to build out fields quickly.
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 * @param $post
	 *
	 */
	function locations_maps_metabox_html($post)
	{
		
		if (get_post_type($post->ID) != 'locations')
			return;
		
		// State Array
		$states = [
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District of Colombia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'PR' => 'Puerto Rico',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming',
		];
		$fields = [
			[
				'euid'  => 'address',
				'value' => '_map_address',
				'title' => 'Address',
				'type'  => 'text',
				'class' => 'one-half',
			],
			[
				'euid'  => 'city',
				'value' => '_map_city',
				'title' => 'City',
				'type'  => 'text',
				'class' => 'one-half',
			],
			[
				'euid'    => 'state',
				'value'   => '_map_state',
				'title'   => 'State',
				'type'    => 'select',
				'choices' => $states,
				'class'   => 'one-quarter',
			],
			[
				'euid'  => 'zip',
				'value' => '_map_zip',
				'title' => 'Zip code',
				'type'  => 'number',
				'class' => 'one-quarter',
			],
			[
				'euid'  => 'country',
				'value' => '_map_country',
				'title' => 'Country',
				'type'  => 'text',
				'class' => 'one-half',
			],
			[
				'euid'  => 'hours',
				'value' => '_map_hours',
				'title' => 'Hours of Operation',
				'type'  => 'text',
				'class' => 'one-half',
			],
			[
				'euid'  => 'phone',
				'value' => '_map_phone',
				'title' => 'Phone Number',
				'type'  => 'text',
				'class' => 'one-half',
			],
			[
				'euid'  => 'lat_text',
				'value' => '_map_lat',
				'title' => 'Latitude',
				'type'  => 'text',
				'class' => 'one-half readonly',
			],
			[
				'euid'  => 'lng_text',
				'value' => '_map_lng',
				'title' => 'Longitude',
				'type'  => 'text',
				'class' => 'one-half readonly',
			],
			[
				'euid'  => 'lat',
				'value' => '_map_lat',
				'title' => 'Latitude',
				'type'  => 'hidden',
			],
			[
				'euid'  => 'lng',
				'value' => '_map_lng',
				'title' => 'Longitude',
				'type'  => 'hidden',
			],
			[
				'euid'  => 'placeID',
				'value' => '_map_placeID',
				'title' => 'Place ID',
				'type'  => 'hidden',
			],
		];
		
		wp_nonce_field('locations_maps_nonce', 'locations_maps_nonce'); ?>

		<div class="map-elements-inside" id="map-elements">

			<div id="map-loading">
				<svg enable-background="new 0 0 100 100" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m31.6 3.5c-25.7 10.1-38.2 39.2-28.1 64.9s39.2 38.3 64.9 28.1l-3.1-7.9c-21.3 8.4-45.4-2-53.8-23.3s2-45.4 23.3-53.8l-3.2-8z" fill="#397DA2">
						<animateTransform attributeName="transform" attributeType="XML" dur="2s" from="0 50 50" repeatCount="indefinite" to="360 50 50" type="rotate" />
					</path>
					<path d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5 L82,35.7z" fill="#54AEC7">
						<animateTransform attributeName="transform" attributeType="XML" dur="2s" from="0 50 50" repeatCount="indefinite" to="360 50 50" type="rotate" />
					</path>
					<path d="m42.3 39.6c5.7-4.3 13.9-3.1 18.1 2.7 4.3 5.7 3.1 13.9-2.7 18.1l4.1 5.5c8.8-6.5 10.6-19 4.1-27.7-6.5-8.8-19-10.6-27.7-4.1l4.1 5.5z" fill="#59C29F">
						<animateTransform attributeName="transform" attributeType="XML" dur="1s" from="0 50 50" repeatCount="indefinite" to="-360 50 50" type="rotate" />
					</path></svg>
			</div>

			<div class="map-row">
				
				<?php
				
				$hidden = [];
				foreach ($fields as $field)
				{
					$markup = '';
					$value  = get_post_meta($post->ID, $field['value'], true);
					
					$type  = $field['type'];
					$fid   = $field['euid'];
					$title = $field['title'];
					$class = isset($field['class']) ? $field['class'] : '';
					$val   = $field['value'];
					
					switch ($field['type'])
					{
						case 'text' :
						case 'number' :
							if (!$value)
							{
								$value = '';
							}
							$atts = '';
							if ($fid === 'lat_text' || $fid === 'lng_text')
							{
								$atts = 'readonly';
								$val  = $field['euid'];
							}
							
							$markup = sprintf('<input type="%1$s" name="%2$s" id="%2$s" value="%3$s" %4$s />', $type, $val, $value, $atts);
							break;
						case 'select' :
							$options = '';
							if ($value === '')
							{
								$value = '';
							}
							$options .= sprintf('<option value="">Select</option>');
							foreach ($field['choices'] as $key => $label) :
								$options .= sprintf('<option value="%s" %s>%s</option>',
									$key,
									selected($value, $key, false),
									__($label, 'fdiv')
								);
							endforeach;
							$markup .= sprintf('<select name="%1$s" id="%1$s">%2$s</select>', $field['value'], $options);
							break;
						case 'hidden' :
							$hidden_input = sprintf('<input type="%1$s" name="%2$s" id="%2$s" value="%3$s" />', $type, $field['value'], $value);
							array_push($hidden, $hidden_input);
							break;
					} ?>
					
					<?php if ($type !== 'hidden') : ?>
					<div class="map-field map-<?php echo $fid . ' ' . $class; ?>">

						<div class="map-label">
							<label for="<?php echo $fid; ?>"><?php _e($title, 'fdiv'); ?></label>
						</div>

						<div class="map-<?php echo $type; ?>">
							<?php echo $markup; ?>
						</div>

					</div>
				
				<?php
				endif;
				} ?>

			</div>

			<div class="map-row">
				<div class="map-field map-get-geolocation">
					<div class="map-geo-button">
						<input name="_save_address" type="button" data-postid="<?php echo $post->ID; ?>" class="btn" id="save_address" value="<?php _e('Save Address', 'fdiv'); ?>">
						<input name="_get_geoloc" type="button" data-postid="<?php echo $post->ID; ?>" class="btn" id="get_geoloc" value="<?php _e('Get Location', 'fdiv'); ?>">
						<input type="button" name="geo_reset" class="btn" id="geo_reset" value="<?php _e('Reset Geo Location', 'fdiv'); ?>" />
						<input type="button" name="form-reset" class="btn" id="form-reset" value="<?php _e('Form Reset', 'fdiv'); ?>" />
					</div>
					<input type="hidden" id="map_api_key" value="<?php echo get_option('locations_maps_google_api_key'); ?>" />
					<?php foreach ($hidden as $hid)
					{
						echo $hid;
					} ?>
				</div>
			</div>

		</div>
		
		<?php
		
	}
	
	/**
	 * Function Name: locations_maps_metabox_save
	 * Description: The function we will use to save the metabox
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 * @param $post_id
	 *
	 */
	function locations_maps_metabox_save($post_id)
	{
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;
		if (!isset($_POST['locations_maps_nonce']) || !wp_verify_nonce($_POST['locations_maps_nonce'], 'locations_maps_nonce'))
			return;
		if (!current_user_can('edit_post', $post_id))
			return;
		
		$fields = [
			'_map_address',
			'_map_city',
			'_map_state',
			'_map_zip',
			'_map_country',
			'_map_lat',
			'_map_lng',
			'_map_placeID',
			'_map_hours',
			'_map_phone'
		];
		
		foreach ($fields as $field)
		{
			if ($_POST[$field])
				update_post_meta($post_id, $field, $_POST[$field]);
			else
				delete_post_meta($post_id, $field);
		}
		
		// We need to run through the other posts and uncheck the box of the others if they are checked.
		$args = [
			'post_type' =>  'locations',
			'per_page'  =>  -1,
			'post__not_in'  =>  [$post_id]
		];
		$post_objects = get_posts($args);
		foreach ($post_objects as $post)
		{
			setup_postdata($post);
			if ( !empty(get_post_meta($post->ID, '_center_location', true)) && isset($_POST['_center_location']) )
				delete_post_meta($post->ID, '_center_location');
		}
		wp_reset_postdata();
		
		if ( isset($_POST['_center_location']))
		{
			update_post_meta($post_id, '_center_location', $_POST['_center_location']);
			
			if (get_option('locations_map_center_lat') !== get_post_meta($post_id, '_map_lat', true))
			{
				update_option('locations_map_center_lat', $_POST['_map_lat']);
				update_option('locations_map_center_lng', $_POST['_map_lng']);
			}
			
		}
		else
		{
			delete_post_meta($post_id, '_center_location');
			
			if ( get_option('locations_map_center_lat') === get_post_meta($post_id, '_map_lat', true))
			{
				delete_option('locations_map_center_lat');
				delete_option('locations_map_center_lng');
			}
		}
		
	}
	
	/**
	 * Function Name: locations_maps_metabox_ajax
	 * Description: The function that triggers an auto save when lat/lng are populated/removed
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 *
	 */
	function locations_maps_metabox_ajax()
	{
		
		$id      = $_POST['id'];
		$lat     = sanitize_text_field($_POST['lat']);
		$lng     = sanitize_text_field($_POST['lng']);
		$address = sanitize_text_field($_POST['address']);
		$city    = sanitize_text_field($_POST['city']);
		$state   = sanitize_text_field($_POST['state']);
		$zip     = sanitize_text_field($_POST['zip']);
		$hours     = sanitize_text_field($_POST['hours']);
		$phone     = sanitize_text_field($_POST['phone']);
		$placeID = sanitize_text_field($_POST['placeID']);
		$country = sanitize_text_field($_POST['country']);
		
		update_post_meta($id, '_map_lat', $lat);
		update_post_meta($id, '_map_lng', $lng);
		update_post_meta($id, '_map_placeID', $placeID);
		update_post_meta($id, '_map_address', $address);
		update_post_meta($id, '_map_city', $city);
		update_post_meta($id, '_map_state', $state);
		update_post_meta($id, '_map_zip', $zip);
		update_post_meta($id, '_map_country', $country);
		update_post_meta($id, '_map_hours', $hours);
		update_post_meta($id, '_map_phone', $phone);
		
	}
	
	/**
	 * Function Name: locations_maps_scripts
	 * Description: The map scripts to add
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 *
	 */
	function locations_maps_scripts()
	{
	
	}
	
	/**
	 * Function Name: locations_maps_add_custom_fields
	 * Description: Register a new fieldset in the REST API
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 *
	 */
	function locations_maps_add_custom_fields()
	{
		
		register_rest_field('locations',
			'map_fields', //New Field Name in JSON RESPONSEs
			[
				'get_callback'    => [
					$this,
					'locations_maps_custom_fields', // custom function name
				],
				'update_callback' => null,
				'schema'          => null,
			]
		);
	}
	
	/**
	 * Function Name: locations_maps_custom_fields
	 * Description: Our REST callback
	 * Version: 1.0
	 * Author: Nick Makris @kupoback
	 *
	 * @param $object
	 *
	 * @return mixed
	 *
	 */
	function locations_maps_custom_fields($object)
	{
		
		$data = [];
		
		$fields = [
			'_map_address',
			'_map_city',
			'_map_state',
			'_map_zip',
			'_map_country',
			'_map_lat',
			'_map_lng',
			'_map_lng',
			'_map_placeID',
			'_map_hours',
			'_map_phone'
		];
		
		foreach ($fields as $field)
		{
			$data[$field] = (object) get_post_meta($object['id'], $field);
		}
		
		//		return $data;
		
		return get_post_meta($object['id']);
		
	}
	
	function locations_maps_update_box_custom_fields()
	{
		
		$post_id = get_the_ID();
		
		if (get_post_type($post_id) !== 'locations')
			return;
		
		$custom_fields = [
			[
				'id'    => '_center_location',
				'title' => 'Center Map Point (Choose 1)',
			],
		];
		
		wp_nonce_field('locations_maps_nonce_' . $post_id, 'locations_maps');
		foreach ($custom_fields as $cf)
		{
			$post_item = get_post_meta($post_id, $cf['id'], true);
			printf('<div class="misc-pub-section"><input type="checkbox" value="1" %3$s name="%1$s" id="%1$s" /><label for="%1$s">%2$s</label></div>',
				$cf['id'],
				__($cf['title'], 'locations-maps'),
				checked($post_item, true, false)
			);
		}
		
	}
	
}