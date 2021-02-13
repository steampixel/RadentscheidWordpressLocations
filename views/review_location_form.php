<?PHP
if ( !(current_user_can('editor') || current_user_can('administrator') ) ) return;

$post_id = intval($_GET['id']);
$post = get_post( $post_id );
$action = $_GET['action'];
$next = 0;
if ($action) {
    if ($action == 'next') {
        $next = 1;
    }
    else if ($action == 'approve') {
        if ($post->post_status != 'publish') {
            $post->post_status = 'publish';
            wp_update_post( $post );
            ?> <div class="sp-xhr-form-success sp-has-text-green">Post "<?=$post->post_title ?>" freigegeben.</div> <?PHP
            $next = 1;
        }
        else {
            ?> <h2>Post <?=$post_id ?> has already been approved.</h2> <?PHP
        }
    }
    else if ($action == 'update') { // update location information
            $post->post_status = 'publish';
            if ($_GET['lat']) update_post_meta( $post_id, 'lat', $_GET['lat']);
            if ($_GET['lng']) update_post_meta( $post_id, 'lng', $_GET['lng']);
            if ($_GET['place']) update_post_meta( $post_id, 'place', $_GET['place']);
            if ($_GET['$postcode']) update_post_meta( $post_id, '$postcode', $_GET['$postcode']);
            if ($_GET['street']) update_post_meta( $post_id, 'street', $_GET['street']);
            if ($_GET['$house_number']) update_post_meta( $post_id, '$house_number', $_GET['$house_number']);
            wp_update_post( $post );
            ?> <div class="sp-xhr-form-success sp-has-text-green">Post "<?=$post->post_title ?>" mit neuer Position freigegeben.</div> <?PHP
            $next = 1;
        ?> 
        <h2>Update <?=$post_id ?> </h2>
        <?PHP
    }
    else if ($action == 'delete') {
        $post->post_status = 'trash';
        wp_update_post( $post );
        ?> <h2>Deleted <?=$post->post_title ?> </h2> <?PHP
    }
    else {
        return;
    }
}
if ($next) {
    $query = new WP_Query ( array ( 'post_type' => 'location', 'post_status' => 'draft', 'posts_per_page' => -1));
    $pending = array_reverse($query->posts);
    if (count($pending) > 0) {
        ?> <h2><?=count($pending) ?> reviews pending.</h2> <?PHP
        $i = 0;
        for (; $i < count($pending); $i++) {
            if ($pending[$i]->ID > $post_id) break;
        }
        if ($i >= count($pending)) $i = 0;
        $post = $pending[$i];
        $post_id = $post->ID;
        ?> <h2>Advance to <?=$post_id ?></h2> <?PHP
    }
    else {
        ?> <h2>No pending reviews.</h2> <?PHP
        return;
    }
}

$title  = $post->post_title;
$type = get_post_meta( $post_id, 'type', true );
$images = get_post_meta( $post_id, 'images', true );
$solution = nl2br(get_post_meta($post_id, 'solution', true));
$description = nl2br(get_post_meta($post_id, 'description', true));
$lat = get_post_meta( $post_id, 'lat', true );
$lng = get_post_meta( $post_id, 'lng', true );
$postcode = get_post_meta( $post_id, 'postcode', true );
$place = get_post_meta( $post_id, 'place', true );
$street = get_post_meta( $post_id, 'street', true );
$house_number = get_post_meta( $post_id, 'house_number', true );
$images = get_post_meta( $post_id, 'images', true );
$contact = get_post_meta($post_id, 'contact_person', true);
$email = get_post_meta($post_id, 'email', true);
$phone = get_post_meta($post_id, 'telephone', true);


if ($post->post_type != 'location' ) {
    ?> <div class="sp-xhr-form-success sp-has-text-red">Unexpected post type "<?=$post->post_type ?>".</div> <?PHP
    return;
}
if ($post->post_status == 'publish' ) {
    ?> <div class="sp-xhr-form-success">Location ist bereits &ouml;ffentlich.</div> <?PHP
}
?>

<form method="get" action="/review" autocomplete="off" onsubmit="return spOnReviewSubmit('<?=get_option('sp-locations_form_review_url').'?id='.$post_id; ?>');" >

  <input id="id" type='hidden' name='id' value="<?=$post_id ?>">

  <div class="sp-xhr-form-fields">

    <div class="sp-columns sp-has-margin-bottom-2">
      <div class="sp-column is-full">
        <div class="sp-has-margin-bottom-2">
          <label for="title">Titel</label>
          <input id="title" required minlength="1" maxlength="200" class="sp-xhr-form-data sp-xhr-form-input" type="text" name="title" value="<?=$title ?>">
        </div>

        <div class="sp-has-margin-bottom-2">
          <label for="type">Kategorie</label>
          <select required class="sp-xhr-form-data sp-xhr-form-input" name="type" id="type" value="<?=$type ?>">
            <option value="problem_bike">Fahrrad</option>
            <option value="problem_foot_path">Fussweg</option>
            <option value="problem_bus">Bus/Bahn</option>
            <?PHP
