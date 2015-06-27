<?php
$mts_options = get_option(MTS_THEME_NAME);
/*------------[ Meta ]-------------*/
if ( ! function_exists( 'mts_meta' ) ) {
	function mts_meta(){
	global $mts_options, $post;
?>
<?php if ( !empty( $mts_options['mts_favicon'] ) ) { ?>
<link rel="icon" href="<?php echo esc_url( $mts_options['mts_favicon'] ); ?>" type="image/x-icon" />
<?php } ?>
<?php if ( !empty( $mts_options['mts_metro_icon'] ) ) { ?>
    <!-- IE10 Tile.-->
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta name="msapplication-TileImage" content="<?php echo esc_attr( $mts_options['mts_metro_icon'] ); ?>">
<?php } ?>
<!--iOS/android/handheld specific -->
<?php if ( !empty( $mts_options['mts_touch_icon'] ) ) { ?>
    <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url( $mts_options['mts_touch_icon'] ); ?>" />
<?php } ?>
<?php if ( ! empty( $mts_options['mts_responsive'] ) ) { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php } ?>
<?php if($mts_options['mts_prefetching'] == '1') { ?>
    <?php if (is_front_page()) { ?>
        <?php $my_query = new WP_Query('posts_per_page=1'); while ($my_query->have_posts()) : $my_query->the_post(); ?>
        <link rel="prefetch" href="<?php the_permalink(); ?>">
        <link rel="prerender" href="<?php the_permalink(); ?>">
        <?php endwhile; wp_reset_postdata(); ?>
    <?php } elseif (is_singular()) { ?>
        <link rel="prefetch" href="<?php echo esc_url( home_url() ); ?>">
        <link rel="prerender" href="<?php echo esc_url( home_url() ); ?>">
    <?php } ?>
<?php } ?>
<meta itemprop="name" content="<?php bloginfo( 'name' ); ?>" />
<meta itemprop="url" content="<?php echo esc_attr( site_url() ); ?>" />
<?php if ( is_singular() ) { ?>
<meta itemprop="creator accountablePerson" content="<?php $user_info = get_userdata($post->post_author); echo $user_info->first_name.' '.$user_info->last_name; ?>" />
<?php } ?>
<?php }
}

/*------------[ Head ]-------------*/
if ( ! function_exists( 'mts_head' ) ){
	function mts_head() {
	global $mts_options;
        echo $mts_options['mts_header_code'];
    }
}
add_action('wp_head', 'mts_head');

/*------------[ Copyrights ]-------------*/
if ( ! function_exists( 'mts_copyrights_credit' ) ) {
	function mts_copyrights_credit() { 
	global $mts_options
?>
<!--start copyrights-->
<div class="row" id="copyright-note">
<span><a href="<?php echo esc_url( trailingslashit( home_url() ) ); ?>" title="<?php bloginfo('description'); ?>" rel="nofollow"><?php bloginfo('name'); ?></a> Copyright &copy; <?php echo date("Y") ?>.</span>
<div class="top"></div>
<div class="top"><?php echo $mts_options['mts_copyrights']; ?>&nbsp;<a href="#blog" class="toplink" rel="nofollow"><?php _e('Back to Top','mythemeshop'); ?><i class=" fa fa-chevron-up
	"></i></a></div>
</div>
<!--end copyrights-->
<?php }
}

/*------------[ Credit Cards ]-------------*/
if ( ! function_exists( 'mts_credit_cards' ) ) {
    function mts_credit_cards() { 
        global $mts_options;

        if ( !empty( $mts_options['mts_accepted_payment_method_images'] ) ) {
        ?>
        <ul class="card-list">
        <?php
        foreach( $mts_options['mts_accepted_payment_method_images'] as $image ) {
            $image_title      = $image['mts_payment_method_title'];
            $card             = $image['mts_payment_method_image'];

            $custom_image_src = wp_get_attachment_image_src( $image['mts_payment_method_custom_image'], 'full' );
            $custom_image_src = $custom_image_src[0];

            $src = ( empty( $custom_image_src ) ) ? get_template_directory_uri().'/options/img/credit-cards/'.$card.'.png' : $custom_image_src;
        ?>
            <li><img src="<?php echo esc_attr( $src ); ?>" title="<?php echo esc_attr( $image_title ); ?>" /></li>
        <?php
        }
        ?>
        </ul><!-- .card-list -->
        <?php
        }
    }
}

