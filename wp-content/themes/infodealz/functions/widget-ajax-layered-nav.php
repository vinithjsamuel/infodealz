<?php
class mts_wc_ajax_filter_widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'mts_wc_ajax_filter_widget',
            __('MyThemeShop: AJAX attribute filter','mythemeshop'),
            array( 'classname' => 'mts-ajax-filter-widget woocommerce widget_layered_nav', 'description' => __( 'Filter products by attributes. The widget will only show up on product archive pages corresponding to the selected attribute.','mythemeshop' ) )
        );
    }

    public function form( $instance ) {

        $defaults = array(
            'title'      => '',
            'attribute'  => '',
            'query_type' => 'and',
            'type'       => 'list',
            'use_colors' => 1,
        );

        $instance = wp_parse_args( (array)$instance, $defaults );

        ?>

        <p>
            <label>
                <strong><?php _e( 'Title', 'mythemeshop' ) ?>:</strong><br />
                <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('attribute'); ?>"><strong><?php _e('Attribute:', 'mythemeshop') ?></strong></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('attribute') ); ?>" name="<?php echo esc_attr( $this->get_field_name('attribute') ); ?>">
                <?php $this->mts_wcan_dropdown_attributes( $instance['attribute'] ); ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'query_type' ); ?>"><?php _e( 'Query Type:', 'mythemeshop' ) ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'query_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_type' ) ); ?>">
                <option value="and" <?php selected( $instance['query_type'], 'and' ); ?>><?php _e( 'AND', 'mythemeshop' ); ?></option>
                <option value="or" <?php selected( $instance['query_type'], 'or' ); ?>><?php _e( 'OR', 'mythemeshop' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><strong><?php _e('Type:', 'mythemeshop') ?></strong></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('type') ); ?>" name="<?php echo esc_attr( $this->get_field_name('type') ); ?>">
                <option value="list" <?php selected( 'list', $instance['type'] ) ?>><?php _e( 'List', 'mythemeshop' ) ?></option>
                <option value="label" <?php selected( 'label', $instance['type'] ) ?>><?php _e( 'Label', 'mythemeshop' ) ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id("use_colors"); ?>">
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("use_colors"); ?>" name="<?php echo $this->get_field_name("use_colors"); ?>" value="1" <?php if (isset($instance['use_colors'])) { checked( 1, $instance['use_colors'], true ); } ?> />
                <?php _e( 'Use attribute colors if available', 'mythemeshop'); ?>
            </label>
        </p>
    <?php
    }

    public function update( $new_instance, $old_instance ) {
        global $woocommerce;

        $instance = $old_instance;

        if ( empty( $new_instance['title'] ) ) {
            $new_instance['title'] = wc_attribute_label( $new_instance['title'] );
        }

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['attribute'] = stripslashes( $new_instance['attribute'] );
        $instance['query_type'] = stripslashes( $new_instance['query_type'] );
        $instance['type'] = stripslashes( $new_instance['type'] );
        $instance['use_colors'] = intval( $new_instance['use_colors'] );

        return $instance;
    }


    public function widget( $args, $instance ) {

        wp_enqueue_script( 'wc-ajax-attr-filters' );

        global $_chosen_attributes, $woocommerce, $_mts_attributes_array;

        extract( $args );

        if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( $_mts_attributes_array, array( 'product_cat', 'product_tag' ) ) ) || is_shop() )
            return;

        $current_term 	 = $_mts_attributes_array && is_tax( $_mts_attributes_array ) ? get_queried_object()->term_id : '';
        $current_tax 	 = $_mts_attributes_array && is_tax( $_mts_attributes_array ) ? get_queried_object()->taxonomy : '';
        $title 			 = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        $taxonomy        = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name($instance['attribute']) : '';
        $query_type 	 = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
        $display_type 	 = isset( $instance['type'] ) ? $instance['type'] : 'list';
        $use_colors      = (int) $instance['use_colors'];

        if ( ! taxonomy_exists( $taxonomy ) )
            return;

        $get_terms_args = array( 'hide_empty' => '1' );

        $orderby = wc_attribute_orderby( $taxonomy );

        switch ( $orderby ) {
            case 'name' :
                $get_terms_args['orderby']    = 'name';
                $get_terms_args['menu_order'] = false;
            break;
            case 'id' :
                $get_terms_args['orderby']    = 'id';
                $get_terms_args['order']      = 'ASC';
                $get_terms_args['menu_order'] = false;
            break;
            case 'menu_order' :
                $get_terms_args['menu_order'] = 'ASC';
            break;
        }

        $terms = get_terms( $taxonomy, $get_terms_args );

        if ( count( $terms ) > 0 ) {

            ob_start();

            $found = false;

            echo $before_widget;

            if ( !empty( $title ) ) {
                echo $before_title . $title . $after_title;
            }

            // Force found when option is selected - do not force found on taxonomy attributes
            if ( ! $_mts_attributes_array || ! is_tax( $_mts_attributes_array ) )
                if ( is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) )
                    $found = true;

            
            // List display
            echo "<ul class='mts-ajax-filter-links mts-ajax-filter-type-".$display_type."'>";

            foreach ( $terms as $term ) {

                // Get count based on current view - uses transients
                $transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );

                if ( false === ( $_products_in_term = get_transient( $transient_name ) ) ) {

                    $_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );

                    set_transient( $transient_name, $_products_in_term );
                }

                $option_is_set = ( isset( $_chosen_attributes[ $taxonomy ] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) );

                // If this is an AND query, only show options with count > 0
                if ( $query_type == 'and' ) {

                    $count = sizeof( array_intersect( $_products_in_term, $woocommerce->query->filtered_product_ids ) );

                    // skip the term for the current archive
                    if ( $current_term == $term->term_id )
                        continue;

                    if ( $count > 0 && $current_term !== $term->term_id )
                        $found = true;


                // If this is an OR query, show all options so search can be expanded
                } else {

                    // skip the term for the current archive
                    if ( $current_term == $term->term_id )
                        continue;

                    $count = sizeof( array_intersect( $_products_in_term, $woocommerce->query->unfiltered_product_ids ) );

                    if ( $count > 0 )
                        $found = true;

                }

                $arg = 'filter_' . sanitize_title( $instance['attribute'] );

                $current_filter = ( isset( $_GET[ $arg ] ) ) ? explode( ',', $_GET[ $arg ] ) : array();

                if ( ! is_array( $current_filter ) )
                    $current_filter = array();

                $current_filter = array_map( 'esc_attr', $current_filter );

                if ( ! in_array( $term->term_id, $current_filter ) )
                    $current_filter[] = $term->term_id;

                // Base Link decided by current page
                if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
                    $link = home_url();
                } elseif ( is_post_type_archive( 'product' ) || is_page( function_exists( 'wc_get_page_id' ) ? wc_get_page_id('shop') : woocommerce_get_page_id('shop') ) ) {
                    $link = get_post_type_archive_link( 'product' );
                } else {
                    $link = get_term_link( get_query_var('term'), get_query_var('taxonomy') );
                }

                // All current filters
                if ( $_chosen_attributes ) {
                    foreach ( $_chosen_attributes as $name => $data ) {
                        if ( $name !== $taxonomy ) {

                            // Exclude query arg for current term archive term
                            while ( in_array( $current_term, $data['terms'] ) ) {
                                $key = array_search( $current_term, $data );
                                unset( $data['terms'][$key] );
                            }

                            // Remove pa_ and sanitize
                            $filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

                            if ( ! empty( $data['terms'] ) ){
                                $link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
                            }

                            if ( $data['query_type'] == 'or' ){
                                $link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
                            }
                        }
                    }
                }

                // Min/Max
                if ( isset( $_GET['min_price'] ) ){
                    $link = add_query_arg( 'min_price', $_GET['min_price'], $link );
                }

                if ( isset( $_GET['max_price'] ) ){
                    $link = add_query_arg( 'max_price', $_GET['max_price'], $link );
                }

                // Current Filter = this widget
                if ( isset( $_chosen_attributes[ $taxonomy ] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) {
                    $class = 'class="chosen"';

                    // Remove this term is $current_filter has more than 1 term filtered
                    if ( sizeof( $current_filter ) > 1 ) {
                        $current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
                        $link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
                    }

                } else {
                    $class = '';
                    $link = add_query_arg( $arg, implode( ',', $current_filter ), $link );

                }

                // Search Arg
                if ( get_search_query() )
                    $link = add_query_arg( 's', get_search_query(), $link );

                // Post Type Arg
                if ( isset( $_GET['post_type'] ) )
                    $link = add_query_arg( 'post_type', $_GET['post_type'], $link );

                // Query type Arg
                if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_attributes[ $taxonomy ]['terms'] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) )
                    $link = add_query_arg( 'query_type_' . sanitize_title( $instance['attribute'] ), 'or', $link );

                $term_taxonomy = $term->taxonomy;
                $term_id = $term->term_id;
                $tax_color_codes = get_option('mts_tax_color_codes');

                $label_color = '';
                if ( 'label' == $display_type && 1 == $use_colors ) {
                    if ( isset( $tax_color_codes[ $term_taxonomy ][ $term_id ] ) ) {
                        $label_color = ' style="background:'.$tax_color_codes[ $term_taxonomy ][ $term_id ].'"';
                    }
                }

                echo '<li ' . $class . '>';

                echo ( $count > 0 || $option_is_set ) ? '<a href="' . esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) . '"'.$label_color.'>' : '<span>';

                if ( 'list' == $display_type && 1 == $use_colors ) {
                    if ( isset( $tax_color_codes[ $term_taxonomy ][ $term_id ] ) ) {
                        echo '<span class="color-swatch" style="background:'.$tax_color_codes[ $term_taxonomy ][ $term_id ].'"></span>';
                    }
                }

                echo $term->name;

                echo ( $count > 0 || $option_is_set ) ? '</a>' : '</span>';

                if( $count != 0 && 'label' !== $display_type ) {
                    echo ' <small class="count">' . $count . '</small>';
                }

                echo '</li>';
            }

            echo "</ul>";

            

            echo $after_widget;

            if ( ! $found )  {
                ob_end_clean();
                //echo substr($before_widget, 0, strlen($before_widget) - 1) . ' style="display:none">' . $after_widget;
            } else {
                echo ob_get_clean();
            }
        }
    }

    public function mts_wcan_dropdown_attributes( $selected ) {
        global $woocommerce;

        if ( ! isset( $woocommerce ) ) return array();

        $attributes = array();

        $attribute_taxonomies = wc_get_attribute_taxonomies();

        if( empty( $attribute_taxonomies ) ) return array();
        foreach( $attribute_taxonomies as $attribute ) {

            $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);

            if ( taxonomy_exists( $taxonomy ) ) {
                $attributes[] = $attribute->attribute_name;
            }
        }

        $options = "";

        foreach( $attributes as $attribute ) {
            echo "<option name='{$attribute}'". selected( $attribute, $selected, false ) .">{$attribute}</option>";
        }
    }
}
// Register Widget
add_action( 'widgets_init', 'mts_register_ajax_nav_widget' );
function mts_register_ajax_nav_widget() {
    register_widget( "mts_wc_ajax_filter_widget" );
}