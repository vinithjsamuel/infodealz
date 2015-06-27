<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page">
	<div class="container">
		<div class="<?php mts_article_class(); ?>">
			<div id="content_box">
				<h1 class="postsby">
					<?php if (is_category()) { ?>
						<span><?php single_cat_title(); ?><?php _e(" Archive", "mythemeshop"); ?></span>
					<?php } elseif (is_tag()) { ?> 
						<span><?php single_tag_title(); ?><?php _e(" Archive", "mythemeshop"); ?></span>
					<?php } elseif (is_author()) { ?>
						<span><?php  $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); echo esc_html( $curauth->nickname ); _e(" Archive", "mythemeshop"); ?></span>
					<?php } elseif (is_day()) { ?>
						<span><?php _e("Daily Archive:", "mythemeshop"); ?></span> <?php the_time('l, F j, Y'); ?>
					<?php } elseif (is_month()) { ?>
						<span><?php _e("Monthly Archive:", "mythemeshop"); ?></span> <?php the_time('F Y'); ?>
					<?php } elseif (is_year()) { ?>
						<span><?php _e("Yearly Archive:", "mythemeshop"); ?></span> <?php the_time('Y'); ?>
					<?php } ?>
				</h1>
				<?php
				$j = 0; if (have_posts()) : while (have_posts()) : the_post();
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
				<?php } ?>
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer(); ?>