<?PHP

// Insert first marker types
$post_id = wp_insert_post( [
  'post_title' => 'problem',
  'post_type' => 'marker',
  'post_status' => 'publish'
] );
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker.svg');
add_post_meta($post_id, 'label', 'Problemstelle');

$post_id = wp_insert_post( [
  'post_title' => 'sign',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker.svg');
add_post_meta($post_id, 'label', 'Unterschriftenstelle');

$post_id = wp_insert_post( [
  'post_title' => 'solved',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker.svg');
add_post_meta($post_id, 'label', 'Problemstelle behoben');
