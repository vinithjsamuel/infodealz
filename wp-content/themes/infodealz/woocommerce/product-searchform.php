<form role="search" method="get" id="searchform" class="woocommerce-product-search search-form" action="<?php echo esc_url( home_url( '/'  ) ); ?>" _lpchecked="1">
	<fieldset>
		<label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'woocommerce' ); ?></label>
		<input type="search" id="s" class="search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woocommerce' ); ?>" <?php if (!empty($mts_options['mts_ajax_search'])) echo ' autocomplete="off"'; ?> />
		<input id="search-image" class="sbutton" type="submit" value="<?php _e('Search', 'mythemeshop'); ?>" />
		<input type="hidden" name="post_type" value="product" class="post-type-input" />
		<i class="fa fa-search"></i>
	</fieldset>
</form>