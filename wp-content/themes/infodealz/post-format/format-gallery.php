<?php
global $content_width;
if ( 696 == $content_width ) {
	$w = 696;
	$h = 398;
} else {
	$w = 1042;
	$h = 596;
}
$gallery_type = 'slider';
$gallery_shortcode = false;
if( function_exists('cfpf_post_gallery_type') ) {
	$gallery_type = cfpf_post_gallery_type();
	$gallery_shortcode = get_post_meta( get_the_ID(), '_format_gallery_shortcode', true );
}

if ( 'shortcode' == $gallery_type ) {
	if ( $gallery_shortcode ) echo do_shortcode( $gallery_shortcode );
} else {
	if ( $images = get_children( array( 'post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image' ) ) ) { ?>
		<div class="slider-container loading">
			<div class="slides gallery-slider">
				<?php
				foreach( $images as $image ) {
					$attachment_img = wp_get_attachment_image_src( $image->ID, 'full' );
					$attachment_url = $attachment_img[0];
					$image_src      = bfi_thumb( $attachment_url, array( 'width' => $w, 'height' => $h, 'crop' => true ) );

					echo '<div><img src="'.esc_attr($image_src).'" class="wp-post-image"></div>';
				}
				?>
			</div>
		</div>
	<?php }
} ?>