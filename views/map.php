
<div
  class="sp-map"
  data-lat="<?=$lat ?>"
  data-lng="<?=$lng ?>"
  data-zoom="<?=$zoom ?>"
  data-cluster-icon="<?=plugin_dir_url(__DIR__).'assets/img/marker_cluster'; ?>"
  >

  <div class="sp-map-leaflet" style="height:<?=$height ?>;"></div>

  <div class="sp-map-navigation">

    <?PHP
    if($layers) {
    ?>
      <div class="sp-map-button sp-map-filter-button">
        <span class="sp-map-button-icon" title="Select Layer">✔</span>
      </div>
    <?PHP
    }
    ?>

  </div>

  <?PHP

  // Draw marker filters
  if($layers) {

    ?>

    <div class="sp-map-filters">

        <?PHP

        foreach($layers as $category => $category_layers) {

          ?>

          <div class="sp-map-filter-category">

            <div class="sp-map-filter-category-header"><?=$category ?></div>

            <div class="sp-map-filter-category-body">

            <?PHP

            foreach($category_layers as $layer) {

              if($layer['type'] == 'marker') {
                ?>

                <label class="sp-map-filter">
                  <div class="sp-map-filter-icon">
                    <img class="sp-map-filter-image" src="<?=$layer['filter_icon'] ?>">
                    <input type="checkbox" class="sp-map-filter-checkbox sp-map-filter-checkbox-marker" data-type="marker" data-key="<?=$layer['key'] ?>" checked>
                  </div>
                  <div class="sp-map-filter-label"><?=$layer['title'] ?></div>
                </label>

                <?PHP
              }

              if($layer['type'] == 'geojson') {

                ?>

                <label class="sp-map-filter">
                  <div class="sp-map-filter-icon">
                    <div class="sp-map-filter-color" style="background-color:<?=$layer['color'] ?>;opacity:<?=$layer['opacity'] ?>;"></div>
                    <input type="checkbox" class="sp-map-filter-checkbox sp-map-filter-checkbox-geojson" data-type="geojson" data-key="<?=$layer['key'] ?>" checked>
                  </div>
                  <div class="sp-map-filter-label"><?=$layer['title'] ?></div>
                </label>

                <?PHP
              }

            }

            ?>

            </div>

          </div>

          <?PHP

        }

        ?>

    </div>
    <?PHP

  }

  ?>

</div>

<?PHP
// Create a welcome modal
if($content) {
  ?>
  <div class="sp-modal sp-modal-welcome">
    <div class="sp-modal-content">
      <div class="sp-modal-close sp-modal-close-trigger">×</div>

      <?=$content ?>

      <br><br>
      <input type="button" class="sp-modal-close-trigger" value="Alles klar">

    </div>
  </div>
  <?PHP
}
?>
