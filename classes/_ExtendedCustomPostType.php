<?PHP

namespace Sp;

class ExtendedCustomPostType {

  public static $custom_post_types = [];

  public static function add($custom_post_type) {

    self::$custom_post_types[$custom_post_types['name']] = $custom_post_type;

  }

  public static function initiate() {


    foreach(self::$custom_post_types as $custom_post_type) {

      // Register the post type
      add_action( 'init', function () {

        // Set UI labels for Custom Post Type
        $labels = array(
          'name'                => $options['plural_label'],
          'singular_name'       => $options['singular_label'],
          'menu_name'           => $options['plural_label'],
          'parent_item_colon'   => 'Parent '.$options['singular_label'],
          'all_items'           => 'All '.$options['plural_label'],
          'view_item'           => 'View '.$options['singular_label'],
          'add_new_item'        => 'Add New '.$options['singular_label'],
          'add_new'             => 'Add New',
          'edit_item'           => 'Edit '.$options['singular_label'],
          'update_item'         => 'Update '.$options['singular_label'],
          'search_items'        => 'Search '.$options['singular_label'],
          'not_found'           => 'Not Found',
          'not_found_in_trash'  => 'Not found in Trash',
        );

        // Set other options for Custom Post Type
        $args = array(
          'label'               => $options['plural_label'],
          'description'         => $options['plural_label'],
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
          // 'show_in_menu'        => 'edit.php?post_type=locations',
          'show_in_nav_menus'   => true,
          'show_in_admin_bar'   => true,
          'menu_position'       => 5,
          'can_export'          => true,
          'has_archive'         => true,
          'exclude_from_search' => true,
          'publicly_queryable'  => true,
          'capability_type'     => 'page',
        );

        // Registering your Custom Post Type
        register_post_type( $options['name'], $args );

      }, 0 );

      // Add columns to CPT
      add_filter( 'manage_'.$this->name.'_posts_columns', function ( $columns ) use ($custom_post_type) {
        foreach($custom_post_type['columns'] as $custom_post_type_column) {
          $columns[$custom_post_type_column['name']] = $custom_post_type_column['label'];
        }
        return $columns;
      });

      // Populate the new column with data
      add_action( 'manage_'.$this->name.'_posts_custom_column', function ( $column, $post_id ) use ($custom_post_type) {
        $row = wpdb::get_row('SELECT * FROM '.wpdb::$prefix.$custom_post_type['table'].' WHERE post_id = '.$post_id, ARRAY_A);
        foreach($custom_post_type['columns'] as $custom_post_type_column) {
          if ( $column === $custom_post_type_column['name']) {
            echo $row[$custom_post_type_column['name']];
          }
        }
      }, 10, 2);

      // Add sortable columns
      add_filter( 'manage_edit-'.$this->name.'_sortable_columns', function ( $columns ) use ($custom_post_type) {
        foreach($custom_post_type['columns'] as $custom_post_type_column) {
          if($custom_post_type_column['sortable'] === true) {
            $columns[$custom_post_type_column['name']] = $custom_post_type_column['name'];
          }
        }
        return $columns;
      });

      // Todo: Alter query for sorting...

      // Add meta boxes for this column
      add_action( 'add_meta_boxes', function () use ($custom_post_type) {

        // Read row from DB
        global $post;
        $row = wpdb::get_row('SELECT * FROM '.wpdb::$prefix.$custom_post_type['table'].' WHERE post_id = '.$post->ID, ARRAY_A);

        foreach($custom_post_type['columns'] as $custom_post_type_column) {
          add_meta_box(
        		$custom_post_type_column['name'],
        		$custom_post_type_column['label'],
        		function () {
              echo Sp\View::render('form_fields/'.$custom_post_type_column['type'], [
                'name'=>$custom_post_type_column['name'],
                'value'=>$row[$custom_post_type_column['name']],
                'row' => $row
              ]);
            },
        		$custom_post_type_column['name'],
        		'normal',
        		'high'
        	);
        }
      } );

      // Save values to custom table on CPT save event
      add_action( 'save_post_'.$this->name, function ( $post_id, $post, $update ) use ($custom_post_type) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return;
        }

        $data = [];
        foreach($custom_post_type['columns'] as $custom_post_type_column) {
          if(isset($_POST[$custom_post_type_column['name']])) {
            $data[$custom_post_type_column['name']] = $_POST[$custom_post_type_column['name']];
          }
        }

        // If this record exists in table...
        if(!wpdb::get_row('SELECT * FROM '.wpdb::$prefix.$custom_post_type['table'].' WHERE post_id = '.$post->ID, ARRAY_A)) {
          $data['post_id'] = $post->ID;
          wpdb::insert( wpdb::$prefix.$custom_post_type['table'], $data);
        } else {
          wpdb::update( wpdb::$prefix.$custom_post_type['table'], $data, array( 'post_id' => $post->ID ) );
        }

      }, 10, 3 );

      // Disable single view for custom post type in the frontend
      add_action( 'template_redirect', function () use ($custom_post_type) {
        $queried_post_type = get_query_var('post_type');
        if ( $custom_post_type['disable_single_view'] && is_single() && $custom_post_type['name'] ==  $queried_post_type ) {
          wp_redirect( home_url(), 301 );
          exit;
        }
      } );

      // Exclude this content type from Yoast SEO Sitemap
      add_filter( 'wpseo_sitemap_exclude_post_type', function ( $value, $post_type ) use ($custom_post_type) {
        if ( $custom_post_type['exclude_from_yoast_seo'] && $post_type == $custom_post_type['name'] ) {
          return true
        };
      }, 10, 2 );

    } // foreach $custom_post_types

  } // initiate

}