/*------------[ footer ]-------------*/
if ( ! function_exists( 'mts_footer' ) ) {
	function mts_footer() { 
	global $mts_options
?>
<?php if ($mts_options['mts_analytics_code'] != '') { ?>
<!--start footer code-->
<?php echo $mts_options['mts_analytics_code']; ?>
<!--end footer code-->
<?php } ?>
<?php }
}

/*------------[ breadcrumb ]-------------*/
if ( !function_exists('mts_the_breadcrumb') ) {

    function mts_the_breadcrumb() {

        if ( mts_isWooCommerce() /*&& is_woocommerce()*/ ) {

            woocommerce_breadcrumb();

        } else {

            echo '<span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="';
            echo home_url();
            echo '" rel="nofollow">'.__('Home','mythemeshop');
            echo "</a></span><span class='delimiter fa fa-angle-right'></span>";
            if (is_category() || is_single()) {
                $categories = get_the_category();
                $output = '';
                if($categories){
                    foreach($categories as $category) {
                        echo '<span typeof="v:Breadcrumb"><a href="'.esc_url( get_category_link( $category->term_id ) ).'" rel="v:url" property="v:title">'.esc_html( $category->cat_name ).'</a></span><span class="delimiter fa fa-angle-right"></span>';
                    }
                }
                if (is_single()) {
                    echo "<span><span>";
                    the_title();
                    echo "</span></span>";
                }
            } elseif (is_page()) {
                echo "<span><span>";
                the_title();
                echo "</span></span>";
            }
        }
    }
}

/*------------[ schema.org-enabled the_category() and the_tags() ]-------------*/
function mts_the_category( $separator = ', ' ) {
    $categories = get_the_category();
    $count = count($categories);
    foreach ( $categories as $i => $category ) {
        echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . sprintf( __( "View all posts in %s", 'mythemeshop' ), esc_attr( $category->name ) ) . '" ' . ' itemprop="articleSection">' . esc_html( $category->name ).'</a>';
        if ( $i < $count - 1 )
            echo $separator;
    }
}
function mts_the_tags($before = null, $sep = ', ', $after = '') {
    if ( null === $before ) 
        $before = __('Tags: ', 'mythemeshop');
    
    $tags = get_the_tags();
    if (empty( $tags ) || is_wp_error( $tags ) ) {
        return;
    }
    $tag_links = array();
    foreach ($tags as $tag) {
        $link = get_tag_link($tag->term_id);
        $tag_links[] = '<a href="' . esc_url( $link ) . '" rel="tag" itemprop="keywords">' . esc_html( $tag->name ) . '</a>';
    }
    echo $before.join($sep, $tag_links).$after;
}

/*------------[ pagination ]-------------*/
if (!function_exists('mts_pagination')) {
    function mts_pagination($pages = '', $range = 3) { 
        $showitems = ($range * 3)+1;
        global $paged; if(empty($paged)) $paged = 1;
        if($pages == '') {
            global $wp_query; $pages = $wp_query->max_num_pages; 
            if(!$pages){ $pages = 1; } 
        }
        if(1 != $pages) { 
            echo "<div class='pagination'><ul>";
            if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
                echo "<li><a rel='nofollow' href='".esc_url( get_pagenum_link(1) )."'><i class='fa fa-angle-double-left'></i> ".__('First','mythemeshop')."</a></li>";
            if($paged > 1 && $showitems < $pages) 
                echo "<li><a rel='nofollow' href='".esc_url( get_pagenum_link($paged - 1) )."' class='inactive'><i class='fa fa-angle-left'></i> ".__('Previous','mythemeshop')."</a></li>";
            for ($i=1; $i <= $pages; $i++){ 
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) { 
                    echo ($paged == $i)? "<li class='current'><span class='currenttext'>".$i."</span></li>":"<li><a rel='nofollow' href='".esc_url( get_pagenum_link($i) )."' class='inactive'>".$i."</a></li>";
                } 
            } 
            if ($paged < $pages && $showitems < $pages) 
                echo "<li><a rel='nofollow' href='".esc_url( get_pagenum_link($paged + 1) )."' class='inactive'>".__('Next','mythemeshop')." <i class='fa fa-angle-right'></i></a></li>";
            if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
                echo "<li><a rel='nofollow' class='inactive' href='".esc_url( get_pagenum_link($pages) )."'>".__('Last','mythemeshop')." <i class='fa fa-angle-double-right'></i></a></li>";
                echo "</ul></div>"; 
        }
    }
}

