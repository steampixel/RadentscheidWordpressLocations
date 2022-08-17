<?PHP

/*
  Add custom post type
*/
add_action( 'init', function () {

  // Set UI labels for Custom Post Type
  $labels = array(
    'name'                => 'Locations',
    'singular_name'       => 'Location',
    'menu_name'           => 'Locations',
    'parent_item_colon'   => 'Parent Location',
    'all_items'           => 'All Locations',
    'view_item'           => 'View Location',
    'add_new_item'        => 'Add New Location',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Location',
    'update_item'         => 'Update Location',
    'search_items'        => 'Search Location',
    'not_found'           => 'Not Found',
    'not_found_in_trash'  => 'Not found in Trash',
  );

  // Set other options for Custom Post Type

  $args = array(
    'label'               => 'locations',
    'description'         => 'Location news and reviews',
    'labels'              => $labels,
    // Features this CPT supports in Post Editor
    'supports'            => array( 'title', 'revisions', 'custom-fields', 'comments' ), // 'editor', 'excerpt', 'author', 'thumbnail', 'comments',
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

    $images = get_post_meta( $post_id, 'images', true );

    // Append random value to uncache the image in case of rotation
    if($images) {
      echo '<img style="max-width:100%;height:auto;" src="'.spGetUploadUrl().'/sp-locations/'.$post_id.'/300/'.$images[0]['src'].'?rand='.rand( 0 , 9999 ).'">';
    } else {
      echo 'Kein Bild';
    }
  }
  if ( 'type' === $column ) {
    $type = get_post_meta($post_id, 'type', true);
    echo $type;
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

  $markers = get_posts( [
    'numberposts' => -1,
    'post_type' => 'marker'
  ] );

  if ( $typenow == 'location' ) { // Your custom post type

    $current_value = '';
    if( isset( $_GET['type'] ) ) {
      $current_value = $_GET['type']; // Check if option has been selected
    } ?>
    <select name="type" id="type">
      <option value="all" <?php selected( 'all', $current_value ); ?>>Alle Orte</option>
      <?php foreach($markers as $marker){ ?>
        <option value="<?=get_post_meta( $marker->ID, 'key', true ) ?>" <?=($type==get_post_meta( $marker->ID, 'key', true )? 'selected=':'') ?>><?=$marker->post_title ?></option>
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
  Add image remove function on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // For each image in this post
  $images = get_post_meta( $post_id, 'images', true );
  if($images) {

    $save_images = $images;

    foreach($images as $key => $image) {

      if(isset($_POST['sp_edit_image_remove_'.$key])) {

        // Remove from array
        unset($save_images[$key]);

        // Unlink from filesystem
        $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';
        unlink($uploads_dir.'/'.$post_id.'/'.$image['src']);
        unlink($uploads_dir.'/'.$post_id.'/300/'.$image['src']);
        unlink($uploads_dir.'/'.$post_id.'/600/'.$image['src']);

      }

    }

    update_post_meta($post_id, 'images', $save_images);

  }

}, 10, 3 );

/*
  Add image rotation function on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // For each image in this post
  $images = get_post_meta( $post_id, 'images', true );
  if($images) {

    foreach($images as $key => $image) {

      if(isset($_POST['sp_edit_image_rotate_'.$key])) {

        $rotation_steps = 1;
        $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';
        Sp\Thumbnail::rotate($uploads_dir.'/'.$post_id.'/'.$image['src'], $rotation_steps*-90);
        Sp\Thumbnail::rotate($uploads_dir.'/'.$post_id.'/300/'.$image['src'], $rotation_steps*-90);
        Sp\Thumbnail::rotate($uploads_dir.'/'.$post_id.'/600/'.$image['src'], $rotation_steps*-90);

      }

    }

  }


}, 10, 3 );

/*
  Upload new images on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  if(isset($_FILES['sp_create_image_src'])) {

    if(file_exists($_FILES['sp_create_image_src']['tmp_name'])){

      $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';

      // Get extension
      $path_parts = pathinfo($_FILES['sp_create_image_src']['name']);
      $ext = strtolower($path_parts['extension']);

      // Create file name
      $name = uniqid().'.'.$ext;

      // Create image dirs
      wp_mkdir_p( $uploads_dir.'/'.$post_id );
      wp_mkdir_p( $uploads_dir.'/'.$post_id.'/300' );
      wp_mkdir_p( $uploads_dir.'/'.$post_id.'/600' );

      // Move the file
      if (move_uploaded_file($_FILES['sp_create_image_src']['tmp_name'], $uploads_dir.'/'.$post_id.'/'.$name)) {
        // Create thumbs
        Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$name, $uploads_dir.'/'.$post_id.'/300/'.$name, 300);
        Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$name, $uploads_dir.'/'.$post_id.'/600/'.$name, 600);

        // Create or update images meta
        $images = get_post_meta( $post_id, 'images', true );
        if(!$images) {
          $images = [];
        }

        array_push($images, [
          'src' => $name,
          'description' => $_REQUEST['sp_create_image_description']
        ]);

        update_post_meta($post_id, 'images', $images);
      }
    }

  }

}, 10, 3 );

/*
  Replace images on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // For each image in this post
  $images = get_post_meta( $post_id, 'images', true );
  if($images) {
    foreach($images as $key => $image) {

      if(isset($_FILES['sp_edit_image_src_'.$key])) {

        if(file_exists($_FILES['sp_edit_image_src_'.$key]['tmp_name'])){

          $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';

          // Get extension
          $path_parts = pathinfo($_FILES['sp_edit_image_src_'.$key]['name']);
          $ext = strtolower($path_parts['extension']);

          // Create file name
          $name = uniqid().'.'.$ext;

          // Move the file
          if (move_uploaded_file($_FILES['sp_edit_image_src_'.$key]['tmp_name'], $uploads_dir.'/'.$post_id.'/'.$name)) {

            // Delete the old images
            unlink($uploads_dir.'/'.$post_id.'/'.$image['src']);
            unlink($uploads_dir.'/'.$post_id.'/300/'.$image['src']);
            unlink($uploads_dir.'/'.$post_id.'/600/'.$image['src']);

            // Create thumbs
            Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$name, $uploads_dir.'/'.$post_id.'/300/'.$name, 300);
            Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$name, $uploads_dir.'/'.$post_id.'/600/'.$name, 600);

            // Update the image in array
            $images[$key] = [
              'src' => $name,
              'description' => $_REQUEST['sp_create_image_description']
            ];

            update_post_meta($post_id, 'images', $images);

          }
        }

      }
    }
  }

}, 10, 3 );

/*
  Replace image descriptions on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  // For each image in this post
  $images = get_post_meta( $post_id, 'images', true );
  if($images) {
    foreach($images as $key => $image) {

      if(isset($_REQUEST['sp_edit_image_description_'.$key])) {

        // Update the image in array
        $images[$key]['description'] = $_REQUEST['sp_edit_image_description_'.$key];

        update_post_meta($post_id, 'images', $images);

      }
    }
  }

}, 10, 3 );

/*
  Save options on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  if(isset($_POST['sp_change_type'])) {

    update_post_meta($post_id, 'type', $_POST['sp_change_type']);

  }

   // return $mydata;
}, 10, 3 );

/*
  Remove activists data on save
*/
add_action( 'save_post_location', function ( $post_id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  if(isset($_POST['sp_remove_activist_data'])) {
    delete_post_meta($post_id, 'contact_person');
    delete_post_meta($post_id, 'email');
    delete_post_meta($post_id, 'telephone');
  }

   // return $mydata;
}, 10, 3 );

/*
  Disable single view for custom post type in the frontend
*/
// add_action( 'template_redirect', function () {
//   $queried_post_type = get_query_var('post_type');
//   if ( is_single() && 'location' ==  $queried_post_type ) {
//     wp_redirect( home_url(), 301 );
//     exit;
//   }
// } );

/*
  Add a filter that modifyes the content of our location post type.
  Because a single view of this post type should not implemented using a custom file in the theme.
  The custom post type single view should work out of the box.
*/
add_filter( 'the_content', function ( $content ) {

    // Check if we're inside the main loop in a single post page.
    // Also check the post type
    if ( is_single() && in_the_loop() && is_main_query() && get_post_type()=='location') {
      wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', dirname(__FILE__ )) );
      wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', dirname(__FILE__ )) );
      wp_enqueue_script( 'steampixel-map-marker', plugins_url( 'assets/js/app.js', dirname(__FILE__ )) );
      wp_enqueue_style( 'steampixel-map-marker', plugins_url( 'assets/css/app.css', dirname(__FILE__ )) );
      return $content . Sp\View::render('location_details', [
        'post_id' => get_the_ID(),
        'post_title' => get_the_title()
      ]);
    }

    return $content;
} );

/*
  Add meta data to location detail pages
*/
add_action( 'wp_head', function () {

  // Check is we are in author archive
  // https://developer.wordpress.org/reference/functions/is_author/
  if ( is_single() && get_post_type()=='location') {

    $post_id = get_the_ID();
    $post_description = get_post_meta( $post_id, 'description', true );
    $post_title = get_the_title();
    $post_url = get_permalink();
    $post_images = get_post_meta( $post_id, 'images', true );

    echo '<meta name="twitter:card" content="summary" />'."\n";
    echo '<meta property="og:type" content="article" />'."\n";
    echo '<meta property="og:title" content="'.htmlentities($post_title).'" />'."\n";
    echo '<meta property="og:description" content="'.htmlentities($post_description).'" />'."\n";
    echo '<meta property="og:url" content="'.$post_url.'" />'."\n";

    // Append random value to uncache the image in case of rotation
    if($post_images) {
      echo '<meta property="og:image" content="'.spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$post_images[0]['src'].'" />'."\n";
    }

  }

} );

/*
  Exclude One Content Type From Yoast SEO Sitemap
*/
// add_filter( 'wpseo_sitemap_exclude_post_type', function ( $value, $post_type ) {
// if ( $post_type == 'location' ) return true;
// }, 10, 2 );

/*
  Add Backend meta boxes
*/
add_action( 'add_meta_boxes', function () {

  add_meta_box(
		'sp_marker_images',
		'Uploaded Images',
		function () {
      global $post;
      echo Sp\View::render('backend_images', ['post_id'=>$post->ID]);
      //[
        // 'imageIds' = get_post_meta( $post->ID, 'image' )
        // Add a random number to the url to uncache the image in case of rotaion
        // 'src' => spGetUploadUrl().'/sp-locations/'.get_post_meta( $post->ID, 'image', true ).'?rand='.rand( 0 , 9999 ),
        // 'hasImage' => !empty(get_post_meta( $post->ID, 'image', true ))
      //]
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
      wp_enqueue_style( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.css', dirname(__FILE__ )) );
      wp_enqueue_script( 'leaflet', plugins_url( 'assets/libs/leaflet/leaflet.js', dirname(__FILE__ )) );
      echo Sp\View::render('backend_map', [
        'lat' => get_post_meta( $post->ID, 'lat', true ),
        'lng' => get_post_meta( $post->ID, 'lng', true ),
        'description' => get_post_meta( $post->ID, 'description', true ),
        'solution' => get_post_meta( $post->ID, 'solution', true ),
        'opening_hours' => get_post_meta( $post->ID, 'opening_hours', true ),
        'street' => get_post_meta( $post->ID, 'street', true ),
        'house_number' => get_post_meta( $post->ID, 'house_number', true ),
        'suburb' => get_post_meta( $post->ID, 'suburb', true ),
        'postcode' => get_post_meta( $post->ID, 'postcode', true ),
        'place' => get_post_meta( $post->ID, 'place', true )
      ]);
    },
		'location',
		'normal',
		'high'
	);

  add_meta_box(
		'sp_marker_activist',
		'Activist',
		function () {
      global $post;
      wp_enqueue_script( 'jsencrypt', plugins_url( 'assets/libs/jsencrypt/bin/jsencrypt.min.js', dirname(__FILE__ )) );
      echo Sp\View::render('backened_location_activist', [
        'contact_person' => get_post_meta( $post->ID, 'contact_person', true ),
        'email' => get_post_meta( $post->ID, 'email', true ),
        'telephone' => get_post_meta( $post->ID, 'telephone', true ),
        'rsa_public_key' => get_post_meta( $post->ID, 'rsa_public_key', true ),
      ]);
    },
		'location',
		'normal',
		'high'
	);

  add_meta_box(
		'sp_marker',
		'Options',
		function () {
      global $post;
      echo Sp\View::render('backend_location_options', [
        'type' => get_post_meta( $post->ID, 'type', true )
      ]);
    },
		'location',
		'normal',
		'high'
	);

} );
