<?php
global $content_width;
if ( 696 == $content_width ) {
	$w = 696;
	$h = 398;
} else {
	$w = 1042;
	$h = 596;
}
if ( !is_single() ) { ?>
<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="nofollow" id="featured-thumbnail">
<?php } ?>
	<div class="featured-thumbnail">
		<?php if ( has_post_thumbnail() ) {
			$id        = get_post_thumbnail_id();
			$image     = wp_get_attachment_image_src( $id, 'full' );
			$image_url = $image[0];
			$thumbnail = bfi_thumb( $image_url, array( 'width' => $w, 'height' => $h, 'crop' => true ) );
			echo '<img src="'.esc_attr($thumbnail).'" class="wp-post-image">';
		}
		?>
	</div>
<?php if ( !is_single() ) { ?>
</a>
<?php } ?>