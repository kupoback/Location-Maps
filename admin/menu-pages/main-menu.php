<?php
/**
 * File: main-menu.php
 * Description:
 * Version: 1.0
 * Author: Nick Makris - Clique Studios
 * Author URI: buildsomething@cliquestudios.com
 *
 * @package saws
 */
if ( !current_user_can('manage_options') ) wp_die( __('You do not have sufficient permissions to access this page.' ) );