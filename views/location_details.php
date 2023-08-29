<?PHP

$type = get_post_meta( $post_id, 'type', true );
$images = get_post_meta( $post_id, 'images', true );
$solution = nl2br(get_post_meta($post_id, 'solution', true));
$description = nl2br(get_post_meta($post_id, 'description', true));
$opening_hours = nl2br(get_post_meta($post_id, 'opening_hours', true));

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

if($images) {

  ?>

  <div class="sp-hero enlarge-on-click" data-enlarge-src="<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$images[0]['src'] ?>">
    <div class="sp-hero-image-background" style="background-image:url(<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$images[0]['src'] ?>);"></div>
    <div class="sp-hero-image" style="background-image:url(<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$images[0]['src'] ?>);"></div>
  </div>

  <?PHP

}

?>

<?PHP
if($description) {
  ?>
  <h2 class="sp-text-md">Kurzbeschreibung</h2>
  <p>
    <?=nl2br($description) ?>
  </p>
  <?PHP
}
?>

<?PHP
$show_solutions_on_detail_page = get_option('sp-show_solutions_on_detail_page');
if($show_solutions_on_detail_page ) {
  if($solution) {
    ?>
    <h2 class="sp-text-md">Lösungsvorschlag</h2>
    <p>
      <?=nl2br($solution) ?>
    </p>
    <?PHP
  }
}
?>



<?PHP
if($opening_hours){
  ?>
  <p>
    <strong>Öffnungszeiten:</strong> <?=$opening_hours ?>
  </p>
  <?PHP
}
?>

<strong>Adresse</strong>
<div class="sp-mini-map" data-zoom="18" data-icon="<?=$marker_icon ?>" data-lat="<?=get_post_meta($post_id, 'lat', true) ?>" data-lng="<?=get_post_meta($post_id, 'lng', true) ?>" data-title="<?=get_post_meta($post_id, 'title', true) ?>" data-type="<?=get_post_meta($post_id, 'type', true) ?>"></div>

<p>
  <?=get_post_meta($post_id, 'street', true) ?> <?=get_post_meta($post_id, 'house_number', true) ?>,
  <?=get_post_meta($post_id, 'postcode', true) ?> <?=get_post_meta($post_id, 'place', true) ?>
  <!-- <?=get_post_meta($post_id, 'suburb', true) ?> -->
</p>

<?PHP

  // Draw all other images
  if(count($images)>1) {

    ?>
    <strong>Weitere Bilder</strong>

    <div class="sp-cards">
      <?php

      foreach($images as $key => $image) {

        if($key>0) {

          ?>
            <div class="sp-card enlarge-on-click" data-enlarge-src="<?=spGetUploadUrl().'/sp-locations/'.$post_id.'/600/'.$image['src'] ?>">

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
