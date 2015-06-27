<?php
/*-----------------------------------------------------------------------------------

	Plugin Name: MyThemeShop Accordion Widget
	Version: 1.0
	
-----------------------------------------------------------------------------------*/


class mts_accordion_widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'mts_accordion_widget',
			__( 'MyThemeShop: Accordion', 'mythemeshop' ),
			array( 'description' => __( 'Display custom accordion containing up to 5 toggles.', 'mythemeshop' ) )
		);
	}

 	public function form( $instance ) {

		$defaults = array(
			'title'            => '',
			'toggle_1_title'   => '',
			'toggle_1_content' => '',
			'toggle_2_title'   => '',
			'toggle_2_content' => '',
			'toggle_3_title'   => '',
			'toggle_3_content' => '',
			'toggle_4_title'   => '',
			'toggle_4_content' => '',
			'toggle_5_title'   => '',
			'toggle_5_content' => '',
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = isset( $instance['title'] ) ? $instance[ 'title' ] : '';

		$toggle_1_title = strip_tags( $instance['toggle_1_title'] );
		$toggle_2_title = strip_tags( $instance['toggle_2_title'] );
		$toggle_3_title = strip_tags( $instance['toggle_3_title'] );
		$toggle_4_title = strip_tags( $instance['toggle_4_title'] );
		$toggle_5_title = strip_tags( $instance['toggle_5_title'] );

		$toggle_1_content = esc_textarea( $instance['toggle_1_content'] );
		$toggle_2_content = esc_textarea( $instance['toggle_2_content'] );
		$toggle_3_content = esc_textarea( $instance['toggle_3_content'] );
		$toggle_4_content = esc_textarea( $instance['toggle_4_content'] );
		$toggle_5_content = esc_textarea( $instance['toggle_5_content'] );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'toggle_1_title' ); ?>"><?php _e( 'Toogle 1 Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'toggle_1_title' ); ?>" name="<?php echo $this->get_field_name( 'toggle_1_title' ); ?>" type="text" value="<?php echo esc_attr( $toggle_1_title ); ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'toggle_1_content' ); ?>"><?php _e( 'Toogle 1 Content:','mythemeshop' ); ?></label>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('toggle_1_content'); ?>" name="<?php echo $this->get_field_name('toggle_1_content'); ?>"><?php echo $toggle_1_content; ?></textarea>

		<p>
			<label for="<?php echo $this->get_field_id( 'toggle_2_title' ); ?>"><?php _e( 'Toogle 2 Title:','mythemeshop' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'toggle_2_title' ); ?>" name="<?php echo $this->get_field_name( 'toggle_2_title' ); ?>" type="text" value="<?php echo esc_attr( $toggle_2_title ); ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'toggle_2_content' ); ?>"><?php _e( 'Toogle 2 Content:','mythemeshop' ); ?></label>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('toggle_2_content'); ?>" name="<?php echo $this->get_field_name('toggle_2_content'); ?>"><?php echo $toggle_2_content; ?></textarea>

		<p>
			<label for="<?php echo $this->get_field_id( 'toggle_3_title' ); ?>"><?php _e( 'Toogle 3 Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'toggle_3_title' ); ?>" name="<?php echo $this->get_field_name( 'toggle_3_title' ); ?>" type="text" value="<?php echo esc_attr( $toggle_3_title ); ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'toggle_3_content' ); ?>"><?php _e( 'Toogle 3 Content:','mythemeshop' ); ?></label>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('toggle_3_content'); ?>" name="<?php echo $this->get_field_name('toggle_3_content'); ?>"><?php echo $toggle_3_content; ?></textarea>

		<p>
			<label for="<?php echo $this->get_field_id( 'toggle_4_title' ); ?>"><?php _e( 'Toogle 4 Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'toggle_4_title' ); ?>" name="<?php echo $this->get_field_name( 'toggle_4_title' ); ?>" type="text" value="<?php echo esc_attr( $toggle_4_title ); ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'toggle_4_content' ); ?>"><?php _e( 'Toogle 4 Content:','mythemeshop' ); ?></label>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('toggle_4_content'); ?>" name="<?php echo $this->get_field_name('toggle_4_content'); ?>"><?php echo $toggle_4_content; ?></textarea>

		<p>
			<label for="<?php echo $this->get_field_id( 'toggle_5_title' ); ?>"><?php _e( 'Toogle 5 Title:','mythemeshop' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'toggle_5_title' ); ?>" name="<?php echo $this->get_field_name( 'toggle_5_title' ); ?>" type="text" value="<?php echo esc_attr( $toggle_5_title ); ?>" />
		</p>

		<label for="<?php echo $this->get_field_id( 'toggle_5_content' ); ?>"><?php _e( 'Toogle 5 Content:','mythemeshop' ); ?></label>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('toggle_5_content'); ?>" name="<?php echo $this->get_field_name('toggle_5_content'); ?>"><?php echo $toggle_5_content; ?></textarea>
		
		<p>
			<input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs in toggle content', 'mythemeshop'); ?></label>
		</p>

		<?php 
	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['toggle_1_title'] = strip_tags( $new_instance['toggle_1_title'] );
		$instance['toggle_2_title'] = strip_tags( $new_instance['toggle_2_title'] );
		$instance['toggle_3_title'] = strip_tags( $new_instance['toggle_3_title'] );
		$instance['toggle_4_title'] = strip_tags( $new_instance['toggle_4_title'] );
		$instance['toggle_5_title'] = strip_tags( $new_instance['toggle_5_title'] );

		if ( current_user_can('unfiltered_html') ) {

			$instance['toggle_1_content'] = $new_instance['toggle_1_content'];
			$instance['toggle_2_content'] = $new_instance['toggle_2_content'];
			$instance['toggle_3_content'] = $new_instance['toggle_3_content'];
			$instance['toggle_4_content'] = $new_instance['toggle_4_content'];
			$instance['toggle_5_content'] = $new_instance['toggle_5_content'];

		} else {

			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed

			$instance['toggle_1_content'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['toggle_1_content'] ) ) ); // wp_filter_post_kses() expects slashed
			$instance['toggle_2_content'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['toggle_2_content'] ) ) ); // wp_filter_post_kses() expects slashed
			$instance['toggle_3_content'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['toggle_3_content'] ) ) ); // wp_filter_post_kses() expects slashed
			$instance['toggle_4_content'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['toggle_4_content'] ) ) ); // wp_filter_post_kses() expects slashed
			$instance['toggle_5_content'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['toggle_5_content'] ) ) ); // wp_filter_post_kses() expects slashed
		}

		$instance['filter'] = isset( $new_instance['filter'] );
		
		return $instance;
	}

	public function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$toggles['1']['title'] = $instance['toggle_1_title'];
		$toggles['2']['title'] = $instance['toggle_2_title'];
		$toggles['3']['title'] = $instance['toggle_3_title'];
		$toggles['4']['title'] = $instance['toggle_4_title'];
		$toggles['5']['title'] = $instance['toggle_5_title'];

		$toggles['1']['content'] = apply_filters( 'widget_text', empty( $instance['toggle_1_content'] ) ? '' : $instance['toggle_1_content'], $instance );
		$toggles['2']['content'] = apply_filters( 'widget_text', empty( $instance['toggle_2_content'] ) ? '' : $instance['toggle_2_content'], $instance );
		$toggles['3']['content'] = apply_filters( 'widget_text', empty( $instance['toggle_3_content'] ) ? '' : $instance['toggle_3_content'], $instance );
		$toggles['4']['content'] = apply_filters( 'widget_text', empty( $instance['toggle_4_content'] ) ? '' : $instance['toggle_4_content'], $instance );
		$toggles['5']['content'] = apply_filters( 'widget_text', empty( $instance['toggle_5_content'] ) ? '' : $instance['toggle_5_content'], $instance );
		
		echo $before_widget;

			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;

			echo '<div class="mts-accordion">';

			foreach ( $toggles as $key => $values ) {
				if ( !empty( $values['title'] ) || !empty( $values['content'] ) ) {
					?>

					<div class="toggle clearfix mts_accordion_toggle">
						<div class="mts_accordion_togglet"><span><?php echo $values['title']; ?></span></div>
						<div class="mts_accordion_togglec clearfix"><?php echo !empty( $instance['filter'] ) ? wpautop( $values['content'] ) : $values['content']; ?></div>
					</div>
					
					<?php
				}
			}

			echo '</div>';

		echo $after_widget;
	}
}

// Register Widget
add_action( 'widgets_init', 'mts_register_accordion_widget' );
function mts_register_accordion_widget() {
	register_widget( "mts_accordion_widget" );
}