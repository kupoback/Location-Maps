/**
 * Function Name tooltipBuild
 * Description: This houses the markup for our tooltip
 * Author: Nick Makris @kupoback
 * AuthorURI: https://makris.io
 *
 * @param elm
 * @returns {string}
 *
 */

/* global MAP_VARS */

function tooltipBuild (elm) {
	"use strict";

	var title = elm.title,
	    address = elm.address,
	    link  = elm.url;

	// console.log(elm);

	return "<div class=\"marker-infowindow\">" +
		"<div class=\"title\">" + title + "</div>" +
		placeAddress(address) +
		"<div class=\"link\"><a href=\"" + link + "\" target=\"_blank\" rel=\"noopener\">View on Google Maps</a></div>" +
		"</div>";
}

function placeAddress (elm) {
	"use strict";

	var address = elm.address,
	    city    = elm.city,
	    state   = elm.state,
	    zip     = elm.zip,
	    country = elm.country;

	return "<div class=\"address\">" +
		"<p>" + address + "</p>" +
		"<p>" + city + " " + state + " " + zip + "</p>" +
		"<p>" + country + "</p>" +
		"</div>";

}

function CustomMarker(latlng, map) {
  this.latlng = latlng;
  this.setMap(map);
}

var map;
var mapElement = jQuery('.map-container').attr('id');
var postsURL = '/wp-json/wp/v2/locations?per_page=100';
var zoom = MAP_VARS.mapZoom || 8;
var icon = MAP_VARS.mapIcon;
var hoverIcon = MAP_VARS.mapHoverIcon || '';
var style = MAP_VARS.mapStyling;
var single = typeof MAP_VARS.post_id ? MAP_VARS.post_id : 'no';
var defaultIcon = MAP_VARS.mapIcon;
var popup = MAP_VARS.mapPopup || '';
var prev_infowindow = false;
var label = 1;
var markers = [];
var markerArray = [];
var count;
var items;
var noInfoWindow = MAP_VARS.mapDisableInfowindow || 'yes';
var center;

function initMap () {
	"use strict";

	if ( jQuery.isNumeric(single) )
		postsURL = "/wp-json/wp/v2/locations/" + single;

	center = new google.maps.LatLng( MAP_VARS.mapCenterLat, MAP_VARS.mapCenterLng );

	map = new google.maps.Map( document.getElementById( mapElement ), {
		zoom:              parseInt( zoom ),
		center:            center,
		disableDefaultUI:  true,
		streetViewControl: false,
		mapTypeControl:    false,
    draggable: false,
    zoomControl: false,
    scrollwheel: false,
    disableDoubleClickZoom: true,
		mapTypeIds:        [ "styledMap" ]
	} );

	jQuery.getJSON( style, function (data) {

		var styledMapType = new google.maps.StyledMapType(
			data,
			{
				name: "Custom Map Styling"
			}
		);

		map.mapTypes.set( "styledMap", styledMapType );
		map.setMapTypeId( "styledMap" );

	} );

	if ( single === "no" ) {
		jQuery.ajax( {
			url:     postsURL,
			method:  "GET",
			success: function (data) {

				var marker,
				    i,
				    n             = 1,
				    metaMarkup    = "",
				    metaEndMarkup = "",
				    bounds        = new google.maps.LatLngBounds(),
				    zindex        = 9999;

				data.forEach( function (post) {

					// console.log( post );

          var pos = new google.maps.LatLng( post.map_fields._map_lat, post.map_fields._map_lng );

					bounds.extend( pos );

					//<editor-fold desc="Marker Build">
					var marker = new google.maps.Marker( {
						position: pos,
						map:      map,
						icon:     defaultIcon || icon,
					} );

					//</editor-fold>

          var cleanAddress = post.map_fields._map_address[ 0 ] !== "" ? post.map_fields._map_address[ 0 ].replace( /\s/g, "+" ) + "+" : "";
          var  cleanCity    = post.map_fields._map_city[ 0 ] !== "" ? post.map_fields._map_city[ 0 ].replace( /\s/g, "+" ) + "+" : "";
          var  cleanState   = post.map_fields._map_state[ 0 ] !== "" ? post.map_fields._map_state[ 0 ] + "+" : "";
          var  cleanZip     = post.map_fields._map_zip[ 0 ] !== "" ? post.map_fields._map_zip[ 0 ] + "+" : "";
          var cleanQuery   = cleanAddress + cleanCity + cleanState + cleanZip;

					var info = {
						title:   post.title.rendered || post.name || "",
						address: {
							address: post.map_fields._map_address,
							city:    post.map_fields._map_city + "," || "",
							state:   post.map_fields._map_state|| "",
							zip:     post.map_fields._map_zip || "",
						},
						url:     "https://www.google.com/maps/search/?api=1&query=" + cleanQuery + "&query_place_id=" + post.map_fields._map_placeID + ">"
					};

					if ( popup !== false ) {

						//<editor-fold desc="Content String Build">
						var contentString = tooltipBuild( info );
						//</editor-fold>


						//<editor-fold desc="InfoWindow Functionality">
						var infowindow = new google.maps.InfoWindow( {
								content: contentString,
							}
						);

						google.maps.event.addListener( marker, "click", ( function (marker, i) {

							return function () {
								if ( !jQuery.isEmptyObject( prev_infowindow ) ) {
									prev_infowindow.close();
								}
								infowindow = new google.maps.InfoWindow( {
									content: contentString,
									// maxWidth: 470
								} );
								prev_infowindow = infowindow;
								infowindow.open( map, marker );
							};

						} )( marker, i ) );

					}

					if ( hoverIcon.length ) {
						marker.addListener( "mouseover", function () {
							marker.setIcon( MAP_VARS.hoverIcon );
						} );

						marker.addListener( "mouseout", function () {
							marker.setIcon( defaultIcon );
						} );

						marker.addListener( "click", function () {
							marker.setIcon( MAP_VARS.hoverIcon );
						} );

					}
					//</editor-fold>

					marker.setVisible( true );

					markers.push( marker );
					marker.setZIndex( google.maps.Marker.MAX_ZINDEX + 1 );

					if ( post.id === 29 ) {
						marker.setZIndex( 9999999999 );
					}

					google.maps.event.addListener( map, "click", function () {
						infowindow.close();
					} );

				} );

				// if ( catID === '' )
				// 	map.fitBounds(bounds);
			},
			error:   function (request, status, error) {
				console.log( "error" );
				console.log( request );
				console.log( status );
				console.log( error );

			},
			// cache: true
		} );
	}
	else if ( !isNaN( single ) ) {
		var marker = new google.maps.Marker( {
			position: center,
			icon:     icon,
		} );
		marker.setMap( map );
	}

	// google.maps.event.addDomListener( window, "resize", function () {
	// 	var center = map.getCenter();
	// 	google.maps.event.trigger( map, "resize" );
	// 	map.setCenter( center );
	// } );

}
