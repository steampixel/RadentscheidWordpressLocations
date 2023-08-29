<?PHP
/*
  Shortcode for counting the markers
*/
add_shortcode( 'steampixel-marker-count', function($atts = [], $content = null, $tag = '') {

  // normalize attribute keys, lowercase
  $atts = array_change_key_case((array)$atts, CASE_LOWER);

  // override default attributes with user attributes
  $attributes = shortcode_atts([
    'type' => null
  ], $atts, $tag);

  if($attributes['type']){

    $types = explode(',', $attributes['type']);

    $locations = [];

    foreach($types as $type){
      $_locations = get_posts( [
        'numberposts' => -1,
        'post_type' => 'location',
        'meta_query' => array(
           array(
             'key' => 'type',
             'value' => trim($type),
             'compare' => '='
           )
       )
      ] );

      if($_locations) {
        $locations = array_merge($locations, $_locations);
      }

    }

  }else{
    $locations = get_posts( [
      'numberposts' => -1,
      'post_type' => 'location'
    ] );
  }

  return count($locations);

} );
