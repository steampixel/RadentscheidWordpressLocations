<?PHP

/*
  Shortcode for displaying the submit form
*/
add_shortcode('steampixel-marker-form', function($atts = [], $content = null, $tag = '') {

  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $attributes = shortcode_atts([
     'selected-type' => 'sign',
     'require-address' => 'true',
     'require-image' => 'true',
     'require-personal-data' => 'true',
     'show-opening-hours' => 'true',
     'show-description' => 'true',
     'lat' => 49.78,
     'lng' => 9.94,
     'zoom' => 13,
     'name-label' => 'Name der Location',
     'name-placeholder' => 'Name des Ladens, der Firma oder der Lokalität',
     'file-label' => 'Bild hochladen',
     'file-placeholder' => 'Wähle ein Bild für die Location aus',
     'submit-value' => 'Location registrieren',
  ], $atts, $tag);

  wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', dirname(__FILE__ )) );
  wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', dirname(__FILE__ )) );
  wp_enqueue_script( 'jsencrypt', plugins_url( 'assets/libs/jsencrypt/bin/jsencrypt.min.js', dirname(__FILE__ )) );
  wp_enqueue_script( 'steampixel-map-marker', plugins_url( 'assets/js/app.js', dirname(__FILE__ )) );
  wp_enqueue_style( 'steampixel-map-marker', plugins_url( 'assets/css/app.css', dirname(__FILE__ )) );

  return Sp\View::render('add_location_form', [
    'selected_type' => $attributes['selected-type'],
    'require_address' => ($attributes['require-address']=="false" ? false:true),
    'require_image' => ($attributes['require-image']=="false" ? false:true),
    'require_personal_data' => ($attributes['require-personal-data']=="false" ? false:true),
    'show_opening_hours' => ($attributes['show-opening-hours']=="false" ? false:true),
    'show_description' => ($attributes['show-description']=="false" ? false:true),
    'lat' => $attributes['lat'],
    'lng' => $attributes['lng'],
    'zoom' => $attributes['zoom'],
    'name_label' => $attributes['name-label'],
    'name_placeholder' => $attributes['name-placeholder'],
    'file_label' => $attributes['file-label'],
    'file_placeholder' => $attributes['file-placeholder'],
    'submit_value' => $attributes['submit-value'],
  ]);

} );
