<?PHP
/*
  Shortcode for displaying the map
*/
add_shortcode( 'steampixel-marker-map', function($atts = [], $content = null, $tag = '') {

  // Normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // Override default attributes with user attributes
  $attributes = shortcode_atts([
    'lat' => 49.78,
    'lng' => 9.94,
    'zoom' => 13,
    'height' => '300px',
    'type' => null,
    'geojson' => null,
    'button-label' => null,
    'button-link' => null
  ], $atts, $tag);

  // Add required assets
  wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', dirname(__FILE__ )) );
  wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', dirname(__FILE__ )) );
  wp_enqueue_style( 'leaflet.markercluster', plugins_url( 'assets/libs/leaflet.markercluster-1.4.1/MarkerCluster.css', dirname(__FILE__ )) );
  wp_enqueue_style( 'leaflet.markercluster.default', plugins_url( 'assets/libs/leaflet.markercluster-1.4.1/MarkerCluster.Default.css', dirname(__FILE__ )) );
  wp_enqueue_script( 'leaflet.markercluster', plugins_url( 'assets/libs/leaflet.markercluster-1.4.1/leaflet.markercluster.js', dirname(__FILE__ )) );
  wp_enqueue_script( 'steampixel-map-marker', plugins_url( 'assets/js/app.js', dirname(__FILE__ )) );
  wp_enqueue_style( 'steampixel-map-marker', plugins_url( 'assets/css/app.css', dirname(__FILE__ )) );

  // Get the markers
  $query = [
    'numberposts' => -1,
    'post_type' => 'marker'
  ];

  if($attributes['type']) {
    $type_keys = explode(',', $attributes['type']);

    $query['meta_query'] = [
      'relation' => 'OR'
    ];

    foreach($type_keys as $type_key) {
      array_push($query['meta_query'], [
        'key' => 'key',
        'value' => trim($type_key),
        'compare' => '='
      ]);
    }
  }

  $markers = get_posts($query);

  // Get the geoJSONs
  $query = [
    'numberposts' => -1,
    'post_type' => 'geojson'
  ];

  if($attributes['geojson']) {
    $geojson_keys = explode(',', $attributes['geojson']);

    $query['meta_query'] = [
      'relation' => 'OR'
    ];

    foreach($geojson_keys as $geojson_key) {
      array_push($query['meta_query'], [
        'key' => 'key',
        'value' => trim($geojson_key),
        'compare' => '='
      ]);
    }
  }

  $geojsons = get_posts($query);

  // Build layers array
  $layers = [];

  // Add category data to each layer item
  foreach($markers as $marker) {

    // Get the category
    $category = 'Andere Ebenen';
    $categories = wp_get_post_categories($marker->ID);
    if(count($categories)) {
      $category = get_cat_name($categories[0]);
    }

    // Get some dato for this layer
    $marker_key = get_post_meta($marker->ID, 'key', true);
    $marker_filter_icon = get_post_meta($marker->ID, 'filter_icon', true);
    $marker_title = $marker->post_title;

    // Create category in array
    if(!array_key_exists($category, $layers)) {
      $layers[$category] = [];
    }

    // Push to array
    array_push($layers[$category], [
      'type' => 'marker',
      'category' => $category,
      'key' => $marker_key,
      'filter_icon' => $marker_filter_icon,
      'title' => $marker_title
    ]);

  }

  // Add category data to each layer item
  foreach($geojsons as $geojson) {

    // Get the category
    $category = 'Andere Ebenen';
    $categories = wp_get_post_categories($geojson->ID);
    if(count($categories)) {
      $category = get_cat_name($categories[0]);
    }

    // Get some dato for this layer
    $geojson_key = get_post_meta($geojson->ID, 'key', true);
    $geojson_color = get_post_meta($geojson->ID, 'color', true);
    $geojson_opacity = get_post_meta($geojson->ID, 'opacity', true);
    // $geojson_icon = get_post_meta($geojson->ID, 'icon', true);
    $geojson_title = $geojson->post_title;

    // Create category in array
    if(!array_key_exists($category, $layers)) {
      $layers[$category] = [];
    }

    // Push to array
    array_push($layers[$category], [
      'type' => 'geojson',
      'category' => $category,
      'key' => $geojson_key,
      'title' => $geojson_title,
      'color' => $geojson_color,
      'opacity' => $geojson_opacity
    ]);

  }

  // print_r($layers);

  return Sp\View::render('map',[
    // 'markers' => $markers,
    // 'geojsons' => $geojsons,
    'layers' => $layers,
    'lat' => $attributes['lat'],
    'lng' => $attributes['lng'],
    'zoom' => $attributes['zoom'],
    'height' => $attributes['height'],
    'content' => do_shortcode($content),
    'button_label' => $attributes['button-label'],
    'button_link' => $attributes['button-link']
  ]);

} );
