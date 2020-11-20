<?PHP

// Test this API with:
// Please do a database backup before you test this!
// Also backup the wp-content/uploads/sp-locations folder!
// http://localhost/wp-admin/admin-ajax.php?action=splocationimport&url=https://www.radentscheid-wuerzburg.de/api/locations&type_map={"problem":"problem_bike","solved":"solved_bike"}

// Check if should execute this route
function sp_location_import() {

  // Build the type map
  if(isset($_GET['type_map'])) {
    $type_map = json_decode(stripslashes($_GET['type_map']), true);
    if($type_map === false) {
      echo 'Error decoding type map.';
      exit;
    }
  } else {
    $type_map = [];
  }

  // echo $_GET['type_map'];
  // print_r($type_map);
  // exit;

  $import_max = 10;

  // Parse the current url
  $path_only = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

  // Define import url
  $url = $_GET['url'];

  // Initializing curl
  $ch = curl_init($url);

  // Configuring curl options
  $options = array(
    CURLOPT_RETURNTRANSFER => true
  );

  // Setting curl options
  curl_setopt_array( $ch, $options );

  // Getting results
  $result = curl_exec($ch);

  // Parse result
  $locations = json_decode($result, true);
  if($locations === false) {
    echo 'Error decoding remote locations.';
    exit;
  }

  $import_max_count = 0;

  // For each result location
  foreach($locations as $location) {

    if($import_max_count>=$import_max) {
      continue;
    }

    echo 'Importing post with id '.$location['id'];

    // Check if this location post is already part of the local db
    $args = array(
      'meta_query'        => array(
        array(
          'key'       => 'import_id',
          'value'     => $location['id']
        )
      ),
      'post_type'         => 'location',
      'posts_per_page'    => '1'
    );

    $posts = get_posts($args);

    if(!count ($posts)) {

      echo ' -> Storing post';

      // Insert new post
      $post_id = wp_insert_post( [
        'post_title' => $location['title'],
        'post_type' => 'location',
        'post_status' => 'publish',
        'post_date' => $location['date']
      ] );

      if($post_id) {

        // Add images
        foreach($location['images'] as $image) {

          // Create upload dir
          $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';

          // Crete thumbnail directorys
          wp_mkdir_p( $uploads_dir );
          wp_mkdir_p( $uploads_dir.'/'.$post_id );
          wp_mkdir_p( $uploads_dir.'/'.$post_id.'/300' );
          wp_mkdir_p( $uploads_dir.'/'.$post_id.'/600' );

          // Get imae name
          $image_name = basename($image['src']);

          // Grab image
          grab_image($image['src'], $uploads_dir.'/'.$post_id.'/'.$image_name);

          // Create thumbs
          Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$image_name, $uploads_dir.'/'.$post_id.'/300/'.$image_name, 300);
          Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$image_name, $uploads_dir.'/'.$post_id.'/600/'.$image_name, 600);

          // Create image meta
          add_post_meta($post_id, 'images', [
            [
              'src' => $image_name,
              'description' => $image['description']
            ]
          ]);

        }

        if(isset($location['lat'])) {add_post_meta($post_id, 'lat', $location['lat']); }
        if(isset($location['lng'])) {add_post_meta($post_id, 'lng', $location['lng']); }
        if(isset($location['street'])) {add_post_meta($post_id, 'street', $location['street']); }
        if(isset($location['house_number'])) {add_post_meta($post_id, 'house_number', $location['house_number']); }
        if(isset($location['postcode'])) {add_post_meta($post_id, 'postcode', $location['postcode']); }
        if(isset($location['place'])) {add_post_meta($post_id, 'place', $location['place']); }
        if(isset($location['suburb'])) {add_post_meta($post_id, 'suburb', $location['suburb']); }

        // Transform the location type
        if(array_key_exists($location['type'], $type_map)) {
          echo ' transforming type from '.$location['type'].' to '.$type_map[$location['type']];
          add_post_meta($post_id, 'type', $type_map[$location['type']]);
        } else {
          add_post_meta($post_id, 'type', $location['type']);
        }

        // Add metas
        add_post_meta($post_id, 'import_id', $location['id']);
        add_post_meta($post_id, 'import_url', $url);

        // add_post_meta($post_id, 'opening_hours', $location['opening_hours']);
        add_post_meta($post_id, 'description', $location['description']);
        // add_post_meta($post_id, 'solution', $location['solution']);

        // add_post_meta($post_id, 'rsa_public_key', $location['rsa_public_key']);
        // add_post_meta($post_id, 'contact_person', $location['contact_person']);
        // add_post_meta($post_id, 'email', $location['email']);
        // add_post_meta($post_id, 'telephone', $location['telephone']);

      }

      echo ' -> OK';

      $import_max_count++;

    } else {

      // Update some basic values
      wp_update_post([
        'ID' => $posts[0]->ID,
        'post_title' => $location['title'],
        'post_date' => $location['date']
      ]);

      echo ' -> Already imported -> Updating some basic values';

    }

    echo '<br>';

  }

  exit;

}

/*
  Add locations endpoint
*/
// add_action('wp_ajax_nopriv_splocationimport', 'sp_location_import');
add_action('wp_ajax_splocationimport', 'sp_location_import');
