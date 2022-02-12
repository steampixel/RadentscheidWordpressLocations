<div class="sp-location-picker sp-has-margin-bottom-2" data-lat="<?=$lat ?>" data-lng="<?=$lng ?>" data-zoom="<?=$zoom ?>" data-marker-icon="<?=plugin_dir_url(__DIR__).'assets/img/marker.svg'; ?>">
  <div id="mymap" class="sp-location-picker-map" style="height:300px;"></div>
<!--  <input class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-lat" name='lat'>
  <input class="sp-xhr-form-data sp-xhr-form-input sp-location-picker-input-lat" name='lng'>
-->
</div>
<?PHP
if($solution) {
  ?>
  <p>
    <strong>Solution:</strong> <?=$solution ?>
  </p>
  <?PHP
}
?>

<?PHP
if($opening_hours) {
  ?>
  <p>
    <strong>Opening hours:</strong> <?=$opening_hours ?>
  </p>
  <?PHP
}
?>

<p>
  <strong>Adresse</strong><br>
  <?=$street ?> <?=$house_number ?><br>
  <?=$postcode ?> <?=$place ?><br>
  <?=$suburb ?>
</p>

<script>

  document.addEventListener("DOMContentLoaded", function(event) {

    var mymap = L.map('mymap').setView([<?=$lat ?>, <?=$lng ?>], 18);

    mymap.attributionControl.addAttribution('&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors');

    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {

    }).addTo(mymap);

    L.marker([<?=$lat ?>, <?=$lng ?>]).addTo(mymap);

  });

</script>
