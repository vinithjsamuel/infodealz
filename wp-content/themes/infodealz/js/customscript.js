jQuery.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

/*! .isOnScreen() returns bool */
jQuery.fn.isOnScreen = function(){

    var win = jQuery(window);

    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};

/*----------------------------------------------------
/* Scroll to top
/*--------------------------------------------------*/
jQuery(document).ready(function() {
    //move-to-top arrow
    jQuery("body").prepend("<a href='#blog' id='move-to-top' class='to-top animate'><i class='fa fa-angle-double-up'></i></a>");
    var scrollDes = 'html,body';
    /*Opera does a strange thing if we use 'html' and 'body' together so my solution is to do the UA sniffing thing*/
    if(navigator.userAgent.match(/opera/i)){
        scrollDes = 'html';
    }
    //show ,hide
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 160 && !jQuery('#footer-to-top').isOnScreen()) {
            jQuery('#move-to-top').addClass('filling').removeClass('hiding');
        } else {
            jQuery('#move-to-top').removeClass('filling').addClass('hiding');
        }
    });
});

/*----------------------------------------------------
/* Header Cart
/*---------------------------------------------------*/
jQuery(document).ready(function($){
    if ( $('.mts-cart-button-wrap').length ) {
        var cart_selector = '.mts-cart-button-wrap';
        var $wrapper = $(cart_selector);
        var openCart = function() {
            var $wrapper = $(cart_selector);
            if ($wrapper.find('mark').first().text() == '0') return true;
            $wrapper.addClass('cart-content-visible').removeClass('cart-content-hidden');
            $wrapper.children('.cart-contents').css('height', ($wrapper.find('.mts-cart-content').height() + 36) + 'px');
        };
        var closeCart = function() {
            var $wrapper = $(cart_selector);
            $wrapper.addClass('cart-content-hidden').removeClass('cart-content-visible');
            $wrapper.children('.cart-contents').css('height', '36px');
        };
        var $button = $wrapper.children('.mts-cart-button');
        $wrapper.css('width', $button.outerWidth()+'px');
        $button.width($button.width());
        $('#header').on('mouseenter', cart_selector, openCart) // open cart on hover
            .on('click', '.close-cart', function(e) { e.preventDefault(); }); // prevent scrolling on close-cart buttons
        jQuery(document).click(function(event) { // close on click outside
            $target = $(event.target);
            if ($target.is(cart_selector) || $target.closest(cart_selector).length > 0) { // don't close cart if click is inside
                if (!$target.is('.close-cart') && !$target.closest('.close-cart').length) return true; // except if it's the close button
            }
            closeCart();// now close
        }).on('ajaxComplete', function(event, xhr, settings) {
            if (typeof settings.data == 'string' && settings.data.indexOf('action=woocommerce_add_to_cart') > -1) {
                openCart();
            }
        });
        $('#header').on('click', '.mts-cart-product .remove', function(e) {
            $(this).closest('.mts-cart-row').fadeOut(400, function() { openCart(); });
        });
    }
});

/*----------------------------------------------------
/* Make all anchor links smooth scrolling
/*--------------------------------------------------*/
jQuery(document).ready(function($) {
 // scroll handler
  var scrollToAnchor = function( id, event ) {
    // grab the element to scroll to based on the name
    var elem = $("a[name='"+ id +"']");
    // if that didn't work, look for an element with our ID
    if ( typeof( elem.offset() ) === "undefined" ) {
      elem = $("#"+id);
    }
    // if the destination element exists
    if ( typeof( elem.offset() ) !== "undefined" ) {
      // cancel default event propagation
      event.preventDefault();
      var scroll_to = elem.offset().top;
      // do the scroll
      $('html, body').animate({
              scrollTop: scroll_to
      }, 600, 'swing', function() { if (scroll_to > 46) window.location.hash = id; } );
    }
  };
  // bind to click event
  $("a").click(function( event ) {
    // only do this if it's an anchor link
    var href = $(this).attr("href");
    if ( href && href.match("#") && href !== '#' && $(this).parents(".tabs").length !== 1 ) {
      // scroll to the location
      var parts = href.split('#'),
        url = parts[0],
        target = parts[1];
      if ((!url || url == window.location.href.split('#')[0]) && target)
        scrollToAnchor( target, event );
    }
  });
});

