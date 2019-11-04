<?php

$markers = get_posts( [
  'numberposts' => -1,
  'post_type' => 'marker'
] );

?>


<label>Type:
  <select name="sp_change_type">

    <?PHP foreach($markers as $marker){ ?>
      <option value="<?=get_post_meta( $marker->ID, 'key', true ) ?>" <?=($type==get_post_meta( $marker->ID, 'key', true )? 'selected=':'') ?>><?=$marker->post_title ?></option>
    <?PHP } ?>

  </select>
</label>
