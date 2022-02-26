<?PHP

$type = get_post_meta( $post_id, 'type', true );
$images = get_post_meta( $post_id, 'images', true );
$solution = nl2br(get_post_meta($post_id, 'solution', true));
$description = nl2br(get_post_meta($post_id, 'description', true));
$name = get_post_meta($post_id, 'contact_person', true);
$updates = array();
for ($i=0; $i<10; $i++) {
	$ext=$i == 0 ? "" : $i;
	$update = get_post_meta($post_id, 'update'.$ext, true);
	if (!$update) break;
	array_push($updates, $update);
}

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
    <div>Kategorie <?=$type_posts[0]->post_title ?></div>
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
  <P>
    <?=nl2br($description) ?>
  </p>
  <?PHP
}
?>

<div class="sp-mini-map" data-zoom="18" data-icon="<?=$marker_icon ?>" data-lat="<?=get_post_meta($post_id, 'lat', true) ?>" data-lng="<?=get_post_meta($post_id, 'lng', true) ?>" data-title="<?=get_post_meta($post_id, 'title', true) ?>" data-type="<?=get_post_meta($post_id, 'type', true) ?>"></div>

<p>
  <?=get_post_meta($post_id, 'street', true) ?> <?=get_post_meta($post_id, 'house_number', true) ?>,
  <?=get_post_meta($post_id, 'postcode', true) ?> <?=get_post_meta($post_id, 'place', true) ?>
  <!-- <?=get_post_meta($post_id, 'suburb', true) ?> -->
</p>

<?PHP
if($solution){
  ?>
  <div>
    <div for="solution">Lösungsvorschlag <?= $name ? 'von '.$name : ''?></div>
    <div style='margin-left:20px;'>
      <?=nl2br($solution) ?>
    </div>
  </div>
  <?PHP
}
foreach($updates as $update) {
	$parts = explode(':', $update);
	$text = count($parts) > 1 ? $parts[1] : $update;
	$date = count($parts) > 1 ? $parts[0] : '- ohne Datum';
  ?>
  <div>
    <div style='margin-top: var(--global--spacing-vertical);'for="solution"><h3>Update <?=$date?></h3></div>
    <div style='margin-left:20px; margin-top: var(--global--spacing-vertical);'>
      <?=nl2br($text) ?>
    </div>
  </div>
<?PHP
}
  // Draw all other images
  if(count($images)>1) {
    ?>
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
<div>
<?PHP
$map_post_url = get_option('sp-locations_map_post_url');
if(!empty($map_post_url)) { // close when triggered from map, otherwise navigate to map
  ?>
  <input class="sp-xhr-form-submit" style='margin-right:30px;' type="button" onClick="self.close(); window.location.href='<?=$map_post_url ?>'" value='Zur&uuml;ck zur Karte'>
  <?PHP
}
?>

<?PHP
$form_post_url = get_option('sp-locations_form_post_url');
if(!empty($form_post_url)) {
  ?>
  <input class="sp-xhr-form-submit" type="button" onClick="window.location.href='<?=$form_post_url ?>'" value='Weiteren Vorschlag einbringen'>
  <?PHP
}
?>
</div>