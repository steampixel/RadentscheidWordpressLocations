<?PHP

/*
  Add custom post type
*/
add_action( 'init', function () {

  // Set UI labels for Custom Post Type
  $labels = array(
    'name'                => 'GeoJSONs',
    'singular_name'       => 'GeoJSON',
    'menu_name'           => 'GeoJSONs',
    'parent_item_colon'   => 'Parent GeoJSON',
    'all_items'           => 'All GeoJSONs',
    'view_item'           => 'View GeoJSONs',
    'add_new_item'        => 'Add New GeoJSON',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit GeoJSON',
    'update_item'         => 'Update GeoJSON',
    'search_items'        => 'Search GeoJSON',
    'not_found'           => 'Not Found',
    'not_found_in_trash'  => 'Not found in Trash'
  );

  // Set other options for Custom Post Type

  $args = array(
    'label'               => 'geojson',
    'description'         => 'GeoJSON news and reviews',
    'labels'              => $labels,
    // Features this CPT supports in Post Editor
    'supports'            => array( 'title', 'revisions', 'custom-fields' ), // 'editor', 'excerpt', 'author', 'thumbnail', 'comments',
    // You can associate this CPT with a taxonomy or custom taxonomy.
    // 'taxonomies'          => array( 'genres' ),
    /* A hierarchical CPT is like Pages and can have
    * Parent and child items. A non-hierarchical CPT
    * is like Posts.
    */
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => 'edit.php?post_type=location',
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 5,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'capability_type'     => 'page',
    'taxonomies'          => array( 'category' ),
  );

  // Registering your Custom Post Type
  register_post_type( 'geojson', $args );

}, 0 );

/*
  Disable single view for custom post type in the frontend
*/
add_action( 'template_redirect', function () {
  $queried_post_type = get_query_var('post_type');
  if ( is_single() && 'geojson' ==  $queried_post_type ) {
    wp_redirect( home_url(), 301 );
    exit;
  }
} );

/*
  Exclude One Content Type From Yoast SEO Sitemap
*/
add_filter( 'wpseo_sitemap_exclude_post_type', function ( $value, $post_type ) {
if ( $post_type == 'geojson' ) return true;
}, 10, 2 );
