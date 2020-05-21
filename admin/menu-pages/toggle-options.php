<?php
/**
 * File: toggle-options.php
 * Description:
 * Version: 1.0
 * Author: Nick Makris - Clique Studios
 * Author URI: buildsomething@cliquestudios.com
 *
 * @package Locations_Maps
 */

if ( !current_user_can( 'manage_options' ) )
	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] )
	admin_notice();

function admin_notice() { ?>
	<div class="notice notice-success is-dismissible">
		<p>Your settings have been updated!</p>
	</div>
<?php }


settings_errors( 'location_maps_messages' );
?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<form action="options.php" method="post" class="locations-map-settings">
		
		<?php
		settings_fields( 'locations_maps_fields' );
		do_settings_sections( 'locations_maps_fields' );
		submit_button(); ?>
	
	</form>
</div>