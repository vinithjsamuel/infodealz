<?php
/**
 * On Sale Products Loop
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$mts_options = get_option( MTS_THEME_NAME );

global $product, $woocommerce_loop;

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page' => $mts_options['mts_offers_page_posts_num'],
    'paged' => $paged,
    'meta_query' => array(
        array(
            'key' => '_visibility',
            'value' => array('catalog', 'visible'),
            'compare' => 'IN'
        ),
        array(
            'key' => '_sale_price',
            'value' => 0,
            'compare' => '>',
            'type' => 'NUMERIC'
        )
    ),
);

if ( isset( $offers_page ) ) $args['paged'] = $offers_page;

if ( isset( $category ) ) $args['product_cat'] = $category;

$offer_products = new WP_Query( apply_filters( 'mts_on_sale_products_query', $args ) );

global $wp_query;
// Put default query object in a temp variable
$tmp_query = $wp_query;
// Now wipe it out completely
$wp_query = null;
// Re-populate the global with our custom query
$wp_query = $offer_products;

if ( $offer_products->have_posts() ) : ?>
<div class="woocommerce">
	<div class="related products offer-products">
		<input type="hidden" class="page_num" name="page_num" value="<?php echo ( isset( $offers_page ) ? $offers_page : 1 ); ?>" />
		<input type="hidden" class="max_pages" name="max_pages" value="<?php echo $offer_products->max_num_pages; ?>" />
		<div class="section-header">
			<?php if ( $mts_options['mts_offers_page_filter'] == '1' && isset( $offers_page ) ) : ?>

			<div class="pagination pagination-previous-next ajax-pagination-previous-next">
				<ul>
					<li class="nav-previous"><a href="#" class="ajax-nav-prev offers-filter-action button"><i class="fa fa-angle-left"></i></a></li>
					<li class="nav-next"><a href="#" class="ajax-nav-next offers-filter-action button"><i class="fa fa-angle-right"></i></a></li>
				</ul>
			</div>

		<?php endif; ?>
			<h2 class="section-title lined-title"><span><?php _e( 'Products on offer', 'mythemeshop' ); ?></span></h2>
		</div>
		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $offer_products->have_posts() ) : $offer_products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product-offer' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end();?>

		<?php if ( !$mts_options['mts_offers_page_filter'] == '1' || !isset( $offers_page ) ) : ?>

			<?php
			/**
			* woocommerce_after_shop_loop hook
			*
			* @hooked woocommerce_pagination - 10
			*/
			do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php endif; ?>
	</div>
</div>
<?php
else:
	wc_get_template( 'loop/no-products-found.php' );
endif;

// Restore original query object
$wp_query = $tmp_query;
// Be kind; rewind
wp_reset_postdata();