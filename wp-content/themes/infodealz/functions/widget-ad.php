<?php

// widget class
class mts_advanced_ads extends WP_Widget {

    function mts_advanced_ads() {
        $widget_ops = array('classname' => 'mts-ads');
        $this->WP_Widget('mts-ads-widget', 'MyThemeShop: Ad', $widget_ops);
    }

    function form($instance) {
        $defaults = array(
            'image_uri' => '',
            'attachment_id' => '',
            'width' => 'full-width',
            'title' => '',
            'description' => '',
            'text_position' => 'left',
            'button_link' => '',
            'button_label' => '',
            'button_position' => 'left',
            'title_color' => '',
            'desc_color' => '',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $image_uri       = $instance['image_uri'];
        $attachment_id   = abs($instance['attachment_id']);
        $width           = $instance['width'];
        $first           = isset( $instance['first'] ) ? (bool) $instance['first'] : false;
        $title           = $instance['title'];
        $description     = $instance['description'];
        $text_position   = $instance['text_position'];
        $button_label    = $instance['button_label'];
        $button_link     = $instance['button_link'];
        $button_position = $instance['button_position'];

        $text_colors     = isset( $instance['text_colors'] ) ? (bool) $instance['text_colors'] : false;
        $title_color     = $instance['title_color'];
        $desc_color      = $instance['desc_color'];

        $id_prefix = $this->get_field_id('');
    ?>
    <p>
        <div  class="" id="<?php echo $this->get_field_id('preview'); ?>">
        <?php
        if ( $image_uri != '' ) {
            echo '<img class="custom_media_image" src="' . $image_uri . '" style="margin:0 0 10px;padding:0;max-width:100%;height:auto;float:left;display:inline-block" />';
        }
        ?>
        </div>
        <input type="hidden" id="<?php echo $this->get_field_id('attachment_id'); ?>" name="<?php echo $this->get_field_name('attachment_id'); ?>" value="<?php echo $attachment_id; ?>" />
        <input type="hidden" id="<?php echo $this->get_field_id('image_uri'); ?>" name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php echo $image_uri; ?>" />
        <input type="submit" class="button" name="<?php echo $this->get_field_name('uploader_button'); ?>" id="<?php echo $this->get_field_id('uploader_button'); ?>" value="<?php _e('Select an Image', 'image_widget'); ?>" onclick="mtsImageWidgetField.uploader( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' ); return false;" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Widget Width:', 'mythemeshop'); ?></label>
        <select id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>">
            <option value="one-fourth" <?php selected($width, 'one-fourth', true); ?>><?php _e('One Fourth', 'mythemeshop'); ?></option>
            <option value="one-third" <?php selected($width, 'one-third', true); ?>><?php _e('One Third', 'mythemeshop'); ?></option>
            <option value="one-half" <?php selected($width, 'one-half', true); ?>><?php _e('One Half', 'mythemeshop'); ?></option>
            <option value="two-third" <?php selected($width, 'two-third', true); ?>><?php _e('Two Third', 'mythemeshop'); ?></option>
            <option value="three-fourth" <?php selected($width, 'three-fourth', true); ?>><?php _e('Three Fourth', 'mythemeshop'); ?></option>
            <option value="full-width" <?php selected($width, 'full-width', true); ?>><?php _e('Full Width', 'mythemeshop'); ?></option>
        </select>
    </p>

    <p>
        <input type="checkbox" id="<?php echo $this->get_field_id('first'); ?>" name="<?php echo $this->get_field_name('first'); ?>"<?php checked( $first ); ?> />
        <label for="<?php echo $this->get_field_id('first'); ?>"><?php _e( 'First ad in a row?', 'mythemeshop' ); ?></label><br />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','mythemeshop' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:','mythemeshop' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('text_position'); ?>"><?php _e('Text Position:', 'mythemeshop'); ?></label>
        <select id="<?php echo $this->get_field_id('text_position'); ?>" name="<?php echo $this->get_field_name('text_position'); ?>">
            <option value="left" <?php selected($text_position, 'left', true); ?>><?php _e('Left', 'mythemeshop'); ?></option>
            <option value="center" <?php selected($text_position, 'center', true); ?>><?php _e('Center', 'mythemeshop'); ?></option>
            <option value="right" <?php selected($text_position, 'right', true); ?>><?php _e('Right', 'mythemeshop'); ?></option>
        </select>
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e( 'Button Label:','mythemeshop' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'button_label' ); ?>" name="<?php echo $this->get_field_name( 'button_label' ); ?>" type="text" value="<?php echo esc_attr( $button_label ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id( 'button_link' ); ?>"><?php _e( 'Link:','mythemeshop' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'button_link' ); ?>" name="<?php echo $this->get_field_name( 'button_link' ); ?>" type="text" value="<?php echo esc_attr( $button_link ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_id('button_position'); ?>"><?php _e('Button Position:', 'mythemeshop'); ?></label>
        <select id="<?php echo $this->get_field_id('button_position'); ?>" name="<?php echo $this->get_field_name('button_position'); ?>">
            <option value="left" <?php selected($button_position, 'left', true); ?>><?php _e('Left', 'mythemeshop'); ?></option>
            <option value="center" <?php selected($button_position, 'center', true); ?>><?php _e('Center', 'mythemeshop'); ?></option>
            <option value="right" <?php selected($button_position, 'right', true); ?>><?php _e('Right', 'mythemeshop'); ?></option>
        </select>
    </p>

    <p>
        <input type="checkbox" class="checkbox ad-widget-mother-checkbox" id="<?php echo $this->get_field_id('text_colors'); ?>" name="<?php echo $this->get_field_name('text_colors'); ?>"<?php checked( $text_colors ); ?> />
        <label for="<?php echo $this->get_field_id('text_colors'); ?>"><?php _e( 'Change text colors?', 'mythemeshop' ); ?></label><br />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('title_color'); ?>"><?php _e('Title Color:', 'mythemeshop'); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('title_color'); ?>" name="<?php echo $this->get_field_name('title_color'); ?>" value="<?php echo $title_color; ?>" />
    </p>

    <p class="mother-checkbox-<?php echo $this->get_field_id('text_colors'); ?>">
        <label for="<?php echo $this->get_field_id('desc_color'); ?>"><?php _e('Description Color:', 'mythemeshop'); ?></label><br />
        <input type="text" class="ad-widget-color-picker" id="<?php echo $this->get_field_id('desc_color'); ?>" name="<?php echo $this->get_field_name('desc_color'); ?>" value="<?php echo $desc_color; ?>" />
    </p>
<?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['image_uri']       = strip_tags( $new_instance['image_uri'] );
        $instance['attachment_id']   = abs($new_instance['attachment_id']);
        $instance['width']           = $new_instance['width'];
        $instance['first']           = ! empty( $new_instance['first'] ) ? 1 : 0;
        $instance['title']           = strip_tags( $new_instance['title'] );
        $instance['description']     = strip_tags( $new_instance['description'] );
        $instance['text_position']   = $new_instance['text_position'];
        $instance['button_label']    = strip_tags( $new_instance['button_label'] );
        $instance['button_link']     = strip_tags( $new_instance['button_link'] );
        $instance['button_position'] = $new_instance['button_position'];

        $instance['text_colors']     = ! empty( $new_instance['text_colors'] ) ? 1 : 0;
        $instance['title_color']     = $new_instance['title_color'];
        $instance['desc_color']      = $new_instance['desc_color'];
        
        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $image_uri       = esc_url( $instance['image_uri'] );
        $attachment_id   = $instance['attachment_id'];
        $width           = !empty( $instance['width']) ? ' '.$instance['width'] : '';
        $first           = ! empty( $instance['first'] ) ? true : false;
        $title           = $instance['title'];
        $description     = $instance['description'];
        $text_position   = $instance['text_position'];
        $button_label    = $instance['button_label'];
        $button_link     = esc_url( $instance['button_link'] );
        $button_position = $instance['button_position'];

        $text_colors     = ! empty( $instance['text_colors'] ) ? true : false;
        $title_color     = $instance['title_color'];
        $desc_color      = $instance['desc_color'];

        $first_class     = ( $first ) ? ' first' : '';

        $title_inline_style = ( $text_colors && !empty( $title_color ) ) ? ' style="color:'.$title_color.'"' : '';
        $desc_inline_style  = ( $text_colors && !empty( $desc_color ) ) ? ' style="color:'.$desc_color.'"' : '';
        ?>

            <div id="<?php echo $widget_id; ?>" class="mts-ad<?php echo $width.$first_class; ?>">
                <div class="mts-ad-inner <?php if ( $image_uri != '' ) { echo 'has-img'; } ?>">
                    <?php if ( $image_uri != '' ) { ?>
                        <?php if ( !empty( $button_link ) ) { ?>
                            <a href="<?php echo $button_link; ?>" rel="nofollow">
                        <?php } ?>
                            <img src="<?php echo $image_uri; ?>" />
                        <?php if ( !empty( $button_link ) ) { ?>
                            </a>
                        <?php } ?>
                        </a>
                    <?php } ?>
                    <div class="ad-text <?php echo $text_position; ?>">
                        <?php if ( !empty( $title ) ) { ?><h4 class="ad-title"<?php echo $title_inline_style; ?>><?php echo $title; ?></h4><?php } ?>
                        <?php if ( !empty( $description ) ) { ?><div class="ad-description"<?php echo $desc_inline_style; ?>><?php echo $description; ?></div><?php } ?>
                    </div>
                    <?php if ( !empty( $button_label ) ) { ?>
                    <div class="ad-button <?php echo $button_position; ?>">
                        <a href="<?php echo $button_link; ?>" class="button"><?php echo $button_label; ?>  <i class="fa fa-angle-right"></i></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php
    }
}

// add admin scripts
add_action('admin_enqueue_scripts', 'mts_advanced_script');
function mts_advanced_script() {
    $screen = get_current_screen();
    $screen_id = $screen->id;

    if ( 'widgets' == $screen_id ) {
        wp_enqueue_media();
        wp_enqueue_script('ads_script', get_template_directory_uri() . '/js/adwidget.js', array( 'jquery', 'media-upload', 'media-views' ), '1.0', true);
        wp_localize_script( 'ads_script', 'adWidget', array(
            'frame_title' => __( 'Select an Image', 'mythemeshop' ),
            'button_title' => __( 'Insert Into Widget', 'mythemeshop' ),
        ) );
    }
}

// register widget
add_action('widgets_init', 'mts_advanced_ads_widget');
function mts_advanced_ads_widget() {
    register_widget( 'mts_advanced_ads' );
}
?>