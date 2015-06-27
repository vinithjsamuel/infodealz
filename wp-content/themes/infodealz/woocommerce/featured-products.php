<?php
/**
 * Featured Products
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$mts_options = get_option( MTS_THEME_NAME );

global $product, $woocommerce_loop;

$args = array(
	'post_type'				=> 'product',
	'post_status' 			=> 'publish',
	'ignore_sticky_posts'	=> 1,
	'posts_per_page' 	    => $mts_options['mts_featured_products_num'],
	'orderby' 	            => 'date',
	'order' 	            => 'desc',
	'meta_query'			=> array(
		array(
			'key' 		=> '_visibility',
			'value' 	=> array('catalog', 'visible'),
			'compare'	=> 'IN'
		),
		array(
			'key' 		=> '_featured',
			'value' 	=> 'yes'
		)
	)
);

$products = new WP_Query( apply_filters( 'mts_featured_products_query', $args ) );

if ( $products->have_posts() ) : ?>
<div class="woocommerce">
	<div class="related products featured-products">
		<hr />
		<div class="section-header">
			<h2 class="section-title"><?php _e( 'Featured Products', 'mythemeshop' ); ?></h2>
		</div>
		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>
</div>
<?php endif;

wp_reset_postdata();