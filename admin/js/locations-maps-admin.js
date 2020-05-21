(function ($) {
  'use strict';
  
  function pageLoadSetLatLng() {
    let lat = document.getElementById('_map_lat').value;
    let lng = document.getElementById('_map_lng').value;
  }
  
  function mapGeoLocate() {
    $('#get_geoloc').on('click', function () {
      
      let address = $('#_map_address').val() || '';
      let address2 = $('#_map_address2').val() || '';
      let city = $('#_map_city').val() || '';
      let state = $('#_map_state').val() || '';
      let zip = $('#_map_zip').val() || '';
      let country = $('#_map_country').val() || '';
      let phone = $('#_map_phone').val() || '';
      let website = $('#_map_website').val() || '';
      //var placeID = $( "#_map_placeID" ).val() || "";
  
      let paramAddress = {
        address : address,
        address2: address2,
        city: city,
        state: state,
        zip: zip,
        country: country
      };
  
      $('#map-loading').addClass('show');
      $('.map-row').addClass('loading');
      $('.map-field input, .map-field.disabled input').prop('readonly', true);
  
      // Start Ajax Build
      $.ajax({
        url:      '/wp-json/locations/v1/place/',
        method:   'GET',
        data : {
          address : paramAddress
        },
        complete: function () {
          $('#map-loading').removeClass('show');
          $('.map-row').removeClass('loading');
          $('.map-field:not(.readonly) input').prop('readonly', false);
        },
        success:  function (data) {
          
          let lat = parseFloat(data.lat);
          let lng = parseFloat(data.lng);
          // var placeID = data.results[0].place_id;
          
          document.getElementById('lat_text').value = lat;
          document.getElementById('lng_text').value = lng;
          document.getElementById('_map_lat').value = lat;
          document.getElementById('_map_lng').value = lng;
          // document.getElementById( "_map_placeID" ).value = placeID;
          
          console.log(lat);
          let returnData = {
            _ajax_nonce: MAP_ADMIN.nonce,
            action:      'geo_cb',
            address:     address,
            address2:    address2,
            city:        city,
            state:       state,
            zip:         zip,
            // country:     country,
            lat:         lat,
            lng:         lng,
            phone:       phone,
            website:     website,
            // "placeID":   placeID,
            id:          $('#get_geoloc').data('postid')
          };
          
          $.post(MAP_ADMIN.adminURL, returnData, function (msg) {
            alert('Address and Place Saved! Please be sure to hit Update if you made any other content changes.');
          });
          
        },
        error:    function (data) {
          alert('Stopping function to grab location. There has been an error!');
        }
      });
      
    });
  }
  
  function mapAddressSave() {
    $('#save_address').on('click', function () {
      
      let address = document.getElementById('_map_address').value || '';
      let address2 = document.getElementById('_map_address2').value || '';
      let city = document.getElementById('_map_city').value || '';
      let state = document.getElementById('_map_state').value || '';
      let zip = document.getElementById('_map_zip').value || '';
      // let country = document.getElementById('_map_country').value || '';
      let lat = document.getElementById('_map_lat').value || '';
      let lng = document.getElementById('_map_lng').value || '';
      let phone = document.getElementById('_map_phone').value || '';
      let email = document.getElementById('_map_email').value || '';
      let website = document.getElementById('_map_website').value || '';
      //let placeID = document.getElementById( "_map_placeID" ).value || "";
      
      let returnData = {
        _ajax_nonce: MAP_ADMIN.nonce,
        action:      'geo_cb',
        address:     address || '',
        address2:    address2 || '',
        city:        city || '',
        state:       state || '',
        zip:         zip || '',
        // country:     country || '',
        lat:         lat || '',
        lng:         lng || '',
        // "placeID":   placeID || "",
        email:       email || '',
        phone:       phone || '',
        website:     website || '',
        id:          $('#get_geoloc').data('postid')
      };
      
      $.post(MAP_ADMIN.adminURL, returnData, function (msg) {
        alert('Address saved. If you haven\'t already, click "Get Place" to get the Place Informtaion.');
      });
      
    });
  }
  
  function mapGeoLocateReset() {
    $('#geo_reset').on('click', function () {
      
      let address = document.getElementById('_map_address').value || '';
      let address2 = document.getElementById('_map_address2').value || '';
      let city = document.getElementById('_map_city').value || '';
      let state = document.getElementById('_map_state').value || '';
      let zip = document.getElementById('_map_zip').value || '';
      // let country = document.getElementById( "_map_country" ).value || "";
      let phone = document.getElementById('_map_phone').value || '';
      let website = document.getElementById('_map_website').value || '';
      
      document.getElementById('lat_text').value = '';
      document.getElementById('lng_text').value = '';
      document.getElementById('_map_lat').value = '';
      document.getElementById('_map_lng').value = '';
      // document.getElementById( "_map_placeID" ).value = "";
      
      let returnData = {
        _ajax_nonce: MAP_ADMIN.nonce,
        action:      'geo_cb',
        address:     address || '',
        address2:    address2 || '',
        city:        city || '',
        state:       state || '',
        zip:         zip || '',
        // country:     country || '',
        lat:         '',
        lng:         '',
        // "placeID":   placeID || "",
        phone:       phone || '',
        website:     website || '',
        id:          $('#get_geoloc').data('postid')
      };
      
      $.post(MAP_ADMIN.adminURL, returnData, function (msg) {
        alert('Data Deleted!');
      });
      
    });
  }
  
  function mapClearChildren(element) {
    for (let i = 0; i < element.childNodes.length; i++) {
      let e = element.childNodes[i];
      if (e.tagName) switch (e.tagName.toLowerCase()) {
        case 'input':
          switch (e.type) {
            case 'radio':
            case 'checkbox':
              e.checked = false;
              break;
            case 'button':
            case 'submit':
            case 'image':
              break;
            case 'hidden' :
            default:
              e.value = '';
              break;
          }
          break;
        case 'select':
          e.selectedIndex = 0;
          break;
        case 'textarea':
          e.innerHTML = '';
          break;
        default:
          mapClearChildren(e);
      }
    }
  }
  
  function mapFormReset() {
    $('#form-reset').on('click', function () {
      
      mapClearChildren(document.getElementById('map-elements'));
      
      let returnData = {
        _ajax_nonce: MAP_ADMIN.nonce,
        action:      'geo_cb',
        address:     '',
        address2:    '',
        city:        '',
        state:       '',
        zip:         '',
        // country:     '',
        lat:         '',
        lng:         '',
        phone:       '',
        website:     '',
        // placeID:   "",
        id:          $('#get_geoloc').data('postid')
      };
      
      $.post(MAP_ADMIN.adminURL, returnData, function (msg) {
        alert('Data Reset! Please make sure to save the post to update all the address fields..');
      });
      
    });
  }
  
  function mapAPICheck() {
    $('#get_geoloc').on('click', function () {
      
      let address = $('#_map_address').val() || '';
      let address2 = $('#_map_address2').val() || '';
      let city = $('#_map_city').val() || '';
      let state = $('#_map_state').val() || '';
      let zip = $('#_map_zip').val() || '';
      let country = $('#_map_country').val() || '';
      let phone = $('#_map_phone').val() || '';
      let website = $('#_map_website').val() || '';
      //var placeID = $( "#_map_placeID" ).val() || "";
      
      // let addressInput = address !== '' ? address.replace(/\s/g, '+') + ',+' : '';
      // let cityInput = city !== '' ? city.replace(/\s/g, '+') + ',+' : '';
      // let stateInput = state !== '' ? state + ',+' : '';
      // let zipInput = zip !== '' ? zip + ',+' : '';
      // // let countryInput = country !== "" ? country.replace( /\s/g, "+" ) + ",+" : "";
      // let countryInput = '';
      
      // Example Data
      address = '12000 E 56th Avenue';
      city = 'Denver';
      state = 'Colorado';
      zip = '80239';
      
      
      let paramAddress = {
        address : address,
        address2: address2,
        city: city,
        state: state,
        zip: zip,
        country: country
      };
      
      $('#map-loading').addClass('show');
      $('.map-row').addClass('loading');
      $('.map-field input, .map-field.disabled input').prop('readonly', true);
  
      // Start Ajax Build
      $.ajax({
        url:      '/wp-json/locations/v1/place/',
        method:   'GET',
        data : {
          address : paramAddress
        },
        // dataType: 'json',
        complete: function () {
          $('#map-loading').removeClass('show');
          $('.map-row').removeClass('loading');
          $('.map-field:not(.readonly) input').prop('readonly', false);
        },
        success:  function (data) {
          console.log(data);
        },
        error:    function (data) {
          console.log(data);
        }
      });
    });
  }
  
  if ($('body').hasClass('post-type-locations')) {
    mapGeoLocate();
    // mapAddressSave();
    mapGeoLocateReset();
    // mapFormReset();
    // mapAPICheck();
  }
  
})(jQuery);
