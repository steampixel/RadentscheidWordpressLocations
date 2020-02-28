<?PHP

// Convert all location images to array
// So we can have multiple images in future

// Create upload dir
$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';

// Find all locations
$locations = get_posts( [
  'numberposts' => -1,
  'post_type' => 'location',
  'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
] );

foreach($locations as $location) {

  // Get the old image and description fields from this location
  $image = get_post_meta( $location->ID, 'image', true );
  // $description = get_post_meta($location->ID, 'description', true);
  $images = get_post_meta( $location->ID, 'images', true );

  if(!$images) {

    echo 'Migrating post '.$location->ID.'<br/>';

    // Create image dir for each post
    wp_mkdir_p( $uploads_dir.'/'.$location->ID );
    wp_mkdir_p( $uploads_dir.'/'.$location->ID.'/300' );
    wp_mkdir_p( $uploads_dir.'/'.$location->ID.'/600' );

    if($image) {

      // Get image infos
      $path_parts = pathinfo($uploads_dir.'/'.$image);
      $ext = strtolower($path_parts['extension']);

      // Create a random name for the image
      $name = uniqid().'.'.$ext;

      // Now copy the image to its new location
      copy($uploads_dir.'/'.$image, $uploads_dir.'/'.$location->ID.'/'.$name);

      // Create the new thumbnails for this image
      Sp\Thumbnail::create($uploads_dir.'/'.$location->ID.'/'.$name, $uploads_dir.'/'.$location->ID.'/300/'.$name, 300);
      Sp\Thumbnail::create($uploads_dir.'/'.$location->ID.'/'.$name, $uploads_dir.'/'.$location->ID.'/600/'.$name, 600);

      // Create a new meta value that can handle multiple images
      add_post_meta($location->ID, 'images', [
        [
          'src' => $name,
          'description' => ''
        ]
      ]);

    }


  }

}
