<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page">
    <div class="article">
        <div id="content_box">
	        <?php if($mts_options['mts_featured_slider'] == '1' && !empty( $mts_options['mts_custom_slider'] ) ) { // Featured slider?>
	            <div class="primary-slider-container clearfix loading">
	                <div id="slider" class="primary-slider">
	                <?php
                    $nav   = '';
                    foreach( $mts_options['mts_custom_slider'] as $slide ) : ?>
                        <a href="<?php echo esc_url( $slide['mts_custom_slider_link'] ); ?>">
                            <?php
                            $image_id = $slide['mts_custom_slider_image'];
                            if ( !empty( $image_id ) ) {

                                $image_array = wp_get_attachment_image_src( $image_id, 'full' );
                                $image_url = $image_array[0];

                                $image_url = bfi_thumb( $image_url, array( 'width' => 806, 'height' => 376, 'crop' => true ) );

                            } else {

                                $image_url = get_template_directory_uri().'/images/nothumb-806x376.png';
                            }
                            echo '<img src="'.esc_attr( $image_url ).'">';
                            ?>
                        </a>
                		<?php $nav.= '<div class="slider-nav-item">';
                        if ( !empty( $slide['mts_custom_slider_title'] ) ) $nav.= '<h2 class="slidertitle">'.esc_html( $slide['mts_custom_slider_title'] ).'</h2>';
                        if ( !empty( $slide['mts_custom_slider_text'] ) )  $nav.= '<p class="slidertext">'.esc_html( $slide['mts_custom_slider_text'] ).'</p>';
                        $nav.= '</div>'; 
                    endforeach;
	                ?>
	                </div><!--#slider-->
	                <?php echo '<div id="slider-nav">'.$nav.'</div>'; ?>
	            </div><!--.primary-slider-container-->
	        <?php } ?>

	        <?php if ( mts_isWooCommerce() && isset( $mts_options['mts_featured_product_categories'] ) && !empty( $mts_options['mts_featured_product_categories'] ) ) { // Featured product categories
	            foreach( $mts_options['mts_featured_product_categories'] as $featured_product_cat ) :
	                $product_cat_value = $featured_product_cat['mts_featured_product_category'];
	                $product_cat_array = explode('|', $product_cat_value, 2);
	                $product_cat_id    = $product_cat_array[0];
	                $product_cat_name  = $product_cat_array[1];
	                $subcategories = isset( $featured_product_cat['mts_featured_product_category_subcategories']) ? true : false;
	                $adds          = isset( $featured_product_cat['mts_featured_product_category_adds']) ? true : false;
	                $products      = isset( $featured_product_cat['mts_featured_product_category_products']) ? true : false; ?>
	                <section class="featured-section clearfix featured-product-cat">
		                <?php $term = get_term_by( 'id', $product_cat_id, 'product_cat' ); ?>
		                    <header class="featured-section-header clearfix featured-product-cat-header">
		                        <h3 class="featured-section-title"><?php echo esc_html( $term->name ); ?></h3>
		                        <div class="featured-section-description"><?php echo esc_html( $term->description ); ?></div>
		                    </header>
		                <?php
		                // Subcategories carousel
		                $args = array(
		                    'taxonomy'   => 'product_cat',
		                    'hide_empty' => 0,
		                    'parent'     => $product_cat_id
		                );
		                $child_cats = get_categories( $args );
		                $cat_icons  = get_option('mts_product_cat_icons');

		                if ( !empty($child_cats) && $subcategories ) { ?>
		                    <div class="carousel-container loading">
		                        <div class="subcategories-carousel">
		                        <?php
		                        foreach( $child_cats as $child_cat ) :
		                            $cat_link = get_term_link( $child_cat->slug, 'product_cat' );
		                            $cat_icon = isset( $cat_icons['product_cat'][ $child_cat->term_id ] ) ? $cat_icons['product_cat'][ $child_cat->term_id ] : 'folder';
		                        ?>
		                            <a href="<?php echo $cat_link; ?>" class="subcategory-item">
		                                <div class="subcategory-icon"><i class="fa fa-<?php echo $cat_icon; ?>"></i></div>
		                                <div class="subcategory-title"><?php echo esc_html( $child_cat->name ); ?></div>
		                            </a>
		                        <?php
		                        endforeach;
		                        ?>
		                        </div>
		                    </div>
		                <?php }

		                // Adds widgetized area
		                if ( $adds ) {
		                    $widget_area = 'home-ads-'.$product_cat_id;
		                    ?>
		                    <div id="<?php echo $widget_area;?>" class="home-ads mts-ad-widgets clearfix">
		                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $widget_area ) ) : ?><?php endif; ?>
		                    </div>
		                    <?php
		                }

		                // Product tabs
		                if ( $products ) {
		                    ?>
		                    <div class="featured-product-tabs" data-term-slug="<?php echo esc_attr( $term->slug ); ?>">
		                        <ul class="tabs">
		                            <li class="tab active loaded"><a href="#" data-tab="best_sellers_tab"><?php _e('Best sellers','mythemeshop'); ?></a></li>
		                            <li class="tab"><a href="#" data-tab="new_products_tab"><?php _e('New Arrivals','mythemeshop'); ?></a></li>
		                            <li class="tab"><a href="#" data-tab="top_rated_tab"><?php _e('Top Rated','mythemeshop'); ?></a></li>
		                        </ul>
		                        <div class="tabs-content">
		                            <div class="tab-content best_sellers_tab active">
		                                <?php
		                                $best_sellers_args = array( 'meta_key' => 'total_sales', 'orderby' => 'meta_value_num','posts_per_page' => 4, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'product_cat' => $term->slug );
		                                $best_sellers_query = new WP_Query( $best_sellers_args );

		                                if ( $best_sellers_query->have_posts() ) {

		                                    echo '<ul class="products">';

		                                    while ( $best_sellers_query->have_posts() ) {
		                                        $best_sellers_query->the_post();
		                                        wc_get_template( 'content-product-home.php' );
		                                    }

		                                    echo '</ul>';
		                                }

		                                wp_reset_postdata();
		                                ?>
		                            </div>
		                            <div class="tab-content new_products_tab">
		                            </div>
		                            <div class="tab-content top_rated_tab">
		                            </div>
		                        </div>
		                    </div>
		                    <?php
		                }
		            
		                ?>
	                </section>
	                <?php
	            endforeach;
	        }
	        ?>

	        <?php if($mts_options['mts_home_blog_section'] == '1') { // Featured blog posts ?>
	        <section class="featured-section clearfix featured-blog-posts">
	            <header class="featured-section-header clearfix featured-blog-posts-header">
	                <h3 class="featured-section-title"><?php _e( 'Latest blog posts', 'mythemeshop' ); ?></h3>
	                <!--<div class="featured-section-description"></div>-->
	            </header>
	            <div class="featured-blog-posts-container clearfix">
	            <?php
	            $blog_args = array( 'ignore_sticky_posts' => 1, 'post_status' => 'publish', 'posts_per_page' => $mts_options['mts_home_blog_section_num'] );
	            $blog_query = new WP_Query( $blog_args );
	            while ( $blog_query->have_posts() ) : $blog_query->the_post();
	            ?>
	                <article class="featured-blog-post">
	                    <div class="featured-blog-post-inner">
	                        <?php if ( has_post_thumbnail() ) {
	                            $thumb_id  = get_post_thumbnail_id();
	                            $image     = wp_get_attachment_image_src( $thumb_id, 'full' );
	                            $image_url = $image[0];
	                            $thumbnail = bfi_thumb( $image_url, array( 'width' => 176, 'height' => 148, 'crop' => true ) );
	                        } else {
	                            $thumbnail = get_template_directory_uri().'/images/nothumb-176x148.png';
	                        }
	                        ?>
	                        <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
	                        	<img src="<?php echo esc_attr( $thumbnail );?>" class="wp-post-image">
	                        </a>
	                        <header class="featured-blog-post-header">
	                            <h4 class="title featured-blog-post-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h4>
	                        </header>
	                        <div class="post-excerpt">
	                            <?php echo mts_excerpt(11); ?>
	                        </div>
	                        <div class="readMore">
	                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow">
	                                <?php _e( 'Read More', 'mythemeshop' ); ?></i>
	                            </a>
	                        </div>
	                    </div>
	                </article>
	            <?php endwhile; wp_reset_postdata(); ?>
	            </div>
	        </section>
	        <?php } ?>

	        <?php if($mts_options['mts_home_brands_section'] == '1') { // Featured brands ?>
	        <section class="featured-section clearfix featured-brands">
	            <header class="featured-section-header clearfix featured-brands-header">
	                <h3 class="featured-section-title"><?php _e( 'Brands', 'mythemeshop' ); ?></h3>
	                <div class="featured-section-description"><?php _e( 'Browse by brands', 'mythemeshop' ); ?></div>
	            </header>
	            <?php if ( !empty( $mts_options['mts_home_brands'] ) ) { ?>
	                <div class="featured-brands-container clearfix">
	                <?php
	                foreach( $mts_options['mts_home_brands'] as $brand ) :
	                    $brand_name  = $brand['mts_home_brand_name'];
	                    $brand_link  = $brand['mts_home_brand_link'];
	                    $brand_image = $brand['mts_home_brand_image'];

	                    $brand_image_url = wp_get_attachment_image_src( $brand_image, 'full' );
	                    $brand_image_url = $brand_image_url[0];
	                ?>
	                    <div class="brend-item">
	                        <a href="<?php echo esc_url( $brand_link ); ?>" title="<?php echo esc_attr( $brand_name ); ?>">
	                            <img src="<?php echo esc_attr( $brand_image_url ); ?>" />
	                        </a>
	                    </div>
	                <?php
	                endforeach;
	                ?>
	                </div>
	            <?php } ?>
	        </section>
	        <?php } ?>
	        </div>
	    </div>
	    <?php get_sidebar(); ?>
	<?php get_footer(); ?>