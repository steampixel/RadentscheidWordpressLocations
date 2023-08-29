<?PHP

// Re add the general description

// Find all locations
$locations = get_posts( [
  'numberposts' => -1,
  'post_type' => 'location',
  'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
] );

foreach($locations as $location) {

  $description = get_post_meta($location->ID, 'description', true);
  $images = get_post_meta( $location->ID, 'images', true );

  if(!$description) {

    if($images) {

      echo 'Migrating post '.$location->ID.'<br/>';

      if(isset($images[0]['description'])) {
        // Copy first image description to geneal description field
        update_post_meta($location->ID, 'description', $images[0]['description']);

        // Remove description from image
        $images[0]['description'] = '';
        update_post_meta($location->ID, 'images', $images);
      }

    }

  }

}
