<?php
/**
 * Template Name: WC Offers Page
 */
?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php $feedburner_id = $mts_options['mts_feedburner']; ?>
<?php get_header(); ?>
<div id="page" class="single offers">
	<article class="<?php mts_article_class(); ?>">
		<div id="content_box">
			<div class="offers-header clearfix">
				<?php if ( $mts_options['mts_offers_page_badge'] == '1' ) { ?>
					<a href="<?php echo esc_url( get_the_permalink() );?>" class="offers-badge">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</a>
				<?php } ?>
				<div class="offers-header-right">
					<div class="offers-header-right-inner">
					<?php if ( !empty($mts_options['mts_offers_page_header_text'] ) ) { ?>
						<h2 class="offers-heading"><?php echo $mts_options['mts_offers_page_header_text']; ?></h2>
					<?php } ?>
					<?php mts_social_buttons('offers'); ?>
					<?php if ( $mts_options['mts_offers_subscribe'] == '1' ) { ?>
						<a href="#offers-subscribe" class="offers-subscribe offers-subscribe-button button"><i class="fa fa-envelope"></i><?php _e('Never miss an offer!','mythemeshop')?></a>
					<?php } ?>
					</div>
				</div>
				<?php if ( $mts_options['mts_offers_page_filter'] == '1' ) { ?>
					<div class="offers-filter-menu-container">
					<?php if ( !empty( $mts_options['mts_offers_page_filter_menu'] ) ) { ?>
						<ul class="offers-filter-menu clearfix">
						<?php
						foreach($mts_options['mts_offers_page_filter_menu'] as $item) :
							$product_category_id = $item['mts_offers_page_filter_menu_category'];
							$term = get_term_by( 'id', $product_category_id, 'product_cat' );
						?>
							<li class="offers-filter-menu-item">
								<a href="#" data-id="<?php echo esc_attr( $product_category_id ); ?>" data-slug="<?php echo esc_attr( $term->slug ); ?>" class="offers-filter-action"><?php echo esc_html( $term->name ); ?></a>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php } ?>
					</div>
				<?php } ?>
			</div>
		<?php if ( $mts_options['mts_offers_slider'] == '1' ) { ?>
			<div class="primary-slider-container clearfix loading">
				<div id="offers-slider" class="primary-slider">
					<?php
					if ( !empty( $mts_options['mts_custom_offers_slider'] ) ) {
						foreach($mts_options['mts_custom_offers_slider'] as $slide) :

							$image_id    = $slide['mts_custom_offers_slider_image'];
							$slide_link  = $slide['mts_custom_offers_slider_link'];
						?>
						<div class="offers-slider-item">
							<a href="<?php echo $slide_link; ?>">
							<?php
							if ( !empty( $image_id ) ) {

								$image_array = wp_get_attachment_image_src( $image_id, 'full' );
								$image_url = $image_array[0];

								$image_url = bfi_thumb( $image_url, array( 'width' => 1042, 'height' => 360, 'crop' => true ) );

							} else {

								$image_url = get_template_directory_uri().'/images/nothumb-806x376.png';
							}

							echo '<img src="'.esc_attr( $image_url ).'">';
							?>
							</a>
						</div><!--.offers-slider-item-->
						<?php
						endforeach;
					}
					?>
				</div><!--#offers-slider-->
			</div><!--.primary-slider-container-->
		<?php } ?>

		<div class="mts-ad-widgets offers-ad-widgets clearfix">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'offers-ads' ) ) : ?><?php endif; ?>
		</div>

		<div class="offer-products-filter-data">
			<?php wc_get_template( 'offer-products.php' ); ?>
		</div>

		<?php if ( $mts_options['mts_offers_subscribe'] == '1' ) { ?>
			<div id="offers-subscribe" class="offers-subscribe-widget clearfix">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'offers-subscribe' ) ) : ?><?php endif; ?>
			</div>
		<?php } ?>
		</div>
	</article>
<?php get_footer(); ?>