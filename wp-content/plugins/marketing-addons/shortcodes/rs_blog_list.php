<?php
/**
 *
 * RS Blog
 * @since 1.0.0
 * @version 1.1.0
 *
 */
function rs_blog_list( $atts, $content = '', $id = '' ) {

  extract( shortcode_atts( array(
    'id'             => '',
    'class'          => '',
    'cats'           => 0,
    'orderby'        => 'ID',
    'posts_per_page' => 999,
    'exclude_posts'  => '',
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
    'posts_per_page' => $posts_per_page,
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

  if(is_page()) {
    $max_num_pages = $the_query -> max_num_pages;
  } else {
    global $wp_query;
    $the_query = $wp_query;
    $max_num_pages = false;
  }
  ?>
  <!-- <?php echo do_shortcode( '[searchandfilter fields="category"]' ); ?> -->
  <div id="<?= $id ?>" class="row isotope <?= $class ?>">
    <?php if($the_query -> have_posts()): while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
    <div <?php post_class('isotope-item col-lg-12 col-md-12 col-sm-12'); ?>>
      <div <?php post_class('tt-post'); ?>>
        <?php get_template_part('templates/blog/blog-list/content', get_post_format()); ?>
        <div class="tt-post-3-info">
          <!-- <div class="tt-post-cat"><?php echo esc_html__('by', 'marketing-addons'); ?> <?php echo get_the_author(); ?></div> -->
          <h2><a class="tt-post-title c-h3" href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h2>
          <div class="tt-post-3-label my-2">
            <p>
              <i class="fa fa-calendar mr-2"></i><span><?php the_time('F d, Y'); ?></span>
              <span><?php echo get_the_category_list( esc_html__( ', ', 'marketing' ) );?></span>
            </p>
          </div>
          <div class="simple-text size-3">
            <p><?php echo marketing_auto_post_excerpt(); ?></p>
          </div>
          <div class="text-right"> 
            <a class="c-btn type-4 read-more-link" href="<?php echo esc_url(get_the_permalink()); ?>"><?php echo esc_html__('Читать далее', 'marketing-addons'); ?></a>
          </div>
        </div>  
      </div>
    </div>
    <div class="empty-space marg-lg-b30"></div>
    <?php endwhile; wp_reset_postdata(); else:
      get_template_part('templates/content', 'none');
    endif; ?>
  </div>
  <?php marketing_paging_nav($max_num_pages); ?>
  <div class="empty-space marg-sm-b30"></div>

  <?php
  $output = ob_get_clean();
  return $output;
}
add_shortcode( 'rs_blog_list', 'rs_blog_list' );
