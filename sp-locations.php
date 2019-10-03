<?php
/**
* Plugin Name: Radentscheid Locations
* Description: Wuhu! Dieses Plugin hilft uns verschiedene Locations für den Radentscheid zu tracken.
* Version: 1.0
* Author: Christoph Stitz
* Author URI: https://steampixel.de
**/

// Debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('classes/Thumbnail.php');
include('classes/View.php');
include('endpoints/sp_location_add.php');

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

  wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', __FILE__ ) );
  wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', __FILE__ ) );
  wp_enqueue_script( 'steampixel-map-marker', plugins_url( 'assets/js/app.js', __FILE__ ) );
  wp_enqueue_style( 'steampixel-map-marker', plugins_url( 'assets/css/app.css', __FILE__ ) );

  if($attributes['type']){
    $locations = get_posts( [
      'numberposts' => -1,
      'post_type' => 'location',
      'meta_query' => array(
         array(
             'key' => 'type',
             'value' => $attributes['type'],
             'compare' => '='
         )
     )
    ] );
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
    'content' => $content,
    'button_label' => $attributes['button-label'],
    'button_link' => $attributes['button-link'],
  ]);

} );

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

  wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', __FILE__ ) );
  wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', __FILE__ ) );
  wp_enqueue_script( 'steampixel-map-marker', plugins_url( 'assets/js/app.js', __FILE__ ) );
  wp_enqueue_style( 'steampixel-map-marker', plugins_url( 'assets/css/app.css', __FILE__ ) );

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

/*
  Add custom post type
*/
add_action( 'init', function () {

  // Set UI labels for Custom Post Type
  $labels = array(
    'name'                => _x( 'Locations', 'Post Type General Name', 'twentythirteen' ),
    'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'twentythirteen' ),
    'menu_name'           => __( 'Locations', 'twentythirteen' ),
    'parent_item_colon'   => __( 'Parent Location', 'twentythirteen' ),
    'all_items'           => __( 'All Locations', 'twentythirteen' ),
    'view_item'           => __( 'View Location', 'twentythirteen' ),
    'add_new_item'        => __( 'Add New Location', 'twentythirteen' ),
    'add_new'             => __( 'Add New', 'twentythirteen' ),
    'edit_item'           => __( 'Edit Location', 'twentythirteen' ),
    'update_item'         => __( 'Update Location', 'twentythirteen' ),
    'search_items'        => __( 'Search Location', 'twentythirteen' ),
    'not_found'           => __( 'Not Found', 'twentythirteen' ),
    'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
  );

  // Set other options for Custom Post Type

  $args = array(
    'label'               => __( 'locations', 'twentythirteen' ),
    'description'         => __( 'Location news and reviews', 'twentythirteen' ),
    'labels'              => $labels,
    // Features this CPT supports in Post Editor
    'supports'            => array( 'title', 'revisions', 'custom-fields', ), // 'editor', 'excerpt', 'author', 'thumbnail', 'comments',
    // You can associate this CPT with a taxonomy or custom taxonomy.
    // 'taxonomies'          => array( 'genres' ),
    /* A hierarchical CPT is like Pages and can have
    * Parent and child items. A non-hierarchical CPT
    * is like Posts.
    */
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    // 'show_in_menu'        => 'edit.php?post_type=locations',
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'capability_type'     => 'page',
  );

  // Registering your Custom Post Type
  register_post_type( 'location', $args );

}, 0 );

/*
  Redefine the columns of the locations
*/
add_filter( 'manage_location_posts_columns', function ( $columns ) {
    $columns = array(
      'cb' => $columns['cb'],
      'image' => __( 'Image' ),
      'title' => __( 'Title' ),
      'place' => __( 'Place' ),
      'street' => __( 'Street' ),
      'type' => __( 'Type' ),
      'date' => __( 'Date' )
    );
  return $columns;
});

/*
  Populate columns
*/
add_action( 'manage_location_posts_custom_column', function ( $column, $post_id ) {
  // Image column
  if ( 'image' === $column ) {
    // Append random value to uncache the image in case of rotation
    if(get_post_meta( $post_id, 'image', true )){
      echo '<img style="max-width:100%;height:auto;" src="'.spGetUploadUrl().'/sp-locations/thumbs/300/'.get_post_meta( $post_id, 'image', true ).'?rand='.rand( 0 , 9999 ).'">';
    } else {
      echo 'Kein Bild';
    }
  }
  if ( 'type' === $column ) {
    $type = get_post_meta($post_id, 'type', true);
    switch($type){
      case 'sign':
        echo 'Unterschreiben';
        break;
      case 'problem':
        echo 'Problemstelle';
        break;
    }
  }
  if ( 'place' === $column ) {
    echo get_post_meta($post_id, 'place', true);
  }
  if ( 'street' === $column ) {
    echo get_post_meta($post_id, 'street', true);
  }
}, 10, 2);

/*
  Add sortable columns
*/
add_filter( 'manage_edit-location_sortable_columns', function ( $columns ) {
  $columns['type'] = 'type';
  $columns['place'] = 'place';
  $columns['street'] = 'street';
  return $columns;
});

