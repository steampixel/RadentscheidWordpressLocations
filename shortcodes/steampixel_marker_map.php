<?PHP
/*
  Shortcode for displaying the map
*/
add_shortcode( 'steampixel-marker-map', function($atts = [], $content = null, $tag = '') {

  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $attributes = shortcode_atts([
    'lat' => 49.78,
    'lng' => 9.94,
    'zoom' => 13,
    'height' => '300px',
    'type' => null,
    'button-label' => null,
    'button-link' => null
  ], $atts, $tag);

  wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', dirname(__FILE__ )) );
  wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', dirname(__FILE__ )) );
  wp_enqueue_script( 'steampixel-map-marker', plugins_url( 'assets/js/app.js', dirname(__FILE__ )) );
  wp_enqueue_style( 'steampixel-map-marker', plugins_url( 'assets/css/app.css', dirname(__FILE__ )) );

  if($attributes['type']){

    $types = explode(',', $attributes['type']);

    $locations = [];

    foreach($types as $type){
      $_locations = get_posts( [
        'numberposts' => -1,
        'post_type' => 'location',
        'meta_query' => array(
           array(
             'key' => 'type',
             'value' => trim($type),
             'compare' => '='
           )
       )
      ] );

      if($_locations) {
        $locations = array_merge($locations, $_locations);
      }

    }


  }else{
    $locations = get_posts( [
      'numberposts' => -1,
      'post_type' => 'location'
    ] );
  }

  // print_r($locations);

  return Sp\View::render('map',[
    'locations' => $locations,
    'lat' => $attributes['lat'],
    'lng' => $attributes['lng'],
    'zoom' => $attributes['zoom'],
    'height' => $attributes['height'],
    'content' => do_shortcode($content),
    'button_label' => $attributes['button-label'],
    'button_link' => $attributes['button-link'],
  ]);

} );
