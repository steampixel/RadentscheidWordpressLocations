<?php

function sp_location_add () {

  // Sanitize the required whole input
  $_POST['title'] = filter_var ( $_POST['title'], FILTER_SANITIZE_STRING);
  $_POST['lat'] = filter_var ( $_POST['lat'], FILTER_SANITIZE_STRING);
  $_POST['lng'] = filter_var ( $_POST['lng'], FILTER_SANITIZE_STRING);
  $_POST['type'] = filter_var ( $_POST['type'], FILTER_SANITIZE_STRING);

  // Sanitize the optional input
  if(isset($_POST['street'])){
    $_POST['street'] = filter_var ( $_POST['street'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['house_number'])){
    $_POST['house_number'] = filter_var ( $_POST['house_number'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['postcode'])){
    $_POST['postcode'] = filter_var ( $_POST['postcode'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['suburb'])){
    $_POST['suburb'] = filter_var ( $_POST['suburb'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['contact_person'])){
    $_POST['contact_person'] = filter_var ( $_POST['contact_person'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['opening_hours'])){
    $_POST['opening_hours'] = filter_var ( $_POST['opening_hours'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['email'])){
    $_POST['email'] = filter_var ( $_POST['email'], FILTER_SANITIZE_EMAIL);
  }
  if(isset($_POST['telephone'])){
    $_POST['telephone'] = filter_var ( $_POST['telephone'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['description'])){
    $_POST['description'] = filter_var ( $_POST['description'], FILTER_SANITIZE_STRING);
  }
  if(isset($_POST['solution'])){
    $_POST['solution'] = filter_var ( $_POST['solution'], FILTER_SANITIZE_STRING);
  }

  // Check nonce
  if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'sp_location_add' ) ) {
    echo json_encode([
      "status"  =>  "error",
      "message" => "Der Token für dieses Formular ist abgelaufen. Bitte lade die Seite neu."
    ]);
    wp_die();
  }

  // Check honeypot
  if(isset($_POST['mail'])){
    echo json_encode([
      "status"  =>  "success"
    ]);
    wp_die();
  }

  // Insert new post
  $post_id = wp_insert_post( [
    'post_title' => $_POST['title'],
    'post_type' => 'location',
  ] );

  if($post_id) {

    // Optional image file
    if(isset($_FILES['image'])) {

      // Get extension
      $path_parts = pathinfo($_FILES['image']['name']);
      $ext = strtolower($path_parts['extension']);

      // Create new image name
      $name = uniqid().'.'.$ext;

      // Create upload dir
      $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'sp-locations';

      // Crete thumbnail directorys
      wp_mkdir_p( $uploads_dir );
      wp_mkdir_p( $uploads_dir.'/'.$post_id );
      wp_mkdir_p( $uploads_dir.'/'.$post_id.'/300' );
      wp_mkdir_p( $uploads_dir.'/'.$post_id.'/600' );

      // Check file type
      if(file_exists($_FILES['image']['tmp_name'])) {
        $image_size = getimagesize($_FILES['image']['tmp_name']);
        $image_type = $image_size[2];

        if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {

          // Move the file
          if (move_uploaded_file($_FILES['image']['tmp_name'], $uploads_dir.'/'.$post_id.'/'.$name)) {

            // Create thumbs
            Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$name, $uploads_dir.'/'.$post_id.'/300/'.$name, 300);
            Sp\Thumbnail::create($uploads_dir.'/'.$post_id.'/'.$name, $uploads_dir.'/'.$post_id.'/600/'.$name, 600);

            // Create image meta
            add_post_meta($post_id, 'images', [
              [
                'src' => $name,
                'description' => $_POST['description']
              ]
            ]);

          } else {
            echo json_encode([
              "status"  =>  "error",
              "message" => "Das System konnte das Bild leider nicht verarbeiten :-( Scheinbar ist unser Dynamo ausgefallen. Bitte versuche es erneut."
            ]);
            wp_delete_post( $post_id, true );
            wp_die();
          }

        }else{
          echo json_encode([
            "status"  =>  "error",
            "message" => "Bitte lade nur Dateien vom Typ gif, jpg, png oder bmp hoch. Alles andere ist zu modern."
          ]);
          wp_delete_post( $post_id, true );
          wp_die();
        }
      } else {
        echo json_encode([
          "status"  =>  "error",
          "message" => "Das Bild ist scheinbar größer als erlaubt :-( Wir arbeiten noch an einer Lösung. Wir entschuldigen uns für das Problem und werden unsere Admins selbstverständlich umgehend entlassen."
        ]);
        wp_delete_post( $post_id, true );
        wp_die();
      }

    }

    // Add required metas
    add_post_meta($post_id, 'lat', $_POST['lat']);
    add_post_meta($post_id, 'lng', $_POST['lng']);
    add_post_meta($post_id, 'type', $_POST['type']);

    // Add optional metas
    if(isset($_POST['street'])){
      add_post_meta($post_id, 'street', $_POST['street']);
    }
    if(isset($_POST['house_number'])){
      add_post_meta($post_id, 'house_number', $_POST['house_number']);
    }
    if(isset($_POST['postcode'])){
      add_post_meta($post_id, 'postcode', $_POST['postcode']);
    }
    if(isset($_POST['place'])){
      add_post_meta($post_id, 'place', $_POST['place']);
    }
    if(isset($_POST['suburb'])){
      add_post_meta($post_id, 'suburb', $_POST['suburb']);
    }
    if(isset($_POST['contact_person'])){
      add_post_meta($post_id, 'contact_person', $_POST['contact_person']);
    }
    if(isset($_POST['opening_hours'])){
      add_post_meta($post_id, 'opening_hours', $_POST['opening_hours']);
    }
    if(isset($_POST['email'])){
      add_post_meta($post_id, 'email', $_POST['email']);
    }
    if(isset($_POST['telephone'])){
      add_post_meta($post_id, 'telephone', $_POST['telephone']);
    }
    if(isset($_POST['solution'])){
      add_post_meta($post_id, 'solution', $_POST['solution']);
    }

    echo json_encode([
      "status"  =>  "success"
    ]);
    wp_die();

  } else {
    echo json_encode([
      "status"  =>  "error",
      "message" => "Das tut uns sehr leid! Das System kann die Anfrage derzeit nicht verarbeiten :-("
    ]);
    wp_die();
  }

}

/*
  Add locations endpoint
*/
add_action('wp_ajax_nopriv_splocationadd', 'sp_location_add');
add_action('wp_ajax_splocationadd', 'sp_location_add');
