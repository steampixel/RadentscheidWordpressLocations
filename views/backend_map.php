<div id="mymap" class="steampixel-marker-map" style="height:300px;"></div>

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
