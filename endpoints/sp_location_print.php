<?php

$path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if($path_only=='/location-print') {

  $location_id = $_GET['location'];

  $location = get_post($location_id);

  if($location) {

      echo Sp\View::render('location_print_details', [
        'post_id' => $location->ID,
        'post_title' => $location->post_title,
      ]);

  }

  exit;

}
