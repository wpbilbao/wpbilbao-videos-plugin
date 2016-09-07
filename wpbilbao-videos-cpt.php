<?php
/*
Plugin Name: WPBilbao - Videos CPT
Description: Custom Post Type for adding the videos of the Meetups and Eventos of WPBilbao. Requieres de use of the Genesis Framework for the Page Template & Single Template.
Plugin URI: http://www.wpbilbao.es
Author: Ibon Azkoitia
Author URI: https://www.kreatidos.com
Version: 1.0
License: GPL2
Text Domain: wpbilbao-videos-cpt
*/


function wpbilbao_cpt_videos_load_plugin_textdomain() {
  load_plugin_textdomain( 'wpbilbao-videos-cpt', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'wpbilbao_cpt_videos_load_plugin_textdomain' );

/**
* Registers a new post type
* @uses $wp_post_types Inserts new post type object into the list
*
* @param string  Post type key, must not exceed 20 characters
* @param array|string  See optional args description above.
* @return object|WP_Error the registered post type object, or an error object
*/
function wpbilbao_cpt_videos() {

  $labels = array(
    'name'                => __( 'Videos', 'wpbilbao-videos-cpt' ),
    'singular_name'       => __( 'Video', 'wpbilbao-videos-cpt' ),
    'add_new'             => __( 'Add New', 'wpbilbao-videos-cpt' ),
    'add_new_item'        => __( 'Add New Video', 'wpbilbao-videos-cpt' ),
    'edit_item'           => __( 'Edit Video', 'wpbilbao-videos-cpt' ),
    'new_item'            => __( 'New Video', 'wpbilbao-videos-cpt' ),
    'view_item'           => __( 'View Video', 'wpbilbao-videos-cpt' ),
    'search_items'        => __( 'Search Videos', 'wpbilbao-videos-cpt' ),
    'not_found'           => __( 'Not Found', 'wpbilbao-videos-cpt' ),
    'not_found_in_trash'  => __( 'Not Found in Trash', 'wpbilbao-videos-cpt' ),
    'parent_item_colon'   => __( 'Parent Video:', 'wpbilbao-videos-cpt' ),
    'menu_name'           => __( 'Videos', 'wpbilbao-videos-cpt' ),
  );

  $args = array(
    'labels'              => $labels,
    'hierarchical'        => false,
    'description'         => 'description',
    'taxonomies'          => array(),
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => null,
    'menu_icon'           => 'dashicons-format-video',
    'show_in_nav_menus'   => true,
    'publicly_queryable'  => true,
    'exclude_from_search' => false,
    'has_archive'         => true,
    'query_var'           => true,
    'can_export'          => true,
    'rewrite'             => true,
    'capability_type'     => 'post',
    'supports'            => array(
        'title', 'author', 'thumbnail',
        'revisions', 'page-attributes', 'post-formats'
        )
  );

  register_post_type( 'videos', $args );
}

add_action( 'init', 'wpbilbao_cpt_videos' );



function wpbilbao_cpt_videos_rewrite_flush() {
  // First, we "add" the custom post type via the above written function.
  // Note: "add" is written with quotes, as CPTs don't get added to the DB,
  // They are only referenced in the post_type column with a post entry,
  // when you add a post of this CPT.
  wpbilbao_cpt_videos();

  // ATTENTION: This is *only* done during plugin activation hook in this example!
  // You should *NEVER EVER* do this on every page load!!
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wpbilbao_cpt_videos_rewrite_flush' );



/*
 * Filter the single_template with our custom function
 */
add_filter('single_template', 'wpbilbao_cpt_videos_single_template');
function wpbilbao_cpt_videos_single_template( $single ) {
  global $wp_query, $post;

  // Checks for single template by post type
  if ( $single && $post->post_type == 'videos' ) {
    if( file_exists( plugin_dir_path( __FILE__ ) . 'single-videos.php' ) ) {
      return plugin_dir_path( __FILE__ ) . 'single-videos.php';
    }
  }
  return $single;
}

/*
 * Filter the archive_template with our custom function
 */
add_filter( 'archive_template', 'wpbilbao_cpt_videos_archive_template' );
function wpbilbao_cpt_videos_archive_template( $archive_template ) {
  global $post;

  if ( is_post_type_archive( 'videos' ) ) {
    if ( file_exists( plugin_dir_path( __FILE__ ) . 'archive-videos.php' ) ) {
      return plugin_dir_path( __FILE__ ) . 'archive-videos.php' ;
    }
  }
  return $archive_template;
}



/*
 * CUSTOM SIDEBAR
 */

//* Register New Sidebar
function wpbilbao_register_sidebars() {

  /* Register the primary sidebar. */
  register_sidebar(
    array(
      'id' => 'videos-single-sidebar',
      'name' => __( 'Videos Single Sidebar', 'wpbilbao-videos-cpt' ),
      'description' => __( 'This is the Sidebar for the Single Page of the Videos.', 'wpbilbao-videos-cpt' ),
      'before_widget' => '<aside id="%1$s" class="widget %2$s">',
      'after_widget' => '</aside>',
      'before_title' => '<h4 class="widget-title">',
      'after_title' => '</h4>'
    )
  );
  /* Repeat register_sidebar() code for additional sidebars. */
}
add_action( 'widgets_init', 'wpbilbao_register_sidebars' );


//* Register and Use Custom Sidebar
add_action('get_header','wpbilbao_cpt_videos_custom_sidebar');
function wpbilbao_cpt_videos_custom_sidebar() {
  if ( is_singular('videos')) { // Check if we're on a single post for my CPT called "videos"
    remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); //remove the default genesis sidebar
    add_action( 'genesis_sidebar', 'wpbilbao_cpt_videos_do_sidebar' ); //add an action hook to call the function for our custom sidebar
  }
}

//Function to output my custom sidebar
function wpbilbao_cpt_videos_do_sidebar() {
  dynamic_sidebar( 'videos-single-sidebar' );
}

/*============================*/


