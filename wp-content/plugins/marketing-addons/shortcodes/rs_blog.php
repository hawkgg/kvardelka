<?php
/**
 *
 * RS Blog
 * @since 1.0.0
 * @version 1.1.0
 *
 */
function rs_blog( $atts, $content = '', $id = '' ) {

  extract( shortcode_atts( array(
    'id'            => '',
    'class'         => '',
    'cats'          => 0,
    'orderby'       => 'ID',
    'exclude_posts' => '',
  ), $atts ) );

  $id    = ( $id ) ? ' id="'. esc_attr($id) .'"' : '';
  $class = ( $class ) ? ' '. $class : '';

  if (get_query_var('paged')) {
    $paged = get_query_var('paged');
  } elseif (get_query_var('page')) {
    $paged = get_query_var('page');
  } else {
    $paged = 1;
  }

  // Query args
  $args = array(
    'paged'          => $paged,
    'orderby'        => $orderby,
    'posts_per_page' => 3,
  );

  if( $cats ) {
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'category',
        'field'    => 'ids',
        'terms'    => explode( ',', $cats )
      )
    );
  }

  if (!empty($exclude_posts)) {
    $exclude_posts_arr = explode(',',$exclude_posts);
    if (is_array($exclude_posts_arr) && count($exclude_posts_arr) > 0) {
      $args['post__not_in'] = array_map('intval',$exclude_posts_arr);
    }
  }

  ob_start();

  $the_query = new WP_Query($args);

  ?>
  <div class="row">
    <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
    <div class="col-sm-4">
      <div <?php post_class('tt-post'); ?>>
        <a class="tt-post-img custom-hover" href="<?php echo esc_url(get_the_permalink()); ?>">
          <?php the_post_thumbnail('marketing-medium', array('class' => 'img-responsive')); ?>
        </a>
        <div class="tt-post-info">
            <a class="tt-post-title c-h5" href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a>
            <div class="tt-post-cat"><?php echo esc_html__('by', 'marketing-addons'); ?> <?php echo get_the_author(); ?></div>
            <div class="simple-text size-3">
              <p><?php echo marketing_auto_post_excerpt(); ?></p>
            </div>
            <a class="c-btn type-4" href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_html__('Read More', 'marketing-addons'); ?></a>   
        </div>
      </div>
    </div>
    <?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
  </div>

  <?php
  $output = ob_get_clean();
  return $output;
}
add_shortcode( 'rs_blog', 'rs_blog' );
