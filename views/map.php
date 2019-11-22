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
      <input type="button" value="Alles klar" onclick="closeModalViaHash('modal')">

    </div>
  </div>
  <?PHP
}
?>

<?PHP

foreach($locations as $location){

  $type = get_post_meta($location->ID, 'type', true);

  ?>
  <div id="<?=$location->ID; ?>" class="sp-modal">
    <div class="sp-modal-content">
      <div class="sp-modal-close">×</div>

      <h2>
        <?PHP /*<img style="height:40px;width:auto;margin-right:16px;" src="<?=plugins_url().'/sp-locations/assets/img/marker.svg' ?>"> */ ?>
        <?=$location->post_title ?>
      </h2>

      <?PHP
        if(get_post_meta( $location->ID, 'image', true )){
          ?>
          <img class="sp-has-margin-bottom-2" data-src='<?=spGetUploadUrl().'/sp-locations/thumbs/600/'.get_post_meta( $location->ID, 'image', true ) ?>'>
          <?PHP
        }
      ?>

      <?PHP

      $description = nl2br(get_post_meta($location->ID, 'description', true));
      $solution = nl2br(get_post_meta($location->ID, 'solution', true));
      $opening_hours = nl2br(get_post_meta($location->ID, 'opening_hours', true));

      if($description) {
        ?>
        <p>
          <strong>Beschreibung:</strong> <?=spTrimText($description) ?>
        </p>
        <?PHP
      }
      ?>

      <a href="<?=get_permalink($location->ID) ?>">Details anzeigen</a>

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

      <input type="button" value="schließen" onclick="closeModalViaHash('<?=$location->ID; ?>')">

    </div>
  </div>
<?PHP
}
?>

<script>

  document.addEventListener("DOMContentLoaded", function(event) {

    var mymap = L.map('mymap');

    var initialLat = <?=$lat ?>;
    var initialLng = <?=$lng ?>;
    var initialZoom = <?=$zoom ?>;

    // Check the hash manager for initial position data
    if(myHashmanager.has('lat')){
      initialLat = myHashmanager.get('lat');
    }
    if(myHashmanager.has('lng')){
      initialLng = myHashmanager.get('lng');
    }
    if(myHashmanager.has('zoom')){
      initialZoom = myHashmanager.get('zoom');
    }

    mymap.setView([initialLat, initialLng], initialZoom);

    // Register hash change events
    myHashmanager.on('lat', function(lat){
      var center = mymap.getCenter();
      center.lat = lat;
      var zoom = mymap.getZoom();
      mymap.setView(center, zoom);
    });
    myHashmanager.on('lng', function(lng){
      var center = mymap.getCenter();
      center.lng = lng;
      var zoom = mymap.getZoom();
      mymap.setView(center, zoom);
    });
    myHashmanager.on('zoom', function(zoom){
      var center = mymap.getCenter();
      mymap.setView(center, zoom);
    });

    // Store the current zoom and position in the hash
    mymap.on('zoomend', function() {
      myHashmanager.set('zoom', mymap.getZoom());
    });

    mymap.on('moveend', function(e) {
      var center = mymap.getCenter();
      myHashmanager.set('lat', center.lat);
      myHashmanager.set('lng', center.lng);
    });

    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {}).addTo(mymap);

    var markers = L.markerClusterGroup({
    	spiderfyOnMaxZoom: true,
    	showCoverageOnHover: false,
    	zoomToBoundsOnClick: true,
      removeOutsideVisibleBounds: true,
      iconCreateFunction: function(cluster) {
    		return L.divIcon({ html: '<div class="sp-map-marker sp-map-marker-cluster"><img style="height:100%;width:auto;" src="<?=plugin_dir_url(__DIR__).'assets/img/marker_cluster.svg'; ?>"><div class="sp-map-marker-info">' + cluster.getChildCount() + '</div></div>' });
    	}
    });

    <?PHP

    foreach($locations as $location){

      $type = get_post_meta($location->ID, 'type', true);

      $markers = get_posts( [
        'post_type' => 'marker',
        'meta_query' => array(
           array(
             'key' => 'key',
             'value' => trim($type),
             'compare' => '='
           )
       )
      ] );

      if($markers){

        $marker = $markers[0];
        $marker_icon = get_post_meta($marker->ID, 'icon', true);
        ?>

        var icon = L.divIcon({
           className: 'map-marker',
           iconSize:null,
           html:'<div class="sp-map-marker sp-map-marker-<?=$type ?>" title="<?=$location->post_title ?>"><img style="height:100%;width:auto;" src="<?=$marker_icon ?>"></div>'
         });

        var marker = L.marker([<?=get_post_meta($location->ID, 'lat', true) ?>, <?=get_post_meta($location->ID, 'lng', true) ?>], {icon: icon}).on('click', function(e) {
          // console.log(e);
          openModalViaHash(<?=$location->ID ?>);

        });

        markers.addLayer(marker);



        <?PHP
      }


    }

    ?>

    mymap.addLayer(markers);

  });

</script>
