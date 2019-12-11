<?php

if($_SERVER['REQUEST_URI']=='/api/locations') {

  $data = [];

  $locations = get_posts( [
    'numberposts' => -1,
    'post_type' => 'location'
  ] );

  if($locations) {

    foreach($locations as $location){

      $push_data = [];

      $type = get_post_meta( $location->ID, 'type', true );
      $images = get_post_meta( $location->ID, 'images', true );
      $street = get_post_meta($location->ID, 'street', true);
      $house_number = get_post_meta($location->ID, 'house_number', true);
      $postcode = get_post_meta($location->ID, 'postcode', true);
      $place = get_post_meta($location->ID, 'place', true);
      $suburb = get_post_meta($location->ID, 'suburb', true);
      $lng = get_post_meta($location->ID, 'lng', true);
      $lat = get_post_meta($location->ID, 'lat', true);

      if($type) { $push_data['type'] = $type; }
      if($lng) { $push_data['lng'] = $lng; }
      if($lat) { $push_data['lat'] = $lat; }
      if($place) { $push_data['place'] = $place; }
      if($postcode) { $push_data['postcode'] = $postcode; }
      if($house_number) { $push_data['house_number'] = $house_number; }
      if($street) { $push_data['street'] = $street; }
      if($suburb) { $push_data['suburb'] = $suburb; }
      if($images) {

        foreach($images as $key => $image){
          $images[$key]['src'] = spGetUploadUrl().'/sp-locations/'.$location->ID.'/'.$images[$key]['src'];
        }

        $push_data['images'] = $images;
      }

      array_push($data, $push_data);

    }

  }

  header('Content-Type: application/json');
  die(json_encode($data));

}
