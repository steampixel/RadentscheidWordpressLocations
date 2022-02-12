<?php

add_action( 'wp_loaded', function() {

  $path_parts = parse_url ( $_SERVER['REQUEST_URI'] );

  if($path_parts['path']=='/api/geojsons') {

    $data = [];

    // Build query
    $query = [
      'numberposts' => -1,
      'post_type' => 'geojson'
    ];

    // Query types
    if(isset($_GET['key'])) {
      $keys = explode(',', $_GET['key']);

      $query['meta_query'] = [
        'relation' => 'OR'
      ];

      foreach($keys as $key) {
        array_push($query['meta_query'], [
          'key' => 'key',
          'value' => trim($key),
          'compare' => '='
        ]);
      }
    }

    $geojsons = get_posts( $query );

    if($geojsons) {

      // Foreach geoJSON
      foreach($geojsons as $geojson) {

        $push_data = [
          'id' => $geojson->ID,
          'title' => $geojson->post_title,
          'date' => $geojson->post_date
        ];

        $key = get_post_meta( $geojson->ID, 'key', true );
        $icon = get_post_meta( $geojson->ID, 'icon', true );
        $color = get_post_meta( $geojson->ID, 'color', true );
        $opacity = get_post_meta( $geojson->ID, 'opacity', true );
        $weight = get_post_meta( $geojson->ID, 'weight', true );
        $description = get_post_meta( $geojson->ID, 'description', true );
        $visible = get_post_meta( $geojson->ID, 'visible', true );
        $url = get_post_meta( $geojson->ID, 'url', true );
        $geojson_data = json_decode(get_post_meta( $geojson->ID, 'geojson', true ));

        if($key) { $push_data['key'] = $key; }
        if($icon) { $push_data['icon'] = $icon; }
        if($color) { $push_data['color'] = $color; }
        if($opacity) { $push_data['opacity'] = $opacity; }
        if($weight) { $push_data['weight'] = $weight; }
        if($description) { $push_data['description'] = $description; }
        if($visible) { $push_data['visible'] = $visible; }
        if($url) { $push_data['url'] = $url; }
        if($geojson_data) { $push_data['geojson'] = $geojson_data; }

        // Add the data by id
        $data[$geojson->ID] = $push_data;

      }

    }

    // Return a JSON result and exit
    header('Content-Type: application/json');
    die(json_encode($data));

  }

} );