/*------------[ Cart ]-------------*/
if ( ! function_exists( 'mts_cart' ) ) {
	function mts_cart() { 
	   if (mts_isWooCommerce()) {

	    global $mts_options, $woocommerce;

       $cart_contents_count = $woocommerce->cart->cart_contents_count;
?>
<div class="mts-cart">
	<a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="mts-cart-button cart-contents">
        <i class="fa fa-shopping-cart"></i><?php echo __( 'Cart', 'mythemeshop') . '<mark>' . esc_html( $cart_contents_count ) . '</mark>'; ?>
    </a>
</div>
<?php } 
    }
    function mts_cart_button() {
       if (mts_isWooCommerce()) {

        global $mts_options, $woocommerce;

        /* 
            Number of product rows in header cart
            By default, 5 rows + 1 that is either placeholder for the rest, 
            or a normal row if there are exactly 6 products in the cart.
        */
        $cart_products_limit = apply_filters( 'mts_header_cart_products_limit', 5 );

        $cart_contents_count = $woocommerce->cart->cart_contents_count;
        $items_count = count($woocommerce->cart->cart_contents); // counts multiple instances of same product as one
        ?>
            <div class="cart-content-wrapper">
            <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" class="cart-link">
                <i class="fa fa-shopping-cart"></i><?php echo __( 'Cart', 'mythemeshop') . '<mark>' . esc_html( $cart_contents_count ) . '</mark>'; ?>
            </a>
            <a href="#" class="close-cart">
                <i class="fa fa-times"></i>
            </a>
            <div class="mts-cart-content">
                <?php if ( $cart_contents_count != '0' ) { ?>
                    <div class="mts-cart-content-body">
                    <table class="cart__summary">
                        <thead>
                            <tr><th><?php _e('Your Order', 'mythemeshop'); ?></th><th><?php _e('Price', 'mythemeshop'); ?></th></tr>
                        </thead>
                    <?php 
                    $i = 0;
                    $remaining_price = 0;
                    $remaining_items = 0;
                    foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
                        $i++;
                        $cart_item_data = $cart_item['data'];
                        if ( $cart_item_data->exists() && $cart_item['quantity'] > 0 ) {
                            if ($i <= $cart_products_limit || ($i == $cart_products_limit+1 && $i == $items_count)) { // account for 1 remaining: show it normally
                                $product_title     = $cart_item_data->get_title();
                                $product_permalink = get_permalink( $cart_item['product_id'] );
                                ?>

                                <tr class="mts-cart-row">
                                    <td class="mts-cart-product">
                                        <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_title ); ?> </a>
                                        <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key ); ?>
                                    </td>
                                    <td class="mts-cart-price">
                                        <?php echo woocommerce_price( $cart_item_data->get_price() ); ?>
                                        <?php if ( $cart_item['quantity'] > 1 ) echo ' &times; ' . $cart_item['quantity']; ?>
                                    </td>
                                </tr>
                            <?php
                            } else {
                                $remaining_price += floatval($cart_item_data->get_price()) * $cart_item['quantity'];
                                $remaining_items += $cart_item['quantity'];
                            }
                        }
                    }
                    if ($remaining_items) {
                        ?>
                        <tr class="mts-cart-row more-items">
                            <td class="mts-cart-product">
                                <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>"><?php echo sprintf( __( "[%s &times; ...]", 'mythemeshop' ), $remaining_items ); ?></a>
                            </td>
                            <td class="mts-cart-price">
                                <?php echo woocommerce_price( $remaining_price ); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tfoot>
                        <tr class="mts-cart-total"><th colspan="2"><?php _e( 'Total:', 'mythemeshop' ); ?> <span class="cart__total"><?php echo $woocommerce->cart->get_cart_total(); ?></span></th></tr>
                    </tfoot>
                    </table>
                    </div>
                    <div class="mts-cart-content-footer clearfix">
                        <a href="#" class="mts-cart-continue close-cart"><?php _e( 'Continue shopping', 'mythemeshop' ); ?></a>
                        <a href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ) ?>" class="button mts-cart-button"><?php _e( 'Checkout', 'mythemeshop' ); ?></a>
                        <a href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ) ?>" class="button mts-cart-button"><?php _e( 'Cart', 'mythemeshop' ); ?></a>
                    </div>
                <?php } ?>
                </div>
            </div>
<?php
    }
    }
}

