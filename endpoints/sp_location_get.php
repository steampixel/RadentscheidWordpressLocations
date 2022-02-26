<?php

add_action( 'wp_loaded', function() {

  $path_parts = parse_url ( $_SERVER['REQUEST_URI'] );

  if($path_parts['path']=='/api/locations') {

    $data = [];

    // Build query
    $query = [
      'numberposts' => -1,
      'post_type' => 'location'
    ];

    // Query types
    if(isset($_GET['type'])) {
      $types = explode(',', $_GET['type']);

      $query['meta_query'] = [
        'relation' => 'OR'
      ];

      foreach($types as $type) {
        array_push($query['meta_query'], [
          'key' => 'type',
          'value' => trim($type),
          'compare' => 'LIKE'
        ]);
      }
    }

    $locations = get_posts( $query );

    if($locations) {

      // Foreach location
      foreach($locations as $location) {

        $push_data = [
          'id' => $location->ID,
          'title' => $location->post_title,
          'date' => $location->post_date,
          'url' => get_permalink($location->ID)
        ];

        $type = get_post_meta( $location->ID, 'type', true );
        $images = get_post_meta( $location->ID, 'images', true );
        $street = get_post_meta($location->ID, 'street', true);
        $house_number = get_post_meta($location->ID, 'house_number', true);
        $postcode = get_post_meta($location->ID, 'postcode', true);
        $description = get_post_meta($location->ID, 'description', true);
        $place = get_post_meta($location->ID, 'place', true);
        $suburb = get_post_meta($location->ID, 'suburb', true);
        $lng = get_post_meta($location->ID, 'lng', true);
        $lat = get_post_meta($location->ID, 'lat', true);

        if($type) { $push_data['type'] = $type; }
        if($lng) { $push_data['lng'] = $lng; }
        if($lat) { $push_data['lat'] = $lat; }
        if($place) { $push_data['place'] = $place; }
        if($postcode) { $push_data['postcode'] = $postcode; }
        if($description) { $push_data['description'] = $description; }
        if($house_number) { $push_data['house_number'] = $house_number; }
        if($street) { $push_data['street'] = $street; }
        if($suburb) { $push_data['suburb'] = $suburb; }

        // Add images and thumbnails
        if($images) {
          foreach($images as $key => $image){
            $images[$key]['name'] = $images[$key]['src'];
            $images[$key]['src'] = spGetUploadUrl().'/sp-locations/'.$location->ID.'/'.$images[$key]['name'];
            $images[$key]['thumbnails'] = [
              '300' => spGetUploadUrl().'/sp-locations/'.$location->ID.'/300/'.$images[$key]['name'],
              '600' => spGetUploadUrl().'/sp-locations/'.$location->ID.'/600/'.$images[$key]['name']
            ];
          }
          $push_data['images'] = $images;
        }

        // Try to find the marker
        $location_marker = get_posts([
          'numberposts' => -1,
          'post_type' => 'marker',
          'meta_query' => array(
             array(
               'key' => 'key',
               'value' => $type,
               'compare' => '='
             )
         )
        ]);

        // Push marker data
        if($location_marker) {

          // Get the icon
          $marker_icon = get_post_meta($location_marker[0]->ID, 'icon', true);

          $push_data['marker'] = [
            'key' => $type,
            'title' => $location_marker[0]->post_title,
            'icon' => $marker_icon
          ];

        }

        // Add the data by id
        $data[$location->ID] = $push_data;

      }

    }

    // Return a JSON result and exit
    header('Content-Type: application/json');
    die(json_encode($data));

  }

} );
