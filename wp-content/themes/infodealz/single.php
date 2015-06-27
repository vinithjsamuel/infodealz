<?php get_header(); ?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<div id="page" class="single">
	<article class="<?php mts_article_class(); ?>" itemscope itemtype="http://schema.org/BlogPosting">
		<div id="content_box" >
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
					<div class="single_post">
						<header>
							<h1 class="title single-title entry-title" itemprop="headline"><?php the_title(); ?></h1>
							<?php mts_the_postinfo( 'single' ); ?>
						</header><!--.headline_area-->
						<div class="featured-content">
							<?php get_template_part( 'post-format/format', get_post_format() ); ?>
						</div>
						<div class="post-single-content-wrap">
							<?php
							if ( isset($mts_options['mts_social_button_position']) ) {
								if ($mts_options['mts_social_button_position'] == 'floating' ) echo '<div id="catchersocial" class="clear"></div><div id="catchersocial2" class="clear"></div>';
								if ($mts_options['mts_social_button_position'] !== 'bottom' ) mts_social_buttons();
							}
							?>
							<div class="post-single-content box entry-content">
								<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
									<?php $toptime = $mts_options['mts_posttop_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$toptime day")), get_the_time("Y-m-d") ) >= 0) { ?>
										<div class="topad">
											<?php echo do_shortcode($mts_options['mts_posttop_adcode']); ?>
										</div>
									<?php } ?>
								<?php } ?>
								<div class="thecontent mark-links" itemprop="articleBody">
									<?php the_content(); ?>
								</div>
								<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => __('Next','mythemeshop'), 'previouspagelink' => __('Previous','mythemeshop'), 'pagelink' => '%','echo' => 1 )); ?>
								<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
									<?php $endtime = $mts_options['mts_postend_adcode_time']; if (strcmp( date("Y-m-d", strtotime( "-$endtime day")), get_the_time("Y-m-d") ) >= 0) { ?>
										<div class="bottomad">
											<?php echo do_shortcode($mts_options['mts_postend_adcode']); ?>
										</div>
									<?php } ?>
								<?php } ?>
								<?php if (empty($mts_options['mts_social_button_position']) || $mts_options['mts_social_button_position'] == 'bottom' ) mts_social_buttons(); ?>
								<?php if($mts_options['mts_tags'] == '1') { ?>
									<div class="tags"><?php mts_the_tags('<span class="tagtext"><i class="fa fa-tags"></i> '.__('Tags','mythemeshop').':</span>',', ') ?></div>
								<?php } ?>
							</div><!--.post-single-content-->
						</div><!--.post-single-content-wrap-->
					</div><!--.single_post-->

					<?php mts_related_posts(); ?>

					<?php if($mts_options['mts_author_box'] == '1') { ?>
						<div class="postauthor">
							<h4><?php _e('About The Author', 'mythemeshop'); ?></h4>
							<div class="postauthor-box">
								<?php if(function_exists('get_avatar')) { echo get_avatar( get_the_author_meta('email'), '120' );  } ?>
								<h5 class="vcard"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="nofollow" class="fn"><?php the_author_meta( 'nickname' ); ?></a></h5>
								<p><?php the_author_meta('description') ?></p>
							</div>
						</div>
					<?php }?>  
				</div><!--.g post-->
				<?php comments_template( '', true ); ?>
			<?php endwhile; /* end loop */ ?>
		</div>
	</article>
	<?php get_sidebar(); ?>

	<?php do_action( 'mts_before_single_post_footer' ); ?>

<?php get_footer(); ?>