/*------------[ Related Posts ]-------------*/
if (!function_exists('mts_related_posts')) {
    function mts_related_posts() {
        global $post;
        $mts_options = get_option(MTS_THEME_NAME);
        if(!empty($mts_options['mts_related_posts'])) { ?>	
    		<!-- Start Related Posts -->
    		<?php 
            $empty_taxonomy = false;
            if (empty($mts_options['mts_related_posts_taxonomy']) || $mts_options['mts_related_posts_taxonomy'] == 'tags') {
                // related posts based on tags
                $tags = get_the_tags($post->ID); 
                if (empty($tags)) { 
                    $empty_taxonomy = true;
                } else {
                    $tag_ids = array(); 
                    foreach($tags as $individual_tag) {
                        $tag_ids[] = $individual_tag->term_id; 
                    }
                    $args = array( 'tag__in' => $tag_ids, 
                        'post__not_in' => array($post->ID), 
                        'posts_per_page' => $mts_options['mts_related_postsnum'], 
                        'ignore_sticky_posts' => 1, 
                        'orderby' => 'rand' 
                    );
                }
             } else {
                // related posts based on categories
                $categories = get_the_category($post->ID); 
                if (empty($categories)) { 
                    $empty_taxonomy = true;
                } else {
                    $category_ids = array(); 
                    foreach($categories as $individual_category) 
                        $category_ids[] = $individual_category->term_id; 
                    $args = array( 'category__in' => $category_ids, 
                        'post__not_in' => array($post->ID), 
                        'posts_per_page' => $mts_options['mts_related_postsnum'],  
                        'ignore_sticky_posts' => 1, 
                        'orderby' => 'rand' 
                    );
                }
             }
            if (!$empty_taxonomy) {
    		$my_query = new wp_query( $args ); if( $my_query->have_posts() ) {
    			echo '<div class="related-posts">';
                echo '<h4>'.__('Related Posts','mythemeshop').'</h4>';
                echo '<div class="clear">';
                $posts_per_row = 3;
                $j = 0;
    			while( $my_query->have_posts() ) { $my_query->the_post(); ?>
    			<article class="related-post<?php echo (++$j % $posts_per_row == 0) ? ' last' : ''; ?>">
                <?php if ( has_post_thumbnail() ) { ?>
					<a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" id="featured-thumbnail">
					    <?php
                        echo '<div class="featured-thumbnail">';
                        the_post_thumbnail(array( 223, 137, 'bfi_thumb' => true ),array('title' => ''));
                        echo '</div>';
                        ?>
                        <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
					</a>
                <?php } ?>
                    <header>
                        <a class="title" href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>
                    </header>
                </article><!--.related-post-->
    			<?php } echo '</div></div><!-- .related-posts -->'; }} wp_reset_postdata(); ?>
    	<?php }
    }
}


if ( ! function_exists('mts_the_postinfo' ) ) {
    function mts_the_postinfo( $section = 'home' ) {
        $mts_options = get_option( MTS_THEME_NAME );
        $opt_key = 'mts_'.$section.'_headline_meta_info';
        
        if ( isset( $mts_options[ $opt_key ] ) && is_array( $mts_options[ $opt_key ] ) && array_key_exists( 'enabled', $mts_options[ $opt_key ] ) ) {
            $headline_meta_info = $mts_options[ $opt_key ]['enabled'];
        } else {
            $headline_meta_info = array();
        }
        if ( ! empty( $headline_meta_info ) ) { ?>
            <div class="post-info">
                <?php foreach( $headline_meta_info as $key => $meta ) { mts_the_postinfo_item( $key ); } ?>
            </div>
        <?php }
    }
}
if ( ! function_exists('mts_the_postinfo_item' ) ) {
    function mts_the_postinfo_item( $item ) {
        switch ( $item ) {
            case 'author':
            ?>
                <span class="theauthor"><i class="fa fa-user"></i> <span itemprop="author"><?php the_author_posts_link(); ?></span></span>
            <?php
            break;
            case 'date':
            ?>
                <span class="thetime updated"><i class="fa fa-calendar"></i> <span itemprop="datePublished"><?php the_time( get_option( 'date_format' ) ); ?></span></span>
            <?php
            break;
            case 'category':
            ?>
                <span class="thecategory"><i class="fa fa-tags"></i> <?php mts_the_category(', ') ?></span>
            <?php
            break;
            case 'comment':
            ?>
                <span class="thecomment"><i class="fa fa-comments"></i> <a rel="nofollow" href="<?php echo esc_url( get_comments_link() ); ?>" itemprop="interactionCount"><?php comments_number();?></a></span>
            <?php
            break;
        }
    }
}

