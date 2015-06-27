<?php
/**
 * Template Name: Blog
 * 
 */
?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page">
    <div class="container">
        <div class="<?php mts_article_class(); ?>">
            <div id="content_box">
                <?php $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
                    $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'paged' => $paged,
                        'ignore_sticky_posts'=> 1,
                    );
                    $latest_posts = new WP_Query( $args );
                    global $wp_query;
                    $tmp_query = $wp_query;
                    $wp_query = null;
                    $wp_query = $latest_posts;
                    $j = 0; 
                if ( $latest_posts->have_posts() ) : while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
                    $format = get_post_format();
                    $format_class = ( false === $format ) ? ' format-standard' : ' format-'.$format;
                    ?>
                    <article class="latestPost excerpt<?php echo $format_class; ?>" itemscope itemtype="http://schema.org/BlogPosting">
                        <div class="featured-content">
                            <?php get_template_part( 'post-format/format', $format ); ?>
                        </div>
                        <div class="front-view-container">
                            <header class="entry-header">
                                <h2 class="title front-view-title" itemprop="headline"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
                                <?php mts_the_postinfo(); ?>
                            </header>
                            <?php if (empty($mts_options['mts_full_posts'])) : ?>
                                <div class="front-view-content">
                                    <?php echo mts_excerpt(55); ?>
                                </div>
                                <?php mts_readmore(); ?>
                            <?php else : ?>
                                <div class="front-view-content full-post">
                                    <?php the_content(); ?>
                                </div>
                                <?php if (mts_post_has_moretag()) : ?>
                                    <?php mts_readmore(); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <span class="format-icon"></span>
                        </div>
                    </article><!--.post excerpt-->

                <?php $j++; endwhile; endif; ?>
                <?php if ( $j !== 0 ) { // No pagination if there is no results ?>
                    <!--Start Pagination-->
                    <?php if (isset($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] == '1' ) { ?>
                        <?php mts_pagination(); ?>
                    <?php } else { ?>
                        <div class="pagination pagination-previous-next">
                            <ul>
                                <li class="nav-previous"><?php next_posts_link( '<i class="fa fa-angle-left"></i> '. __('Previous', 'mythemeshop' ) ); ?></li>
                                <li class="nav-next"><?php previous_posts_link( __( 'Next', 'mythemeshop' ) . ' <i class="fa fa-angle-right"></i>' ); ?></li>
                            </ul>
                        </div>
                    <?php } ?>
                    <!--End Pagination-->
                <?php }
                // Restore original query object
                $wp_query = $tmp_query;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>