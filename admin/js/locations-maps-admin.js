( function ($) {
	"use strict";

	function pageLoadSetLatLng () {
		var lat = document.getElementById( "_map_lat" ).value,
		    lng = document.getElementById( "_map_lng" ).value;
	}

	function mapGeoLocate () {

		$( "#get_geoloc" ).on( "click", function (e) {

			var address = $( "#_map_address" ).val() || "",
			    city    = $( "#_map_city" ).val() || "",
			    state   = $( "#_map_state" ).val() || "",
			    zip     = $( "#_map_zip" ).val() || "",
			    country = $( "#_map_country" ).val() || "",
			    phone   = $( "#_map_phone" ).val() || "",
			    hours   = $( "#_map_hours" ).val() || "",
			    placeID = $( "#_map_placeID" ).val() || "",
			    apiKey  = $( "#map_api_key" ).val() || "";

			if ( apiKey === "" ) {
				alert( "You must enter in an API Key. Please head to the Settings page located under this post type! Stopping Function." );
				return false;
			}

			var addressInput = address !== "" ? address.replace( /\s/g, "+" ) + ",+" : "",
			    cityInput    = city !== "" ? city.replace( /\s/g, "+" ) + ",+" : "",
			    stateInput   = state !== "" ? state + ",+" : "",
			    zipInput     = zip !== "" ? zip + ",+" : "",
			    countryInput = country !== "" ? country.replace( /\s/g, "+" ) + ",+" : "";

			$( "#map-loading" ).addClass( "show" );
			$( ".map-row" ).addClass( "loading" );
			$( ".map-field input, .map-field.disabled input" ).prop( "readonly", true );

			// Start Ajax Build
			$.ajax( {
				url:      "https://maps.googleapis.com/maps/api/geocode/json?address=" + addressInput + cityInput + stateInput + zipInput + countryInput + "&key=" + apiKey,
				method:   "GET",
				dataType: "json",
				complete: function () {
					$( "#map-loading" ).removeClass( "show" );
					$( ".map-row" ).removeClass( "loading" );
					$( ".map-field:not(.readonly) input" ).prop( "readonly", false );
					// setTimeout(function() {
					// 	alert('Geocode settings saved');
					// 	}, 500);
				},
				success:  function (data) {

					var lat     = data.results[ 0 ].geometry.location.lat,
					    lng     = data.results[ 0 ].geometry.location.lng,
					    placeID = data.results[ 0 ].place_id;

					document.getElementById( "lat_text" ).value = lat;
					document.getElementById( "lng_text" ).value = lng;
					document.getElementById( "_map_lat" ).value = lat;
					document.getElementById( "_map_lng" ).value = lng;
					document.getElementById( "_map_placeID" ).value = placeID;

					var returnData = {
						_ajax_nonce: MAP_ADMIN.nonce,
						action:      "geo_cb",
						"address":   address,
						"city":      city,
						"state":     state,
						"zip":       zip,
						"country":   country,
						"lat":       lat,
						"lng":       lng,
						"phone":     phone,
						"hours":     hours,
						"placeID":   placeID,
						"id":        $( "#get_geoloc" ).data( "postid" ),
					};

					$.post( MAP_ADMIN.adminURL, returnData, function (msg) {
						alert( "Address and Geocode Saved! Please be sure to hit Update if you made any other content changes." );
					} );

				},
				error:    function (data) {
					alert( "You must enter in an API Key. Please head to the Settings page located under this post type! Stopping Function." );
				},
			} );

		} );

	}

	function mapAddressSave () {
		$( "#save_address" ).on( "click", function () {

			var address = document.getElementById( "_map_address" ).value || "",
			    city    = document.getElementById( "_map_city" ).value || "",
			    state   = document.getElementById( "_map_state" ).value || "",
			    zip     = document.getElementById( "_map_zip" ).value || "",
			    country = document.getElementById( "_map_country" ).value || "",
			    lat     = document.getElementById( "_map_lat" ).value || "",
			    lng     = document.getElementById( "_map_lng" ).value || "",
			    phone   = document.getElementById( "_map_phone" ).value || "",
			    hours   = document.getElementById( "_map_hours" ).value || "",
			    placeID = document.getElementById( "_map_placeID" ).value || "";

			var returnData = {
				_ajax_nonce: MAP_ADMIN.nonce,
				action:      "geo_cb",
				"address":   address || "",
				"city":      city || "",
				"state":     state || "",
				"zip":       zip || "",
				"country":   country || "",
				"lat":       lat || "",
				"lng":       lng || "",
				"placeID":   placeID || "",
				"phone":     phone || "",
				"hours":     hours || "",
				"id":        $( "#get_geoloc" ).data( "postid" )
			};

			$.post( MAP_ADMIN.adminURL, returnData, function (msg) {
				alert( "Address saved. If you haven't already, click \"Get Location\" to get the Latitude and Longitude." );
			} );

		} );
	}

	function mapGeoLocateReset () {
		$( "#geo_reset" ).on( "click", function () {

			var address = document.getElementById( "_map_address" ).value || "",
			    city    = document.getElementById( "_map_city" ).value || "",
			    state   = document.getElementById( "_map_state" ).value || "",
			    zip     = document.getElementById( "_map_zip" ).value || "",
			    country = document.getElementById( "_map_country" ).value || "",
			    phone   = document.getElementById( "_map_phone" ).value || "",
			    hours   = document.getElementById( "_map_hours" ).value || "";

			document.getElementById( "lat_text" ).value = "";
			document.getElementById( "lat_text" ).value = "";
			document.getElementById( "_map_lat" ).value = "";
			document.getElementById( "_map_lng" ).value = "";
			document.getElementById( "_map_placeID" ).value = "";

			var returnData = {
				_ajax_nonce: MAP_ADMIN.nonce,
				action:      "geo_cb",
				"address":   address || "",
				"city":      city || "",
				"state":     state || "",
				"zip":       zip || "",
				"country":   country || "",
				"lat":       "",
				"lng":       "",
				"placeID":   placeID || "",
				"phone":     phone || "",
				"hours":     hours || "",
				"id":        $( "#get_geoloc" ).data( "postid" )
			};

			$.post( MAP_ADMIN.adminURL, returnData, function (msg) {
				alert( "Geocode Deleted!" );
			} );

		} );
	}

	function mapClearChildren (element) {
		for ( var i = 0; i < element.childNodes.length; i++ ) {
			var e = element.childNodes[ i ];
			if ( e.tagName ) switch ( e.tagName.toLowerCase() ) {
				case "input":
					switch ( e.type ) {
						case "radio":
						case "checkbox":
							e.checked = false;
							break;
						case "button":
						case "submit":
						case "image":
							break;
						case "hidden" :
							switch ( e.id ) {
								case "map_api_key":
									break;
								default:
									e.value = "";
									break;
							}
							break;
						default:
							e.value = "";
							break;
					}
					break;
				case "select":
					e.selectedIndex = 0;
					break;
				case "textarea":
					e.innerHTML = "";
					break;
				default:
					mapClearChildren( e );
			}
		}
	}

	function mapFormReset () {
		$( "#form-reset" ).on( "click", function () {

			mapClearChildren( document.getElementById( "map-elements" ) );

			var returnData = {
				_ajax_nonce: MAP_ADMIN.nonce,
				action:      "geo_cb",
				"address":   "",
				"city":      "",
				"state":     "",
				"zip":       "",
				"country":   "",
				"lat":       "",
				"lng":       "",
				"phone":     "",
				"hours":     "",
				"placeID":   "",
				"id":        $( "#get_geoloc" ).data( "postid" )
			};

			$.post( MAP_ADMIN.adminURL, returnData, function (msg) {
				alert( "Data Reset! Please make sure to save the post to update all the address fields.." );
			} );

		} );
	}

	if ( $( "body" ).hasClass( "post-type-locations" ) ) {
		mapGeoLocate();
		mapAddressSave();
		mapGeoLocateReset();
		mapFormReset();
		console.log( "click" );
	}

} )( jQuery );