if (!function_exists('mts_social_buttons')) {
    function mts_social_buttons( $location = 'post' ) {
        $mts_options = get_option( MTS_THEME_NAME );

        switch ( $location ) {
            case 'post':

                if ( isset( $mts_options['mts_social_buttons'] ) && is_array( $mts_options['mts_social_buttons'] ) && array_key_exists( 'enabled', $mts_options['mts_social_buttons'] ) ) {
                    $buttons = $mts_options['mts_social_buttons']['enabled'];
                } else {
                    $buttons = array();
                }

                if ( ! empty( $buttons ) ) {
                ?>
                    <div class="shareit <?php echo $mts_options['mts_social_button_position']; ?>">
                        <?php foreach( $buttons as $key => $button ) { mts_social_button( $key ); } ?>
                    </div>
                <?php
                }

            break;
            case 'product':

                if ( isset( $mts_options['mts_product_social_buttons'] ) && is_array( $mts_options['mts_product_social_buttons'] ) && array_key_exists( 'enabled', $mts_options['mts_product_social_buttons'] ) ) {
                    $buttons = $mts_options['mts_product_social_buttons']['enabled'];
                } else {
                    $buttons = array();
                }

                if ( ! empty( $buttons ) ) {
                ?>
                    <div class="product-share-buttons">
                        <div class="shareit">
                            <?php foreach( $buttons as $key => $button ) { mts_social_button( $key ); } ?>
                        </div>
                    </div>
                <?php
                }

            break;
            case 'offers':

                if ( isset( $mts_options['mts_offers_social_buttons'] ) && is_array( $mts_options['mts_offers_social_buttons'] ) && array_key_exists( 'enabled', $mts_options['mts_offers_social_buttons'] ) ) {
                    $buttons = $mts_options['mts_offers_social_buttons']['enabled'];
                } else {
                    $buttons = array();
                }

                if ( ! empty( $buttons ) ) {
                ?>
                    <div class="offers-share">
                        <div class="shareit">
                            <?php foreach( $buttons as $key => $button ) { mts_social_button( $key ); } ?>
                        </div>
                    </div>
                <?php
                }

            break;
        }
    }
}

if ( ! function_exists('mts_social_button' ) ) {
    function mts_social_button( $button ) {
        $mts_options = get_option( MTS_THEME_NAME );
        switch ( $button ) {
            case 'twitter':
            ?>
                <!-- Twitter -->
                <span class="share-item twitterbtn">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo esc_attr( $mts_options['mts_twitter_username'] ); ?>">Tweet</a>
                </span>
            <?php
            break;
            case 'gplus':
            ?>
                <!-- GPlus -->
                <span class="share-item gplusbtn">
                    <g:plusone size="medium"></g:plusone>
                </span>
            <?php
            break;
            case 'facebook':
            ?>
                <!-- Facebook -->
                <span class="share-item facebookbtn">
                    <div id="fb-root"></div>
                    <div class="fb-like" data-send="false" data-layout="button_count" data-width="150" data-show-faces="false"></div>
                </span>
            <?php
            break;
            case 'pinterest':
                global $post;
            ?>
                <!-- Pinterest -->
                <span class="share-item pinbtn">
                    <a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' ); echo $thumb['0']; ?>&description=<?php the_title(); ?>" class="pin-it-button" count-layout="horizontal">Pin It</a>
                </span>
            <?php
            break;
            case 'linkedin':
            ?>
                <!--Linkedin -->
                <span class="share-item linkedinbtn">
                    <script type="IN/Share" data-url="<?php echo esc_url( get_the_permalink() ); ?>"></script>
                </span>
            <?php
            break;
            case 'stumble':
            ?>
                <!-- Stumble -->
                <span class="share-item stumblebtn">
                    <su:badge layout="1"></su:badge>
                </span>
            <?php
            break;
        }
    }
}

/*------------[ Class attribute for <article> element ]-------------*/
if ( ! function_exists( 'mts_article_class' ) ) {
    function mts_article_class() {
        $mts_options = get_option( MTS_THEME_NAME );
        $class = '';
        
        // sidebar or full width
        if ( mts_custom_sidebar() == 'mts_nosidebar' || is_page_template( 'page-offers.php' ) ) {
            $class = 'ss-full-width';
        } else {
            $class = 'article';
        }
        
        echo $class;
    }
}
?>