/*----------------------------------------------------
/* Responsive Navigation
/*--------------------------------------------------*/
if (mts_customscript.responsive ) {
    jQuery(document).ready(function($){

        var menu_wrapper = $('.secondary-navigation')
            .clone().attr('class', 'mobile-menu secondary').removeAttr('id')
            .wrap('<div id="mobile-menu-wrapper" />').parent().hide()
            .appendTo('body');

        $('.toggle-mobile-menu').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#mobile-menu-wrapper').show();
            $('body').toggleClass('mobile-menu-active');
        });
        
        // prevent propagation of scroll event to parent
        $(document).on('DOMMouseScroll mousewheel', '#mobile-menu-wrapper .mobile-menu', function(ev) {
            var $this = $(this),
                scrollTop = this.scrollTop,
                scrollHeight = this.scrollHeight,
                height = $this.height(),
                delta = (ev.type == 'DOMMouseScroll' ?
                    ev.originalEvent.detail * -40 :
                    ev.originalEvent.wheelDelta),
                up = delta > 0;
        
            var prevent = function() {
                ev.stopPropagation();
                ev.preventDefault();
                ev.returnValue = false;
                return false;
            }
        
            if (!up && -delta > scrollHeight - height - scrollTop) {
                // Scrolling down, but this will take us past the bottom.
                $this.scrollTop(scrollHeight);
                return prevent();
            } else if (up && delta > scrollTop) {
                // Scrolling up, but this will take us past the top.
                $this.scrollTop(0);
                return prevent();
            }
        });
    }).click(function() {
        jQuery('body').removeClass('mobile-menu-active');
    });
}
/*----------------------------------------------------
/*  Dropdown menu
/* ------------------------------------------------- */
jQuery(document).ready(function($) { 
	$('#navigation ul.sub-menu, #navigation ul.children').hide(); // hides the submenus in mobile menu too
	$('#navigation li').hover( 
		function() {
			$(this).children('ul.sub-menu, ul.children').slideDown('fast');
		}, 
		function() {
			$(this).children('ul.sub-menu, ul.children').hide();
		}
	);
});

/*---------------------------------------------------
/*  Vertical ( widget ) menus/lists
/* -------------------------------------------------*/
jQuery(document).ready(function($) {

    $('.widget_nav_menu, .widget_product_categories, .widget_pages, .widget_categories').addClass('toggle-menu');
    $('.toggle-menu ul.sub-menu, .toggle-menu ul.children').addClass('toggle-submenu');
    $('.toggle-menu .current-menu-item, .toggle-menu .current-cat, .toggle-menu .current_page_item').addClass('toggle-menu-current-item');
    //$('.toggle-menu .menu-item-has-children, .toggle-menu .cat-parent, .toggle-menu .page_item_has_children').addClass('toggle-menu-item-parent');
    $('.toggle-menu ul.sub-menu, .toggle-menu ul.children').parent().addClass('toggle-menu-item-parent');

    $('.toggle-menu').each(function() {
        var $this = $(this);

        $this.find('.toggle-submenu').hide();

        $this.find('.toggle-menu-current-item').last().parents('.toggle-menu-item-parent').addClass('active').children('.toggle-submenu').show();
        $this.find('.toggle-menu-item-parent').append('<span class="toggle-caret"><i class="fa fa-angle-down"></i></span>');
    });

    $('.toggle-caret').click(function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('active').children('.toggle-submenu').slideToggle('fast');
    });
});

/*----------------------------------------------------
/* Social button scripts
/*---------------------------------------------------*/
jQuery(document).ready(function($){
	(function(d, s) {
	  var js, fjs = d.getElementsByTagName(s)[0], load = function(url, id) {
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.src = url; js.id = id;
		fjs.parentNode.insertBefore(js, fjs);
	  };
	jQuery('span.facebookbtn, .facebook_like').exists(function() {
	  load('//connect.facebook.net/en_US/all.js#xfbml=1', 'fbjssdk');
	});
	jQuery('span.gplusbtn').exists(function() {
	  load('https://apis.google.com/js/plusone.js', 'gplus1js');
	});
	jQuery('span.twitterbtn').exists(function() {
	  load('//platform.twitter.com/widgets.js', 'tweetjs');
	});
	jQuery('span.linkedinbtn').exists(function() {
	  load('//platform.linkedin.com/in.js', 'linkedinjs');
	});
	jQuery('span.pinbtn').exists(function() {
	  load('//assets.pinterest.com/js/pinit.js', 'pinterestjs');
	});
	jQuery('span.stumblebtn').exists(function() {
	  load('//platform.stumbleupon.com/1/widgets.js', 'stumbleuponjs');
	});
	}(document, 'script'));
});

/*---------------------------------------------------
/*  Accordion Widget
/* -------------------------------------------------*/
jQuery(document).ready(function($) {

    if ( $('.mts-accordion').length ) {

        $('.mts-accordion').each(function() {

            var $this = $(this);

            $this.find(".mts_accordion_togglec").hide();
            $this.find(".mts_accordion_togglec:first").show().prev(".mts_accordion_togglet").addClass("mts_accordion_toggleta");
        });

        $(".mts_accordion_togglet").click(function(){

            var $this = $(this),
                $accWrap = $this.closest('.mts-accordion');

            if ( ! $this.hasClass("mts_accordion_toggleta") ) {
                $accWrap.find(".mts_accordion_toggleta").removeClass("mts_accordion_toggleta").next(".mts_accordion_togglec").slideUp("normal");
                $this.addClass("mts_accordion_toggleta").next(".mts_accordion_togglec").slideDown("normal");
            }
        });
    }
});