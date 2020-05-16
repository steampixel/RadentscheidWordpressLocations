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
      <?=$type_posts[0]->post_title ?>
      <?PHP
    }

  }

?>

<div id="mymap" class="steampixel-marker-map" style="height:300px;"></div>

<?PHP

$solution = nl2br(get_post_meta($post_id, 'solution', true));
$opening_hours = nl2br(get_post_meta($post_id, 'opening_hours', true));

?>

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
if($opening_hours){
  ?>
  <p>
    <strong>Öffnungszeiten:</strong> <?=$opening_hours ?>
  </p>
  <?PHP
}
?>

<?PHP

  $images = get_post_meta( $post_id, 'images', true );

  if($images) {

    foreach($images as $key => $image) {

      ?>

      <div id="<?=$key; ?>" class="sp-modal">

        <div class="sp-modal-content">

          <div class="sp-modal-close" onclick="closeModal('<?=$key; ?>')">×</div>
          <img class="sp-has-margin-bottom-2" src='<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$image['src'] ?>'>

          <input type="button" value="schließen" onclick="closeModal('<?=$key; ?>')">

        </div>
      </div>

      <?PHP

    }

    ?>

      <div class="sp-cards">
        <?php

        foreach($images as $key => $image) {

          ?>
            <div class="sp-card">

              <div class="sp-card-content">

                <div class="sp-card-image-wrapper" onclick="openModal('<?=$key; ?>')">
                  <div class="sp-card-image-background" style="background-image:url(<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$image['src'] ?>);"></div>
                  <div class="sp-card-image" style="background-image:url(<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$image['src'] ?>);"></div>
                </div>

                <div class="sp-card-body">
                  <?PHP
                  if($image['description']) {
                    ?>
                    <?=nl2br($image['description']) ?>
                    <?PHP
                  }
                  ?>
                </div>
              </div>
            </div>
          <?PHP
        }
        ?>
      </div>
    <?php

  }
?>

<?PHP
$map_post_url = get_option('sp-locations_map_post_url');
if(!empty($map_post_url)) {
  ?>
  <a href="<?=$map_post_url ?>">Zurück zur Karte</a><br>
  <?PHP
}
?>

<?PHP
$form_post_url = get_option('sp-locations_form_post_url');
if(!empty($form_post_url)) {
  ?>
  <a href="<?=$form_post_url ?>">Location melden</a><br>
  <?PHP
}
?>

<a href="<?=get_site_url().'/location-print?location='.$post_id ?>" target="_blank">Druckansicht</a>

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
