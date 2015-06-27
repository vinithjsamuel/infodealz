<?php
/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<nav class="woocommerce-pagination pagination clearfix">
	<?php
		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
			'base'         => esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
			'format'       => '',
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $wp_query->max_num_pages,
			'prev_text'    => '<i class="fa fa-angle-left"></i>',
			'next_text'    => '<i class="fa fa-angle-right"></i>',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3
		) ) );
	?>
	<input type="hidden" class="shop-pagination-current" value="<?php echo max( 1, get_query_var( 'paged' ) ); ?>">
	<input type="hidden" class="shop-pagination-max" value="<?php echo $wp_query->max_num_pages; ?>">
	<input type="hidden" class="shop-pagination-nextlink" value="<?php echo next_posts( $wp_query->max_num_pages, false ); ?>">
</nav>