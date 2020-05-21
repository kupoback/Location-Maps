<?php
/**
 * File Name: lm-locations.php
 * Description:
 * Version:
 * Author: Nick Makris
 * Author URI: buildsomething@cliquestudios.com
 *
 */

?>
<div class="location" xmlns:="http://schema.org/#" typeof="v:LocalBusiness">
	<?php
	
	printf('<div class="location-hq">%s</div>', get_post_meta(get_the_ID(), '_map_department', true));
	
	printf('<div class="location-city"><h2 property="v:name">%s</h2></div>', get_the_title( ));
	
	?>
	<div class="location-address" property="v:address" typeof="v:PostalAddress">
		<?php
		
		$address = get_post_meta(get_the_ID(), '_map_address', true) ? '<span property="v:streetAddress">' . get_post_meta(get_the_ID(), '_map_address', true) . '</span><br />' : '';
		
		$city    = get_post_meta(get_the_ID(), '_map_city', true) ? '<span property="v:addressLocality">' . get_post_meta(get_the_ID(), '_map_city', true) . '</span>' : '';
		
		$state   = get_post_meta(get_the_ID(), '_map_state', true) ? (get_post_meta(get_the_ID(), '_map_city', true) ? ', ' : '') . '<span property="v:addressRegion">' . get_post_meta(get_the_ID(), '_map_state', true) . '</span>' : '';
		
		$zip     = get_post_meta(get_the_ID(), '_map_zip', true) ? (get_post_meta(get_the_ID(), '_map_city', true) || get_post_meta(get_the_ID(), '_map_state', true) ? ' ' : '') . '<span property="v:postalCode">' . get_post_meta( get_the_ID(), '_map_zip', true) . '</span>' : '';
		
		printf(
			'<address><a href="https://www.google.com/maps/search/?api=1&query=Google&query_place_id=%2$s">%1$s</a></address>',
			$address . $city . $state . $zip,
			get_post_meta(get_the_ID(), '_map_placeID', true )
			);
		?>
	</div>
	
	<?php
	printf('<div class="location-phone" property="v:telephone"><a href="tel:1%2$s">&plus; %1$s</a></div>',
	       get_post_meta(get_the_ID(), '_map_phone', true ),
	preg_replace( '/\s+/', '', preg_replace('/[^a-zA-Z0-9\']/', '', get_post_meta(get_the_ID(), '_map_phone', true) ) )
	);
	?>
</div>
