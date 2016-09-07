<?php

/*
 * The template for displaying videos custom type archive page.
 *
 * @package WPBilbao\Archive\Videos
 * @author  Ibon Azkoitia
 * @license GPL-2.0+
 * @link    https://www.wpbilbao.es
 *
 */

  /** Init WPBilbao Videos Archive Page **/
  add_action( 'genesis_meta', 'wpbilbao_template_videos' );

  function wpbilbao_template_videos() {

    // Remove the Standard Genesis Loop.
    remove_action( 'genesis_loop', 'genesis_do_loop' );

    // Add our Custom Loop.
    add_action( 'genesis_loop', 'wpbilbao_template_videos_do_loop' );

    // Force full with content.
    add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

  }

 function wpbilbao_template_videos_do_loop() {
    global $post;

    /*
     * We select the custom post type 'videos'
     * Videos per page: 9
     */
    $args = wp_parse_args(
       genesis_get_custom_field( 'query_args' ),
       array(
          'post_type'      => 'videos',
          'posts_per_page' => 9,
          'post_status'    => 'publish' )
       );

    global $wp_query;
    $wp_query = new WP_Query( $args );

    if ( have_posts() ) : ?>

      <div class="row">

      <?php do_action( 'genesis_before_while' ); ?>
      <?php while ( have_posts() ) : the_post(); ?>

        <?php do_action( 'genesis_before_entry' ); ?>

        <div class="col-xs-12 col-sm-6 col-md-4">

          <?php printf( '<article %s>', genesis_attr( 'entry' ) ); ?>

            <?php do_action( 'genesis_before_entry_content' ); ?>

            <?php printf( '<div %s>', genesis_attr( 'entry-content' ) ); ?>

              <div class="embed-container">
                <?php echo the_field('videos_url_video'); ?>
              </div><!-- .embed-container-->

              <p class="text-center">
                <a href="<?php echo get_the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                  <?php echo the_title(); ?>
                </a>
              </p>

            </div><!-- .entry-content -->

            <?php do_action( 'genesis_after_entry_content' ); ?>

          </article>
        </div><!-- .col-md-4 -->

        <?php do_action( 'genesis_after_entry' ); ?>

      <?php endwhile; // End of the posts.?>
        <div class="clearfix"></div>
      <?php do_action( 'genesis_after_endwhile' ); ?>

      </div><!-- .row -->

  <?php else : // If no posts exist. ?>
      <?php do_action( 'genesis_loop_else' ); ?>
  <?php endif; // End of the loop. ?>

  <?php
  wp_reset_query();
 }

genesis();