jQuery(document).ready(function() {
	function isScrolledTo(elem,top) {
		var docViewTop = jQuery(window).scrollTop(); //num of pixels hidden above current screen
		var docViewBottom = docViewTop + jQuery(window).height();

		var elemTop = jQuery(elem).offset().top - top; //num of pixels above the elem
		var elemBottom = elemTop + jQuery(elem).height();

		return ((elemTop <= docViewTop));
	}

	function stickThatMenu(sticky,catcher,top) {
		if(isScrolledTo(sticky,top)) {
			sticky.addClass('sticky-nav');
			catcher.height(sticky.height());
		} 
		var stopHeight = catcher.offset().top;
		if ( stopHeight > sticky.offset().top) {
			sticky.removeClass('sticky-nav');
			catcher.height(0);
		}
	}

	var catcher = jQuery('#catcher'),
		sticky  = jQuery('.main-container #sticky'),
		bodyTop = jQuery('body').offset().top;

	if ( sticky.length ) {
	
		jQuery(window).scroll(function() {
			stickThatMenu(sticky,catcher,bodyTop);
		});
		jQuery(window).resize(function() {
			stickThatMenu(sticky,catcher,bodyTop);
		});
	}

	/*----------------------------------------------------
	/* Social button scripts
	/*---------------------------------------------------*/
	jQuery('#catchersocial').exists(function() {

		var catcherSocial   = jQuery('#catchersocial'),
			catcherSocial2  = jQuery('#catchersocial2'),
			stickySocial    = jQuery('.shareit'),
			stickyNavHeight = 0,
			stickySocialTop = 0;

		if ( sticky.length ) {
			stickyNavHeight = sticky.height();
		}

		stickySocialTop = stickyNavHeight + bodyTop + 10;

		jQuery(window).scroll(function() {

			if ( isScrolledTo( catcherSocial, stickySocialTop ) || ( isScrolledTo( catcherSocial2, stickySocialTop + stickySocial.height() ) && stickySocial.css('bottom') == '0' ) ) {
				stickySocial.css( 'position', 'fixed' );
				stickySocial.css( 'top', stickySocialTop );
				stickySocial.css( 'bottom', 'auto' );
			}

			var stopHeight  = catcherSocial.offset().top + catcherSocial.height();
			if ( stopHeight > stickySocial.offset().top) {
				stickySocial.css('position','absolute');
				stickySocial.css('top', '0');
				stickySocial.css('bottom', 'auto');
			}

			var stopHeight2 = catcherSocial2.offset().top + catcherSocial2.height();
			if ( stopHeight2 < stickySocial.offset().top + stickySocial.height() ) {
				stickySocial.css('position','absolute');
				stickySocial.css('top', 'auto');
				stickySocial.css('bottom', '0');
			}
			
		});
	});
});