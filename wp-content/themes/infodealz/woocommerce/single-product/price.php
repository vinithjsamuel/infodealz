<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>
<hr />
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<p class="price"><?php echo $product->get_price_html(); ?></p>
	<?php
	// Fix structured data error if there is no price
	if ( '' === $product->get_price() ) {
	?>
	<meta itemprop="price" content="0" />
	<?php
	} else {
	?>
	<meta itemprop="price" content="<?php echo $product->get_price(); ?>" />
	<?php
	}
	?>
	<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>