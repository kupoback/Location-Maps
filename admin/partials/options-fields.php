<?php
/**
 * File: options-fields.php
 * Description:
 * Version: 1.0
 * Author: Nick Makris - Clique Studios
 * Author URI: buildsomething@cliquestudios.com
 *
 * @link    Tutorial https://github.com/rayman813/smashing-custom-fields/blob/master/smashing-fields-approach-1/smashing-fields.php
 *
 * @package Locations_Maps
 */

$option = get_option('lm_options');
$entry_value = isset($args['uid']) ? $args['uid'] : $args['default'];
$value = isset($option[$entry_value]) ? $option[$entry_value] : $args['default'];
$option_name = 'lm_options';

$placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
$helper      = isset($args['helper']) ? $args['helper'] : '';

switch ($args['type'])
{
	case 'checkbox':
		if (!empty ($args['options']) && is_array($args['options']))
		{
			$options_markup = '';
			$i              = 0;
			foreach ($args['options'] as $key => $label)
			{
				// $i ++;
				$options_markup .= sprintf(
					'<label for="%1$s"><input id="%1$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
					"{$option_name}[{$args['uid']}]",
					$args['type'],
					$key,
					isset($value[0]) ? checked($value[array_search($key, $value, true)], $key, false) : '',
					$label
				// $i
				);
			}
			printf('<fieldset>%s</fieldset>', $options_markup);
		}
		break;
	case 'text':
	case 'password':
	case 'number':
		printf(
			'<input class="regular-text" name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" autocomplete="off" aria-label="%5$s" />',
			"{$option_name}[{$args['uid']}]",
			$args['type'],
			$placeholder,
			$value,
			$args['title']
		);
		break;
	case 'textarea':
		printf('<textarea class="regular-text" name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>',
		       "{$option_name}[{$args['uid']}]",
		       $placeholder,
		       $value
		);
		break;
	case 'select':
	case 'multiselect':
		if (!empty ($args['options']) && is_array($args['options']))
		{
			$attributes     = '';
			$options_markup = '';
			foreach ($args['options'] as $key => $label)
			{
				$options_markup .= sprintf(
					'<option value="%s" %s>%s</option>',
					$key,
			isset($value[0]) ? selected($value[array_search($key, $value, true)], $key, false) : '',
					$label
				);
			}
			if ($args['type'] === 'multiselect')
				$attributes = ' multiple="multiple" ';
			
			printf(
				'<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>',
				"{$option_name}[{$args['uid']}]",
				$attributes,
				$options_markup
			);
		}
		break;
	case 'wysiwyg' :
	  wp_editor( $value, $args['uid'], $settings = ['textarea_name' => "{$option_name}[{$args['uid']}]", 'textarea_rows'=> '10', 'media_buttons' => false] );
	  break;
	case 'file' :
	case 'image' :
		$src = $title = '';
		if (!empty($value))
		{
			$image_attributes = wp_get_attachment_image_src($value);
			$src              = is_array($image_attributes) && !empty($image_attributes) ? $image_attributes[0] : null;
			$title            = get_the_title($value);
		}
		$hidden = sprintf(
			'<input type="hidden" name="%s" value="%s" data-name="hidden-media" />',
			"{$option_name}[{$args['uid']}]",
				$value
		);
		$hover = sprintf(
			'<div class="locations-map-hover hide"><p id="lm-edit" class="lm-icon -change" data-name="edit" title="%s"></p><p id="lm-remove" class="lm-icon -remove" data-name="remove" title="%s"></p></div>',
				__('Change'),
				__('Remove')
		);
		$has_value = sprintf(
			'<div class="has-value media-wrap hide %s-type">%s</div>',
				$args['type'],
				$args['type'] === 'image'
					? '<span class="image"><img src="' . $src . '" data-src="' . $src . '" data-name="media" alt="Selected Image" /></span>'
					: ( $args['type'] === 'file'
								? '<p class="file-name" data-name="media"><b>' . __('File Name') . '</b>: <span>' . $title . '</span></p>'
								: null )
		);
		$no_value = sprintf(
			'<div class="no-value hide">%s</div>',
				$args['type'] === 'image'
						? '<p>' . __('No image selected') . '</p><p><input type="button" id="locations_map-media-upload" class="button button-primary" value="Select Image" data-name="add"></p>'
						: ( $args['type'] === 'file'
									? '<p>' . __('No file selected', 'locations-maps') . '</p><p><input type="button" id="locations_map-media-upload" class="button button-primary" value="Select File" data-name="add"></p>'
									: null )
		);
		printf(
			'<div class="lm-media-container">%s</div>',
	  $hidden . $has_value .$hover .$no_value
		);
		break;
}

if ($helper)
	printf('<span class="helper"> %s</span>', $helper);
//
//		if( $supplimental = $args['supplimental'] )
//			printf( '<p class="description">%s</p>', $supplimental );
