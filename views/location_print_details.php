<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  </head>

  <body>

    <?PHP

      $type = get_post_meta( $post_id, 'type', true );

      if($type) {

        $type_posts = get_posts( [
          'numberposts' => -1,
          'post_type' => 'marker',
          'meta_query' => array(
             array(
               'key' => 'key',
               'value' => $type,
               'compare' => '='
             )
         )
        ] );

        if($type_posts) {
          ?>
          <h1><?=$type_posts[0]->post_title ?>: <?=$post_title ?></h1>
          <?PHP
        }

      }

    ?>

    <div id="mymap" style="height:500px;width:1000px;"></div>

    <p>
      <strong>Adresse</strong><br>
      <?=get_post_meta($post_id, 'street', true) ?> <?=get_post_meta($post_id, 'house_number', true) ?><br>
      <?=get_post_meta($post_id, 'postcode', true) ?> <?=get_post_meta($post_id, 'place', true) ?><br>
      <!-- <?=get_post_meta($post_id, 'suburb', true) ?> -->
    </p>

    <?PHP
    $description = get_post_meta($post_id, 'description', true);
    ?>

    <p>
      <?=nl2br($description) ?>
    </p>

    <?PHP
    $opening_hours = nl2br(get_post_meta($post_id, 'opening_hours', true));

    if($opening_hours){
      ?>
      <p>
        <strong>Öffnungszeiten:</strong> <?=$opening_hours ?>
      </p>
      <?PHP
    }
    ?>

    <h2>Bilder</h2>

    <?PHP

      $images = get_post_meta( $post_id, 'images', true );

      if($images) {

        ?>

          <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
            <?php

            foreach($images as $key => $image) {

              ?>
                <tr>

                    <td>
                      <img src="<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$image['src'] ?>">
                    </td>

                    <td style="padding-left:25px;">
                      <?PHP
                      if($image['description']) {
                        ?>
                        <?=$image['description'] ?>
                        <?PHP
                      }
                      ?>
                    </td>

                </tr>
              <?PHP
            }
            ?>
          </table>
        <?php

      }
    ?>

    <link rel='stylesheet' type='text/css' media='all' href="<?=plugins_url( 'assets/libs/leaflet/leaflet.css', dirname(__FILE__ )) ?>" />
    <script type='text/javascript' src="<?=plugins_url( 'assets/libs/leaflet/leaflet.js', dirname(__FILE__ )) ?>"></script>
    <script type='text/javascript' src="<?=plugins_url( 'assets/js/app.js', dirname(__FILE__ )) ?>"></script>
    <link rel='stylesheet' type='text/css' media='all' href="<?=plugins_url( 'assets/css/app.css', dirname(__FILE__ )) ?>" />

    <script>

    <?PHP

    $markers = get_posts( [
      'post_type' => 'marker',
      'meta_query' => array(
         array(
           'key' => 'key',
           'value' => trim(get_post_meta($post_id, 'type', true)),
           'compare' => '='
         )
     )
    ] );

    if($markers) {

      $marker = $markers[0];
      $marker_icon = get_post_meta($marker->ID, 'icon', true);

    }

    ?>

    document.addEventListener("DOMContentLoaded", function(event) {

      var mymap = L.map('mymap').setView([<?=get_post_meta($post_id, 'lat', true) ?>, <?=get_post_meta($post_id, 'lng', true) ?>], 18);

      mymap.attributionControl.addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors');

      L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {

      }).addTo(mymap);

      var icon = L.divIcon({
         className: 'map-marker',
         iconSize:null,
         html:'<div class="sp-map-marker sp-map-marker-<?=get_post_meta($post_id, 'type', true) ?>" title="<?=$post_title ?>"><img style="height:100%;width:auto;" src="<?=$marker_icon ?>"></div>'
       });

      // Add marker
      mymarker = L.marker([<?=get_post_meta($post_id, 'lat', true) ?>, <?=get_post_meta($post_id, 'lng', true) ?>], {icon: icon}).addTo(mymap);

    });

    </script>

  </body>

</html>
