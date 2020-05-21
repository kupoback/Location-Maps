<?php
/**
 * File: class-lm-template-loader.php
 * Description:
 * Version:
 * Author: Nick Makris @kupoback
 * Author URI: https://makris.io
 *
 * @package
 */


class LM_Template_Loader extends Gamajo_Template_Loader {
	
	protected $filter_prefix = 'lm';
	
	protected $theme_template_directory = 'lm-templates';
	
	protected $plugin_directory = LOCATIONS_MAPS_PLUGIN_PATH;
	
	protected $plugin_template_directory = 'public/lm-templates';
	
}