<?PHP

// Insert first marker types
$post_id = wp_insert_post( [
  'post_title' => 'Gehweg-Problemstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
] );
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker_walker_orange.svg');
add_post_meta($post_id, 'filter_icon', '/wp-content/plugins/sp-locations/assets/img/list_walker_orange.svg');
add_post_meta($post_id, 'key', 'problem_side_walk');

$post_id = wp_insert_post( [
  'post_title' => 'Fahrrad-Problemstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker_bike_orange.svg');
add_post_meta($post_id, 'filter_icon', '/wp-content/plugins/sp-locations/assets/img/list_bike_orange.svg');
add_post_meta($post_id, 'key', 'problem_bike');

$post_id = wp_insert_post( [
  'post_title' => 'Behobene Gehweg-Problemstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker_walker_green.svg');
add_post_meta($post_id, 'filter_icon', '/wp-content/plugins/sp-locations/assets/img/list_walker_green.svg');
add_post_meta($post_id, 'key', 'solved_side_walk');

$post_id = wp_insert_post( [
  'post_title' => 'Behobene Radweg-Problemstelle',
  'post_type' => 'marker',
  'post_status' => 'publish'
]);
add_post_meta($post_id, 'icon', '/wp-content/plugins/sp-locations/assets/img/marker_bike_green.svg');
add_post_meta($post_id, 'filter_icon', '/wp-content/plugins/sp-locations/assets/img/list_bike_green.svg');
add_post_meta($post_id, 'key', 'solved_bike');
