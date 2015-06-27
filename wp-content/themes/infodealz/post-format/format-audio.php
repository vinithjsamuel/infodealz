<?php
global $content_width;

$audio_meta = get_post_meta( get_the_ID(), '_format_audio_embed', true );
if ( $audio_meta == '' ) { ?>
	<div class="message_box warning">
		<p><?php _e( 'Please add valid embed Code.', 'mythemeshop' ); ?></p>
	</div>
<?php
} elseif ( strpos( $audio_meta, 'iframe' ) !== false || strpos( $audio_meta, 'embed' ) !== false ) {
	
	echo $audio_meta;
	
} else {

	$embed_code = wp_oembed_get( $audio_meta );
	echo $embed_code;
}
?>