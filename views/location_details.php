<?PHP
  if(get_post_meta( $post_id, 'image', true )){
    ?>
    <img class="sp-has-margin-bottom-2" src='<?=spGetUploadUrl().'/sp-locations/thumbs/600/'.get_post_meta( $post_id, 'image', true ) ?>'>
    <?PHP
  }
?>

<div id="mymap" class="steampixel-marker-map" style="height:300px;"></div>

<?PHP

$description = nl2br(get_post_meta($post_id, 'description', true));
$solution = nl2br(get_post_meta($post_id, 'solution', true));
$opening_hours = nl2br(get_post_meta($post_id, 'opening_hours', true));

if($description){
  ?>
  <p>
    <strong>Beschreibung:</strong> <?=$description ?>
  </p>
  <?PHP
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

<p>
  <strong>Adresse</strong><br>
  <?=get_post_meta($post_id, 'street', true) ?> <?=get_post_meta($post_id, 'house_number', true) ?><br>
  <?=get_post_meta($post_id, 'postcode', true) ?> <?=get_post_meta($post_id, 'place', true) ?><br>
  <!-- <?=get_post_meta($post_id, 'suburb', true) ?> -->
</p>

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
