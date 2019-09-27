<div id="mymap" class="steampixel-marker-map" style="height:300px;"></div>

<script>

  document.addEventListener("DOMContentLoaded", function(event) {

    var mymap = L.map('mymap').setView([<?=$lat ?>, <?=$lng ?>], 18);

    L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {

    }).addTo(mymap);

    L.marker([<?=$lat ?>, <?=$lng ?>]).addTo(mymap);

  });

</script>