/*
  Alter query for sorting
*/
add_action( 'pre_get_posts', function ( $query ) {
  if( ! is_admin() || ! $query->is_main_query() ) {
    return;
  }

  if ( 'type' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'type' );
    $query->set( 'meta_type', 'string' );
  }

  if ( 'place' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'place' );
    $query->set( 'meta_type', 'string' );
  }

  if ( 'street' === $query->get( 'orderby') ) {
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'meta_key', 'street' );
    $query->set( 'meta_type', 'string' );
  }
});

/*
  Add custom filter dropdowns
  https://pluginrepublic.com/how-to-filter-custom-post-type-by-meta-field/
*/
add_action( 'restrict_manage_posts', function () {
  global $typenow;
  global $wp_query;
    if ( $typenow == 'location' ) { // Your custom post type
      $values = array(
        'sign' => 'Unterschreiben',
        'problem' => 'Problemstelle'
      ); // Options for the filter select field
      $current_value = '';
      if( isset( $_GET['type'] ) ) {
        $current_value = $_GET['type']; // Check if option has been selected
      } ?>
      <select name="type" id="type">
        <option value="all" <?php selected( 'all', $current_value ); ?>>Alle Orte</option>
        <?php foreach( $values as $key=>$value ) { ?>
          <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $current_value ); ?>><?php echo esc_attr( $value ); ?></option>
        <?php } ?>
      </select>
  <?php }
} );

/*
  Add custom filter to list query
*/
add_filter( 'parse_query', function ( $query ) {
  global $pagenow;
  // Get the post type
  $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
  if ( is_admin() && $pagenow=='edit.php' && $post_type == 'location' && isset( $_GET['type'] ) && $_GET['type'] !='all' ) {
    $query->query_vars['meta_key'] = 'type';
    $query->query_vars['meta_value'] = $_GET['type'];
    $query->query_vars['meta_compare'] = '=';
  }
});

/*
  Add image rotation function on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  if(isset($_POST['sp_rotate_image'])){

    $rotation_steps = 1;
    $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';
    Sp\Thumbnail::rotate($uploads_dir.'/'.get_post_meta( $post_id, 'image', true ), $rotation_steps*-90);
    Sp\Thumbnail::rotate($uploads_dir.'/thumbs/300/'.get_post_meta( $post_id, 'image', true ), $rotation_steps*-90);
    Sp\Thumbnail::rotate($uploads_dir.'/thumbs/600/'.get_post_meta( $post_id, 'image', true ), $rotation_steps*-90);

  }

}, 10, 3 );

/*
  Upload new image on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  if(isset($_FILES['sp_change_image'])) {

    if(file_exists($_FILES['sp_change_image']['tmp_name'])){

      $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';

      // Get extension
      $path_parts = pathinfo($_FILES['sp_change_image']['name']);
      $ext = strtolower($path_parts['extension']);

      // Move the file
      if (move_uploaded_file($_FILES['sp_change_image']['tmp_name'], $uploads_dir.'/'.$post_id.'.'.$ext)) {
        // Create thumbs
        Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'.'.$ext, $uploads_dir.'/thumbs/600/'.$post_id.'.'.$ext, 600);
        Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'.'.$ext, $uploads_dir.'/thumbs/300/'.$post_id.'.'.$ext, 300);
        // Create meta
        add_post_meta($post_id, 'image', $post_id.'.'.$ext);
      }
    }

  }

   // return $mydata;
}, 10, 3 );

/*
  Enabble file upload in edit form
*/
add_action('post_edit_form_tag', function () {
    echo ' enctype="multipart/form-data"';
});

/*
  Add locations endpoint
*/
add_action('wp_ajax_nopriv_splocationadd', 'sp_location_add');
add_action('wp_ajax_splocationadd', 'sp_location_add');

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

/*
  Disable single view for custom post type in the frontend
*/
add_action( 'template_redirect', function () {
  $queried_post_type = get_query_var('post_type');
  if ( is_single() && 'location' ==  $queried_post_type ) {
    wp_redirect( home_url(), 301 );
    exit;
  }
} );

/*
  Exclude One Content Type From Yoast SEO Sitemap
*/
add_filter( 'wpseo_sitemap_exclude_post_type', function ( $value, $post_type ) {
if ( $post_type == 'location' ) return true;
}, 10, 2 );

/*
  Add Backend meta boxes
*/
add_action( 'add_meta_boxes', function () {

  add_meta_box(
		'sp_marker_image',
		'Uploaded Image',
		function () {
      global $post;
      echo Sp\View::render('backend_image', [
        // Add a random number to the url to uncache the image in case of rotaion
        'src' => spGetUploadUrl().'/sp-locations/'.get_post_meta( $post->ID, 'image', true ).'?rand='.rand( 0 , 9999 ),
        'hasImage' => (get_post_meta( $post->ID, 'image', true ) ? true:false)
      ]);
    },
		'location',
		'normal',
		'high'
	);

  add_meta_box(
		'sp_marker_map',
		'Location',
		function () {
      global $post;
      wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', __FILE__ ) );
      wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', __FILE__ ) );
      echo Sp\View::render('backend_map', [
        'lat' => get_post_meta( $post->ID, 'lat', true ),
        'lng' => get_post_meta( $post->ID, 'lng', true )
      ]);
    },
		'location',
		'normal',
		'high'
	);

} );
