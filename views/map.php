<div id="mymap" style="height:<?=$height ?>;">

  <!-- <div class="sp-map-button">
    <input type="button" value="Beschreibung öffnen" onclick="openModal('modal-welcome')">
  </div> -->

  <?PHP
  if($button_label){
    ?>
    <div class="sp-map-button">
      <a href="<?=$button_link ?>"><input type="button" value="<?=$button_label ?>"></a>
    </div>
    <?PHP
  }
  ?>

  <!-- <div class="sp-map-filter">
    <label>
      <input type="checkbox" checked onclick="toggleDisplay('.marker-sign')">📋 Sammelstellen anzeigen
    </label>
    <br>
    <label>
      <input type="checkbox" checked onclick="toggleDisplay('.marker-problem')">⚡ Problemstellen anzeigen
    </label>
  </div> -->

</div>

<?PHP
if($content){
  ?>
  <div id="welcome" class="sp-modal sp-is-active">
    <div class="sp-modal-content">
      <div class="sp-modal-close">×</div>

      <?=$content ?>

      <br><br>
      <input type="button" value="Alles klar" onclick="closeModal('modal')">

    </div>
  </div>
  <?PHP
}
?>

<?PHP

// http://xahlee.info/comp/unicode_office_icons.html

foreach($locations as $location){

  $type = get_post_meta($location->ID, 'type', true);

  ?>
  <div id="<?=$location->ID; ?>" class="sp-modal">
    <div class="sp-modal-content">
      <div class="sp-modal-close">×</div>

      <h2>
        <img style="height:40px;width:auto;margin-right:16px;" src="<?=plugins_url().'/sp-locations/assets/img/marker.svg' ?>">
        <?=$location->post_title ?>
      </h2>
      <img class="sp-has-margin-bottom-2" data-src='<?=spGetUploadUrl().'/sp-locations/thumbs/600/'.get_post_meta( $location->ID, 'image', true ) ?>'>

      <?PHP

      $description = nl2br(get_post_meta($location->ID, 'description', true));
      $solution = nl2br(get_post_meta($location->ID, 'solution', true));
      $opening_hours = nl2br(get_post_meta($location->ID, 'opening_hours', true));

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
        <?=get_post_meta($location->ID, 'street', true) ?> <?=get_post_meta($location->ID, 'house_number', true) ?><br>
        <?=get_post_meta($location->ID, 'postcode', true) ?> <?=get_post_meta($location->ID, 'place', true) ?><br>
        <!-- <?=get_post_meta($location->ID, 'suburb', true) ?> -->
      </p>

      <input type="button" value="schließen" onclick="closeModal('<?=$location->ID; ?>')">

    </div>
  </div>
<?PHP
}
?>

<script>

  document.addEventListener("DOMContentLoaded", function(event) {

    var mymap = L.map('mymap').setView([<?=$lat ?>, <?=$lng ?>], <?=$zoom ?>);

    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {}).addTo(mymap);

    <?PHP

    foreach($locations as $location){

      $type = get_post_meta($location->ID, 'type', true);

      ?>

      var icon = L.divIcon({
         className: 'map-marker',
         iconSize:null,
         html:'<div class="sp-map-marker marker-<?=$type ?>" title="<?=$location->post_title ?>"><img style="height:100%;width:auto;" src="<?=plugins_url().'/sp-locations/assets/img/marker.svg' ?>"></div>'
       });

      L.marker([<?=get_post_meta($location->ID, 'lat', true) ?>, <?=get_post_meta($location->ID, 'lng', true) ?>], {icon: icon}).on('click', function(e) {
        // console.log(e);
        openModal(<?=$location->ID ?>);

      }).addTo(mymap);

      <?PHP

    }

    ?>

  });

</script>
