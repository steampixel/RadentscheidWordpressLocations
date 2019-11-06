<?php
/**
* Plugin Name: Radentscheid Locations
* Description: Wuhu! Dieses Plugin hilft uns verschiedene Locations für den Radentscheid zu tracken.
* Version: 1.3.0
* Author: Christoph Stitz
* Author URI: https://steampixel.de
**/

// Debug
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Include core classes
include('classes/Thumbnail.php');
include('classes/View.php');
include('classes/Migrator.php');

// Include API endpoints
include('endpoints/sp_location_add.php');

// Include shortcodes
include('shortcodes/steampixel_marker_map.php');
include('shortcodes/steampixel_marker_count.php');
include('shortcodes/steampixel_marker_form.php');

// Include custom post types
include('custom_post_types/location.php');
include('custom_post_types/marker.php');

/*
  Add locations endpoint
*/
add_action('wp_ajax_nopriv_splocationadd', 'sp_location_add');
add_action('wp_ajax_splocationadd', 'sp_location_add');

/*
  Enabble file upload in edit form
*/
add_action('post_edit_form_tag', function () {
  echo ' enctype="multipart/form-data"';
});

/*
  Run migrations
*/
add_action( 'init', function () {
  Migrator::migrate();
});

/*
  Function to get the upload url
  This function makes this plugin multisite compatible
*/
function spGetUploadUrl() {
  if(is_multisite()) {
    return WP_CONTENT_URL.'/uploads/sites/'.get_current_blog_id();
  } else {
    return WP_CONTENT_URL.'/uploads';
  }
}