/*            $available_types = array("problem_bike", "problem_bus", "problem_foot_path");
            foreach($available_types as $available_type) {
              $marker_key = get_post_meta($available_type->ID, 'key', true);
              ?>
              <option value="<?=$marker_key?>" <?=($selected_type==$marker_key? 'selected':'') ?>><?=$available_type->post_title ?></option>
              <?PHP
            }
*/            ?>
          </select>
        </div>

        <div class="sp-has-margin-bottom-2">
        <?PHP
       foreach($images as $key => $image) {
        ?>
          <label class="sp-file-uploader">
            <img class="sp-file-uploader-image" src="<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$image['src'] ?>">
            <input id="upload_file" class="sp-xhr-form-data sp-xhr-form-input sp-file-uploader-input" name="image" type="file" accept="image/*">
            <span class="sp-file-uploader-label">
              <?=$file_placeholder ?>
            </span>
          </label>
        <?PHP
        }
        ?>

        </div>
      </div>
    </div>

    <div class="sp-location-picker sp-has-margin-bottom-2" data-lat="<?=$lat ?>" data-lng="<?=$lng ?>" data-zoom="<?=$zoom ?>" data-marker-icon="<?=plugin_dir_url(__DIR__).'assets/img/marker.svg'; ?>">
      <label for="street">Adresse und Position auf der Karte</label>
      <div class="sp-columns enable-wrap">

        <div class="sp-column is-full is-half-tablet sp-has-padding-right-1-tablet sp-has-margin-bottom-2">
          <div class="sp-location-picker-map"></div>
          <input type="hidden" class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-lat" name="lat" value="<?=$lat?>">
          <input type="hidden" class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-lng" name="lng" value="<?=$lng?>">
          <input type="hidden" class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-suburb" name="suburb">
          <div class="sp-location-picker-map-hint sp-xhr-form-hint sp-has-text-red"></div>
        </div>

        <div class="sp-column is-full is-half-tablet sp-has-padding-left-1-tablet">

          <div class="sp-has-margin-bottom-2">
            <label for="street">Straße und Hausnummer</label>
            <div class="sp-columns">
              <div class="sp-column is-two-third">
                <input id="street" minlength="1" maxlength="200" class="sp-location-picker-input-street sp-xhr-form-data sp-xhr-form-input" type="text" name="street" value="<?=$street ?>">
              </div>
              <div class="sp-column is-one-third">
                <input minlength="1" maxlength="10"  class="sp-location-picker-input-house-number sp-xhr-form-data sp-xhr-form-input" type="text" name="house_number"  value="<?=$house_number ?>">
              </div>
            </div>
          </div>

          <div class="sp-has-margin-bottom-2">
            <label for="postcode">Postleitzahl und Ort</label>
            <div class="sp-columns">
              <div class="sp-column is-one-third">
                <input id="postcode" class="sp-location-picker-input-postcode sp-xhr-form-data sp-xhr-form-input" type="number" name="postcode"  value="<?=$postcode ?>">
              </div>
              <div class="sp-column is-two-third">
                <input minlength="1" maxlength="200" class="sp-location-picker-input-place sp-xhr-form-data sp-xhr-form-input" type="text" name="place" value="<?=$place ?>">
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

    <?PHP
    // This is a simple honeypot
    // The form will only simulate the success if this field gets filled
    ?>
    <label class="sp-fg56bn67">
      Email
      <input class="sp-xhr-form-data sp-xhr-form-input" type="email" name="mail">
    </label>

      <div class="sp-columns enable-wrap">

        <div class="sp-column is-full">

          <div class="sp-has-margin-bottom-2">
            <label for="description">Beschreibung</label>
            <textarea minlength="1" maxlength="2000" class="sp-xhr-form-data sp-xhr-form-input" name="description" id="description"><?=$description ?></textarea>
          </div>

          <div class="sp-has-margin-bottom-2">
            <label for="solution">Lösungsvorschlag</label>
            <textarea minlength="1" maxlength="2000" class="sp-xhr-form-data sp-xhr-form-input" name="solution" id="solution"><?=$solution ?></textarea>
          </div>

        </div>
      </div>

    <div class="sp-columns">
      <div class="sp-column is-full">

        <div class="sp-has-margin-bottom-2">
          Kontaktdaten
        </div>

        <div class="sp-has-margin-bottom-2">
          <label for="contact_person">Name</label>
          <input data-encrypt="true" minlength="1" maxlength="200" lass="sp-xhr-form-data sp-xhr-form-input" type="text" name="contact_person" id="contact_person" value="<?=$contact ?>">
        </div>

      </div>
    </div>

    <div class="sp-columns enable-wrap">

      <div class="sp-column is-full is-half-tablet sp-has-padding-right-1-tablet">
        <div class="sp-has-margin-bottom-2">
          <label for="email">Email</label>
          <input data-encrypt="true" minlength="1" maxlength="200" class="sp-xhr-form-data sp-xhr-form-input" type="email" name="email" id="email" value="<?=$email ?>">
        </div>
      </div>

      <div class="sp-column is-full is-half-tablet sp-has-padding-left-1-tablet">
        <div class="sp-has-margin-bottom-2">
          <label for="telephone">Telefonnummer</label>
          <input data-encrypt="true" minlength="1" maxlength="200" class="sp-xhr-form-data sp-xhr-form-input" type="text" name="telephone" id="telephone" value="<?=$phone ?>">
        </div>
      </div>

    </div>

    <div>
      <?php wp_nonce_field( 'sp_location_approve' ); ?>
      <label for="contact_person">Aktion</label>
      <select class="sp-xhr-form-data sp-xhr-form-input" name="action" id="action">
        <option selected value='next'>N&auml;chster</option>
        <option value='approve'>Freigeben</option>
        <option value='update'>Mit korrigierter Position freigeben</option>
        <option value='delete'>L&ouml;schen</option>
      </select>
    </div>
    <div class="sp-columns">
      <input type="submit" value="Ausf&uuml;hren">
    </div>

  </div>

</form>
