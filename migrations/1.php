<?PHP

// Insert first marker types
$post_id = wp_insert_post( [
  'post_title' => 'Problemstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
] );
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker.svg');
add_post_meta($post_id, 'key', 'problem');

$post_id = wp_insert_post( [
  'post_title' => 'Unterschriftenstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker.svg');
add_post_meta($post_id, 'key', 'sign');

$post_id = wp_insert_post( [
  'post_title' => 'Behobene Problemstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker_positiv.svg');
add_post_meta($post_id, 'key', 'solved');
