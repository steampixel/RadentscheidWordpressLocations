<?php
/**
* Plugin Name: Verkehrswende Locations
* Description: Dieses Plugin hilft uns verschiedene Problemstellen für den Rad- und Fußverkehr zu tracken.
* Version: 1.11.1
* Author: Christoph Stitz
* Author URI: https://steampixel.de
**/

define('SP_LOCATIONS_VERSION', '1.11.1');

// Debug
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/*
  Function to get the upload url
  This function makes this plugin multisite compatible
*/
function spGetUploadUrl() {
  // if(is_multisite()) {
  //   return WP_CONTENT_URL.'/uploads/sites/'.get_current_blog_id();
  // } else {
    return WP_CONTENT_URL.'/uploads';
  //}
}

/*
  Helper function to trim a text
*/
function spTrimText($s, $max_length = 340) {
  if (strlen($s) > $max_length) {
      $offset = ($max_length - 3) - strlen($s);
      $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
  }
  return $s;
}

/*
  Function to grab an image
  https://stackoverflow.com/questions/6476212/save-image-from-url-with-curl-php
*/
function grab_image($url,$saveto){
  $ch = curl_init ($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
  $raw=curl_exec($ch);
  curl_close ($ch);
  if(file_exists($saveto)){
    unlink($saveto);
  }
  $fp = fopen($saveto,'x');
  fwrite($fp, $raw);
  fclose($fp);
}

// Include core classes
include('classes/Thumbnail.php');
include('classes/View.php');
include('classes/Migrator.php');
include('classes/Seeder.php');

// Include API endpoints
include('endpoints/sp_location_add.php');
include('endpoints/sp_location_get.php');
include('endpoints/sp_location_print.php');
include('endpoints/sp_location_import.php');
include('endpoints/sp_geojson_get.php');

// Include shortcodes
include('shortcodes/steampixel_marker_map.php');
include('shortcodes/steampixel_marker_count.php');
include('shortcodes/steampixel_marker_form.php');

// Include custom post types
include('custom_post_types/location.php');
include('custom_post_types/marker.php');
include('custom_post_types/geojson.php');
// include('custom_post_types/image.php');

/*
  Enable file upload in edit form
*/
add_action('post_edit_form_tag', function () {
  echo ' enctype="multipart/form-data"';
});

/*
  Run migrations and seeds
*/
add_action( 'init', function () {
  Sp\Migrator::migrate();
  Sp\Seeder::seed();
});

/*
  Register plugin options
*/
add_action( 'admin_init', function() {
  add_option( 'sp-locations_notify_email', '');
  add_option( 'sp-locations_map_post_url', '');
  add_option( 'sp-locations_form_post_url', '');
  add_option( 'sp-locations_rsa_public_key', '');
  add_option( 'sp-locations_rsa_enable', false);
  add_option( 'sp-show_solutions_on_detail_page', false);
  register_setting( 'sp-locations_options_group', 'sp-locations_notify_email', 'myplugin_callback' );
  register_setting( 'sp-locations_options_group', 'sp-locations_map_post_url', 'myplugin_callback' );
  register_setting( 'sp-locations_options_group', 'sp-locations_form_post_url', 'myplugin_callback' );
  register_setting( 'sp-locations_options_group', 'sp-locations_rsa_public_key', 'myplugin_callback' );
  register_setting( 'sp-locations_options_group', 'sp-locations_rsa_enable', 'myplugin_callback' );
  register_setting( 'sp-locations_options_group', 'sp-show_solutions_on_detail_page', 'myplugin_callback' );
} );

/*
  Options page
*/
add_action('admin_menu', function() {
  add_options_page('Location Options', 'Location Options', 'manage_options', 'sp-locations', function() {
    echo Sp\View::render('options', [

    ]);
  });
});
