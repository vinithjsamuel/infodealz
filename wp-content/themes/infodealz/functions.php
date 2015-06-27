<?php
/*-----------------------------------------------------------------------------------*/
/*  Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define( 'MTS_THEME_NAME', 'woocart' );
require_once( dirname( __FILE__ ) . '/theme-options.php' );
if ( ! isset( $content_width ) ) $content_width = 1060;

/*-----------------------------------------------------------------------------------*/
/*  Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option( MTS_THEME_NAME );
add_theme_support( 'title-tag' );

/*-----------------------------------------------------------------------------------*/
/*  Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain( 'mythemeshop', get_template_directory().'/lang' );

// Custom translations
if ( !empty( $mts_options['translate'] )) {
    $mts_translations = get_option( 'mts_translations_'.MTS_THEME_NAME );//$mts_options['translations'];
    function mts_custom_translate( $translated_text, $text, $domain ) {
        if ( $domain == 'mythemeshop' || $domain == 'nhp-opts' ) {
            global $mts_translations;
            if ( !empty( $mts_translations[$text] )) {
                $translated_text = $mts_translations[$text];
            }
        }
        return $translated_text;
        
    }
    add_filter( 'gettext', 'mts_custom_translate', 20, 3 );
}

if ( function_exists( 'add_theme_support' ) ) add_theme_support( 'automatic-feed-links' );

/*-----------------------------------------------------------------------------------*/
/*  Disable theme updates from WordPress.org theme repository
/*-----------------------------------------------------------------------------------*/
// Check if MTS Connect plugin already done this
if ( !class_exists('mts_connection') ) {
    // If wrong updates are already shown, delete transient so that we can run our workaround
    add_action('init', 'mts_hide_themes_plugins');
    function mts_hide_themes_plugins() {
        if ( !is_admin() ) return;
        if ( false === get_site_transient( 'mts_wp_org_check_disabled' ) ) { // run only once
            delete_site_transient('update_themes' );
            delete_site_transient('update_plugins' );

            add_action('current_screen', 'mts_remove_themes_plugins_from_update' );
        }
    }
    // Hide mts themes/plugins
    function mts_remove_themes_plugins_from_update( $screen ) {
        $run_on_screens = array( 'themes', 'themes-network', 'plugins', 'plugins-network', 'update-core', 'network-update-core' );
        if ( in_array( $screen->base, $run_on_screens ) ) {
            //Themes
            if ( $themes_transient = get_site_transient( 'update_themes' ) ) {//var_dump($themes_transient);
                if ( property_exists( $themes_transient, 'response' ) && is_array( $themes_transient->response ) ) {
                    foreach ( $themes_transient->response as $key => $value ) {
                        $theme = wp_get_theme( $value['theme'] );
                        $theme_uri = $theme->get( 'ThemeURI' );
                        if ( 0 !== strpos( $theme_uri, 'mythemeshop.com' ) ) {
                            unset( $themes_transient->response[$key] );
                        }
                    }
                    set_site_transient( 'update_themes', $themes_transient );
                }
            }
            //Plugins
            if ( $plugins_transient = get_site_transient( 'update_plugins' ) ) {
                if ( property_exists( $plugins_transient, 'response' ) && is_array( $plugins_transient->response ) ) {
                    foreach ( $plugins_transient->response as $key => $value ) {
                        $plugin = get_plugin_data( WP_PLUGIN_DIR.'/'.$key, false, false );
                        $plugin_uri = $plugin['PluginURI'];
                        if ( 0 !== strpos( $plugin_uri, 'mythemeshop.com' ) ) {
                            unset( $plugins_transient->response[$key] );
                        }
                    }
                    set_site_transient( 'update_plugins', $plugins_transient );
                }
            }
            set_site_transient( 'mts_wp_org_check_disabled', time() );
        }
    }
    add_action( 'load-themes.php', 'mts_clear_check_transient' );
    add_action( 'load-plugins.php', 'mts_clear_check_transient' );
    add_action( 'upgrader_process_complete', 'mts_clear_check_transient' );
    function mts_clear_check_transient(){
        delete_site_transient( 'mts_wp_org_check_disabled');
    }
}
// Disable auto update
add_filter( 'auto_update_theme', '__return_false' );

/*-----------------------------------------------------------------------------------*/
/*  Create Homepage & Blog page on Theme Activation
/*-----------------------------------------------------------------------------------*/
if (isset($_GET['activated']) && is_admin()){
    //blog
    $blog_page_title = __('Blog','mythemeshop');
    $blog_page_content = '';
    $blog_page_template = 'page-blog.php';
    //don't change the code bellow, unless you know what you're doing
    $page_check = get_page_by_title($blog_page_title);
    $blog_page = array(
        'post_type' => 'page',
        'post_title' => $blog_page_title,
        'post_content' => $blog_page_content,
        'post_status' => 'publish',
        'post_author' => 1,
    );
    if(!isset($page_check->ID)){
        $blog_page_id = wp_insert_post($blog_page);
        if(!empty($blog_page_template)){
                update_post_meta($blog_page_id, '_wp_page_template', $blog_page_template);
        }
        $page_id = $blog_page_id;
    } else {
    $page_id = $page_check->ID;
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Disable Google Typography plugin
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_init', 'mts_deactivate_google_typography_plugin' );
function mts_deactivate_google_typography_plugin() {
    if ( in_array( 'google-typography/google-typography.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        deactivate_plugins( 'google-typography/google-typography.php' );
    }
}

// a shortcut function
function mts_isWooCommerce(){
    if(is_multisite()){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active('woocommerce/woocommerce.php');
    }
    else{
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }
}

/*------------------------------------------------------------------------------------------------------------*/
/*  Define MTS_ICONS constant containing all available icons for use in nav menus and icon select option
/*------------------------------------------------------------------------------------------------------------*/
$_mts_icons = array(
    'Misc Icons' => array(
        'glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th', 'th-list', 'check', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'cog', 'trash-o', 'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox', 'play-circle-o', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones', 'volume-off', 'volume-down', 'volume-up', 'qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print', 'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-center', 'align-right', 'align-justify', 'list', 'outdent', 'indent', 'video-camera', 'picture-o', 'pencil', 'map-marker', 'adjust', 'tint', 'pencil-square-o', 'share-square-o', 'check-square-o', 'arrows', 'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward', 'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle', 'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban', 'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'share', 'expand', 'compress', 'plus', 'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down', 'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart', 'twitter-square', 'facebook-square', 'camera-retro', 'key', 'cogs', 'comments', 'thumbs-o-up', 'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link', 'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o', 'phone-square', 'twitter', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn', 'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 'hand-o-down', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter', 'briefcase', 'arrows-alt', 'users', 'link', 'cloud', 'flask', 'scissors', 'files-o', 'paperclip', 'floppy-o', 'square', 'bars', 'list-ul', 'list-ol', 'strikethrough', 'underline', 'table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square', 'google-plus', 'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'sort', 'sort-desc', 'sort-asc', 'envelope', 'linkedin', 'undo', 'gavel', 'tachometer', 'comment-o', 'comments-o', 'bolt', 'sitemap', 'umbrella', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md', 'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o', 'ambulance', 'medkit', 'fighter-jet', 'beer', 'h-square', 'plus-square', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down', 'desktop', 'laptop', 'tablet', 'mobile', 'circle-o', 'quote-left', 'quote-right', 'spinner', 'circle', 'reply', 'github-alt', 'folder-o', 'folder-open-o', 'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 'flag-checkered', 'terminal', 'code', 'reply-all', 'star-half-o', 'location-arrow', 'crop', 'code-fork', 'chain-broken', 'question', 'info', 'exclamation', 'superscript', 'subscript', 'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher', 'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-circle-down', 'html5', 'css3', 'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v', 'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'level-up', 'level-down', 'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'caret-square-o-down', 'caret-square-o-up', 'caret-square-o-right', 'eur', 'gbp', 'usd', 'inr', 'jpy', 'rub', 'krw', 'btc', 'file', 'file-text', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square', 'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn', 'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up', 'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 'dribbble', 'skype', 'foursquare', 'trello', 'female', 'male', 'gratipay', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo', 'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'try', 'plus-square-o', 'space-shuttle', 'slack', 'envelope-square', 'wordpress', 'openid', 'university', 'graduation-cap', 'yahoo', 'google', 'reddit', 'reddit-square', 'stumbleupon-circle', 'stumbleupon', 'delicious', 'digg', 'pied-piper', 'pied-piper-alt', 'drupal', 'joomla', 'language', 'fax', 'building', 'child', 'paw', 'spoon', 'cube', 'cubes', 'behance', 'behance-square', 'steam', 'steam-square', 'recycle', 'car', 'taxi', 'tree', 'spotify', 'deviantart', 'soundcloud', 'database', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'file-powerpoint-o', 'file-image-o', 'file-archive-o', 'file-audio-o', 'file-video-o', 'file-code-o', 'vine', 'codepen', 'jsfiddle', 'life-ring', 'circle-o-notch', 'rebel', 'empire', 'git-square', 'git', 'hacker-news', 'tencent-weibo', 'qq', 'weixin', 'paper-plane', 'paper-plane-o', 'history', 'circle-thin', 'header', 'paragraph', 'sliders', 'share-alt', 'share-alt-square', 'bomb', 'futbol-o', 'tty', 'binoculars', 'plug', 'slideshare', 'twitch', 'yelp', 'newspaper-o', 'wifi', 'calculator', 'paypal', 'google-wallet', 'cc-visa', 'cc-mastercard', 'cc-discover', 'cc-amex', 'cc-paypal', 'cc-stripe', 'bell-slash', 'bell-slash-o', 'trash', 'copyright', 'at', 'eyedropper', 'paint-brush', 'birthday-cake', 'area-chart', 'pie-chart', 'line-chart', 'lastfm', 'lastfm-square', 'toggle-off', 'toggle-on', 'bicycle', 'bus', 'ioxhost', 'angellist', 'cc', 'ils', 'meanpath', 'buysellads', 'connectdevelop', 'dashcube', 'forumbee', 'leanpub', 'sellsy', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'cart-plus', 'cart-arrow-down', 'diamond', 'ship', 'user-secret', 'motorcycle', 'street-view', 'heartbeat', 'venus', 'mars', 'mercury', 'transgender', 'transgender-alt', 'venus-double', 'mars-double', 'venus-mars', 'mars-stroke', 'mars-stroke-v', 'mars-stroke-h', 'neuter', 'facebook-official', 'pinterest-p', 'whatsapp', 'server', 'user-plus', 'user-times', 'bed', 'viacoin', 'train', 'subway', 'medium'),
    'Web Application Icons' => array(
        'adjust', 'anchor', 'archive', 'area-chart', 'arrows', 'arrows-h', 'arrows-v', 'asterisk', 'at', 'ban', 'bar-chart', 'barcode', 'bars', 'bed', 'beer', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'calculator', 'calendar', 'calendar-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'cart-arrow-down', 'cart-plus', 'cc', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'comments', 'comments-o', 'compass', 'copyright', 'credit-card', 'crop', 'crosshairs', 'cube', 'cubes', 'cutlery', 'database', 'desktop', 'diamond', 'dot-circle-o', 'download', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'eyedropper', 'fax', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-pdf-o', 'file-powerpoint-o', 'file-video-o', 'file-word-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gift', 'glass', 'globe', 'graduation-cap', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat', 'history', 'home', 'inbox', 'info', 'info-circle', 'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'lemon-o', 'level-down', 'level-up', 'life-ring', 'lightbulb-o', 'line-chart', 'location-arrow', 'lock', 'magic', 'magnet', 'male', 'map-marker', 'meh-o', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'money', 'moon-o', 'motorcycle', 'music', 'newspaper-o', 'paint-brush', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 'phone', 'phone-square', 'picture-o', 'pie-chart', 'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 'search', 'search-minus', 'search-plus', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shopping-cart', 'sign-in', 'sign-out', 'signal', 'sitemap', 'sliders', 'smile-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'space-shuttle', 'spinner', 'spoon', 'square', 'square-o', 'star', 'star-half', 'star-half-o', 'star-o', 'street-view', 'suitcase', 'sun-o', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'terminal', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'toggle-off', 'toggle-on', 'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tty', 'umbrella', 'university', 'unlock', 'unlock-alt', 'upload', 'user', 'user-plus', 'user-secret', 'user-times', 'users', 'video-camera', 'volume-down', 'volume-off', 'volume-up', 'wheelchair', 'wifi', 'wrench'),
    'Transportation Icons' => array(
        'ambulance', 'bicycle', 'bus', 'car', 'fighter-jet', 'motorcycle', 'plane', 'rocket', 'ship', 'space-shuttle', 'subway', 'taxi', 'train', 'truck', 'wheelchair' ),
    'Gender Icons' => array(
        'circle-thin', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'mercury', 'neuter', 'transgender', 'transgender-alt', 'venus', 'venus-double', 'venus-mars' ),
    'File Type Icons' => array(
        'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-o', 'file-pdf-o', 'file-powerpoint-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o' ),
    'Spinner Icons' => array(
        'circle-o-notch', 'cog', 'refresh', 'spinner', ),
    'Form Control Icons' => array(
        'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o' ),
    'Payment Icons' => array(
        'cc-amex', 'cc-discover', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'credit-card', 'google-wallet', 'paypal' ),
    'Chart Icons' => array(
        'area-chart', 'bar-chart', 'line-chart', 'pie-chart' ),
    'Currency Icons' => array(
        'btc', 'eur', 'gbp', 'ils', 'inr', 'jpy', 'krw', 'money', 'rub', 'try', 'usd' ),
    'Text Editor Icons' => array(
        'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain-broken', 'clipboard', 'columns', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'repeat', 'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo' ),
    'Directional Icons' => array(
        'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up' ),
    'Video Player Icons' => array(
        'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'play', 'play-circle', 'play-circle-o', 'step-backward', 'step-forward', 'stop', 'youtube-play' ),
    'Brand Icons' => array(
        'adn', 'android', 'angellist', 'apple', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'btc', 'buysellads', 'cc-amex', 'cc-discover', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'codepen', 'connectdevelop', 'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal', 'empire', 'facebook', 'facebook-official', 'facebook-square', 'flickr', 'forumbee', 'foursquare', 'git', 'git-square', 'github', 'github-alt', 'github-square', 'google', 'google-plus', 'google-plus-square', 'google-wallet', 'gratipay', 'hacker-news', 'html5', 'instagram', 'ioxhost', 'joomla', 'jsfiddle', 'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-square', 'linux', 'maxcdn', 'meanpath', 'medium', 'openid', 'pagelines', 'paypal', 'pied-piper', 'pied-piper-alt', 'pinterest', 'pinterest-p', 'pinterest-square', 'qq', 'rebel', 'reddit', 'reddit-square', 'renren', 'sellsy', 'share-alt', 'share-alt-square', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'skype', 'slack', 'slideshare', 'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'tencent-weibo', 'trello', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'viacoin', 'vimeo-square', 'vine', 'vk', 'weibo', 'weixin', 'whatsapp', 'windows', 'wordpress', 'xing', 'xing-square', 'yahoo', 'yelp', 'youtube', 'youtube-play', 'youtube-square' ),
    'Medical Icons' => array(
        'ambulance', 'h-square', 'heart', 'heart-o', 'heartbeat', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair')
);

define ( 'MTS_ICONS', serialize( $_mts_icons ) ); // To use it - $mts_icons = unserialize( MTS_ICONS );

/*-----------------------------------------------------------------------------------*/
/*  Post Thumbnail Support
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) ) { 
    add_theme_support( 'post-thumbnails' );

    add_action( 'init', 'woocart_wp_review_thumb_size', 11 );
    function woocart_wp_review_thumb_size() {
        add_image_size( 'wp_review_large', 284, 180, true ); 
        add_image_size( 'wp_review_small', 64, 64, true );
    }
}

function mts_get_thumbnail_url( $size = 'full' ) {
    global $post;
    if (has_post_thumbnail( $post->ID ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
        return $image[0];
    }
    
    // use first attached image
    $images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
    if (!empty($images)) {
        $image = reset($images);
        $image_data = wp_get_attachment_image_src( $image->ID, $size );
        return $image_data[0];
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) )
        return get_template_directory_uri().'/images/nothumb-'.$size.'.png';
    else
        return '';
}

/*-----------------------------------------------------------------------------------*/
/*  CREATE AND SHOW COLUMN FOR FEATURED IN PORTFOLIO ITEMS LIST ADMIN PAGE
/*-----------------------------------------------------------------------------------*/

//Get Featured image
function mts_get_featured_image($post_ID) {  
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);  
    if ($post_thumbnail_id) {  
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'widgetfull');  
        $image_src= bfi_thumb( $post_thumbnail_img[0], array( 'width' => 300, 'height' => 200, 'crop' => true ) );
        return $image_src;
    }  
} 
function mts_columns_head($defaults) {
    if (get_post_type() == 'post')
        $defaults['featured_image'] = __('Featured Image', 'mythemeshop');
    return $defaults;  
}  
function mts_columns_content($column_name, $post_ID) {  
    if ($column_name == 'featured_image') {  
        $post_featured_image = mts_get_featured_image($post_ID);  
        if ($post_featured_image) {  
            echo '<img width="150" height="100" src="' . esc_attr( $post_featured_image ) . '" />';  
        }  
    }  
} 
add_filter('manage_posts_columns', 'mts_columns_head');  
add_action('manage_posts_custom_column', 'mts_columns_content', 10, 2);

/*-----------------------------------------------------------------------------------*/
/*  Use first attached image as post thumbnail (fallback)
/*-----------------------------------------------------------------------------------*/
//add_filter( 'post_thumbnail_html', 'mts_post_image_html', 10, 5 );
function mts_post_image_html( $html, $post_id, $post_image_id, $size, $attr ) {
    if ( has_post_thumbnail() || get_post_type( $post_id ) != 'post' )
        return $html;
    
    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
    if (!empty($images)) {
        $image = reset($images);
        return wp_get_attachment_image( $image->ID, $size, false, $attr );
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
        $placeholder_src = get_template_directory_uri().'/images/nothumb-'.$size.'.png';
        $placeholder_classs = 'attachment-'.$size.' wp-post-image';
        return '<img src="'.esc_attr( $placeholder_src ).'" class="'.esc_attr( $placeholder_classs ).'" alt="'.esc_attr( get_the_title() ).'">';
    } else {
        return '';
    }
    
}

/*-----------------------------------------------------------------------------------*/
/*  Custom Menu Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'menus' );
if ( function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array(
          'primary-menu' => __( 'Primary Menu', 'mythemeshop' ),
          'secondary-menu' => __( 'Secondary Menu', 'mythemeshop' ),
          'quick-links-menu' => __( 'Quick Links', 'mythemeshop' ),
          'footer-menu' => __( 'Footer Menu', 'mythemeshop' )
         )
     );
}

// Filter wp_nav_menu() to add title to quick links menu
add_filter( 'wp_nav_menu_items', 'mts_menu_home_icon', 10, 2 );
function mts_menu_home_icon( $items, $args ) {

    global $mts_options;

    if ( $args->theme_location === 'quick-links-menu' ) {

        $title_item = '<li class="title-item"><span>'.__( 'Quick Links', 'mythemeshop' ).'  <i class="fa fa-angle-right"></i></span></li>';

        $items = $title_item . $items;
    }

    return $items;
}

/*-----------------------------------------------------------------------------------*/
/*  Post Formats Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'post-formats', array( 'gallery', 'image', 'audio', 'video' ) );

/*-----------------------------------------------------------------------------------*/
/*  Enable Widgetized sidebar and Footer
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'register_sidebar' ) ) {   
    function mts_register_sidebars() {
        $mts_options = get_option( MTS_THEME_NAME );
        
        // Default sidebar
        register_sidebar( array(
            'name' => __('Sidebar','mythemeshop'),
            'description'   => __( 'Default sidebar(Primarily used for Blog).', 'mythemeshop' ),
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        // Top level footer widget areas
        if ( !empty( $mts_options['mts_top_footer'] )) {
            if ( empty( $mts_options['mts_top_footer_num'] )) $mts_options['mts_top_footer_num'] = 3;
            register_sidebars( $mts_options['mts_top_footer_num'], array(
                'name' => __( 'Top Footer %d', 'mythemeshop' ),
                'description'   => __( 'Appears at the top of the footer.', 'mythemeshop' ),
                'id' => 'footer-top',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }
        
        // Custom sidebars
        if ( !empty( $mts_options['mts_custom_sidebars'] ) && is_array( $mts_options['mts_custom_sidebars'] )) {
            foreach( $mts_options['mts_custom_sidebars'] as $sidebar ) {
                if ( !empty( $sidebar['mts_custom_sidebar_id'] ) && !empty( $sidebar['mts_custom_sidebar_id'] ) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-' ) {
                    register_sidebar( array(
                        'name' => ''.$sidebar['mts_custom_sidebar_name'].'',
                        'id' => ''.sanitize_title( strtolower( $sidebar['mts_custom_sidebar_id'] )).'',
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget' => '</div>', 
                        'before_title' => '<h3 class="widget-title">',
                        'after_title' => '</h3>' )
                    );
                }
            }
        }

        if ( mts_isWooCommerce() ) {

            // Register WooCommerce Shop and Single Product Sidebar
            register_sidebar( array(
                'name' => __('Homepage Sidebar', 'mythemeshop'),
                'description'   => __( 'Appears on Shop Homepage.', 'mythemeshop' ),
                'id' => 'home-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );

            register_sidebar( array(
                'name' => __('Shop Page Sidebar', 'mythemeshop'),
                'description'   => __( 'Appears on Shop main page and product archive pages.', 'mythemeshop' ),
                'id' => 'shop-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );

            register_sidebar( array(
                'name' => __('Single Product Sidebar', 'mythemeshop'),
                'description'   => __( 'Appears on single product pages.', 'mythemeshop' ),
                'id' => 'product-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );

            // Homepage featured product categories widget areas
            if ( !empty( $mts_options['mts_featured_product_categories'] ) ) {
                foreach( $mts_options['mts_featured_product_categories'] as $featured_product_cat ) {
                    $product_cat_value = $featured_product_cat['mts_featured_product_category'];
                    $product_cat_array = explode('|', $product_cat_value, 2);
                    $product_cat_id    = $product_cat_array[0];
                    $product_cat_name  = $product_cat_array[1];

                    $ads_enabled = isset( $featured_product_cat['mts_featured_product_category_adds']) ? true : false;

                    if ( $ads_enabled ) {
                        register_sidebar( array(
                            'name' => 'Homepage '.$product_cat_name.' Section Ads',
                            'description'   => __( 'Appears on the homepage, in the featured product category.', 'mythemeshop' ),
                            'id' => 'home-ads-'.$product_cat_id,
                            'before_widget' => '<div id="%1$s" class="widget %2$s">',
                            'after_widget' => '</div>',
                            'before_title' => '<h3>',
                            'after_title' => '</h3>'
                        ) );
                    }
                }
            }

            // Widget areas above product listings 
            register_sidebar( array(
                'name' => __('Shop Page Ads','mythemeshop'),
                'id' => 'shop-ads',
                'description'   => __( 'Appears on the Shop page, above the products listing.', 'mythemeshop' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ) );

            register_sidebar( array(
                'name' => __('Catalog Pages Ads','mythemeshop'),
                'id' => 'catalog-ads',
                'description'   => __( 'Appears on product category archives, above the products listing.', 'mythemeshop' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ) );

            register_sidebar( array(
                'name' => __('Offers Page Ads','mythemeshop'),
                'id' => 'offers-ads',
                'description'   => __( 'Appears on the offers page, above products listing.', 'mythemeshop' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ) );
        }

        if (!empty($mts_options['mts_offers_subscribe'])) {
            register_sidebar( array(
                'name' => __('Offers Page Subscribe Widget','mythemeshop'),
                'id' => 'offers-subscribe',
                'description'   => __( 'Appears on the offers page, below products listing.', 'mythemeshop' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>'
            ) );
        }
    }
    
    add_action( 'widgets_init', 'mts_register_sidebars' );

    function mts_register_extra_sidebars() {
        $mts_options = get_option( MTS_THEME_NAME );
        if (!empty($mts_options['mts_category_ad_widgets_enabled']) && !empty($mts_options['mts_category_ad_widgets'])) {
            foreach( $mts_options['mts_category_ad_widgets'] as $category_id => $v ) {
                $term = get_term($category_id, 'product_cat');
                register_sidebar( array(
                    'name' => $term->name. __(' Catalog Ads','mythemeshop'),
                    'description'   => __( 'Appears on specific product category archive.', 'mythemeshop' ),
                    'id' => 'catalog-ads-'.$category_id,
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3>',
                    'after_title' => '</h3>'
                ) );
            }
        }
    }
    add_action( 'init', 'mts_register_extra_sidebars', 10 ); // can't run this on 'widgets_init' :/
}

function mts_get_excluded_sidebars() {
    $mts_options = get_option( MTS_THEME_NAME );
    $list = array();
    if ( !empty( $mts_options['mts_featured_product_categories'] ) ) {
        foreach( $mts_options['mts_featured_product_categories'] as $featured_product_cat ) {
            $product_cat_value = $featured_product_cat['mts_featured_product_category'];
            $product_cat_array = explode('|', $product_cat_value, 2);
            $product_cat_id    = $product_cat_array[0];
            $product_cat_name  = $product_cat_array[1];

            $ads_enabled = isset( $featured_product_cat['mts_featured_product_category_adds']) ? true : false;

            if ( $ads_enabled ) {
                $list[] = 'home-ads-'.$product_cat_id;
            }
        }
    }
    if (!empty( $mts_options['mts_category_ad_widgets'] )) {
        foreach( $mts_options['mts_category_ad_widgets'] as $product_cat_id => $v ) {
            $list[] = 'catalog-ads-'.$product_cat_id;
        }
    }
    $list[] = 'shop-ads';
    $list[] = 'catalog-ads';
    $list[] = 'offers-ads';
    $list[] = 'offers-subscribe';

    return array_merge($list, array('sidebar', 'footer-top', 'footer-top-2', 'footer-top-3', 'footer-top-4', 'footer-top-5', 'footer-top-6', 'widget-header','shop-sidebar','product-sidebar','home-sidebar'));
}


function mts_custom_sidebar() {
    $mts_options = get_option( MTS_THEME_NAME );
    
    // Default sidebar
    $sidebar = 'sidebar';

    if ( is_page_template('page-blog.php') && !empty( $mts_options['mts_sidebar_for_blog'] )) $sidebar = $mts_options['mts_sidebar_for_blog'];
    if ( is_single() && !empty( $mts_options['mts_sidebar_for_post'] )) $sidebar = $mts_options['mts_sidebar_for_post'];
    if ( is_page() && !empty( $mts_options['mts_sidebar_for_page'] )) $sidebar = $mts_options['mts_sidebar_for_page'];
    
    // Archives
    if ( is_archive() && !empty( $mts_options['mts_sidebar_for_archive'] )) $sidebar = $mts_options['mts_sidebar_for_archive'];
    if ( is_category() && !empty( $mts_options['mts_sidebar_for_category'] )) $sidebar = $mts_options['mts_sidebar_for_category'];
    if ( is_tag() && !empty( $mts_options['mts_sidebar_for_tag'] )) $sidebar = $mts_options['mts_sidebar_for_tag'];
    if ( is_date() && !empty( $mts_options['mts_sidebar_for_date'] )) $sidebar = $mts_options['mts_sidebar_for_date'];
    if ( is_author() && !empty( $mts_options['mts_sidebar_for_author'] )) $sidebar = $mts_options['mts_sidebar_for_author'];
    
    // Other
    if ( is_search() && !empty( $mts_options['mts_sidebar_for_search'] )) $sidebar = $mts_options['mts_sidebar_for_search'];
    if ( is_404() && !empty( $mts_options['mts_sidebar_for_notfound'] )) $sidebar = $mts_options['mts_sidebar_for_notfound'];
    
    // Woo
    if ( mts_isWooCommerce() ) {
        if ( is_shop() || is_product_category() ) {
            if ( !empty( $mts_options['mts_sidebar_for_shop'] )) {
                $sidebar = $mts_options['mts_sidebar_for_shop'];
            } else {
                $sidebar = 'shop-sidebar'; // default
            }
        }
        if ( is_product() ) {
            if ( !empty( $mts_options['mts_sidebar_for_product'] ))
                $sidebar = $mts_options['mts_sidebar_for_product'];
            else
                $sidebar = 'product-sidebar'; // default
        }
        if ( is_home() ) {
            if ( !empty( $mts_options['mts_sidebar_for_home'] ))
                $sidebar = $mts_options['mts_sidebar_for_home'];
            else
                $sidebar = 'home-sidebar'; // default
        }
    }

    global $wp_registered_sidebars;
    // Page/post specific custom sidebar
    if ( is_page() || is_single() ) {
        wp_reset_postdata();
        global $post;
        $custom = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
        if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) $sidebar = $custom;
    } elseif ( function_exists('is_shop') && is_shop() ) {
        $custom = get_post_meta( woocommerce_get_page_id('shop'), '_mts_custom_sidebar', true );
        if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) $sidebar = $custom;
    }

    return $sidebar;
}

/*---------------------------------------------------------------------------------------------------*/
/*  Add "no-widget-title" or "has-widget-title" class to widget ( used for styling widgets )
/*---------------------------------------------------------------------------------------------------*/

function mts_widget_title( $params ) {
    
    global $wp_registered_widgets;
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[ $widget_id ];
    $widget_opt = get_option( $widget_obj['callback'][0]->option_name );
    $widget_num = $widget_obj['params'][0]['number'];

    $title_class = ( !isset( $widget_opt[ $widget_num ]['title'] ) || empty( $widget_opt[ $widget_num ]['title'] ) ) ? 'no-widget-title' : 'has-widget-title';

    $params[0]['before_widget'] = preg_replace( '/class="/', "class=\"{$title_class} ", $params[0]['before_widget'], 1 );

    return $params;
}
add_filter( 'dynamic_sidebar_params', 'mts_widget_title' );

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets, Actions and Libraries
/*-----------------------------------------------------------------------------------*/

// BFI Thumb Resize
include_once( "functions/bfi-thumb.php" );

// Add the Ad Block Custom Widget
include_once( "functions/widget-ad.php" );

// Add the 125x125 Ad Block Custom Widget
include_once( "functions/widget-ad125.php" );

// Add the 300x250 Ad Block Custom Widget
include_once( "functions/widget-ad256.php" );

// Add the Latest Tweets Custom Widget
include_once( "functions/widget-tweets.php" );

// Add Recent Posts Widget
include_once( "functions/widget-recentposts.php" );

// Add Related Posts Widget
include_once( "functions/widget-relatedposts.php" );

// Add Author Posts Widget
include_once( "functions/widget-authorposts.php" );

// Add Popular Posts Widget
include_once( "functions/widget-popular.php" );

// Add Facebook Like box Widget
include_once( "functions/widget-fblikebox.php" );

// Add Social Profile Widget
include_once( "functions/widget-social.php" );

// Add Category Posts Widget
include_once( "functions/widget-catposts.php" );

// Add Category Posts Widget
include_once( "functions/widget-postslider.php" );

// Add Accordion Widget
include( "functions/widget-accordion.php" );

if ( mts_isWooCommerce() ) {
    // Add Product Slider Widget
    include_once( "functions/widget-productslider.php" );

    // Add Ajax filter widget
    include_once( "functions/widget-ajax-layered-nav.php" );
}

// Add Welcome message
include_once( "functions/welcome-message.php" );

// Template Functions
include_once( "functions/theme-actions.php" );

// Post/page editor meta boxes
include_once( "functions/metaboxes.php" );

// TGM Plugin Activation
include_once( "functions/plugin-activation.php" );

// AJAX Contact Form - mts_contact_form()
include_once( 'functions/contact-form.php' );

// Custom menu walker
include_once( 'functions/nav-menu.php' );

/*-----------------------------------------------------------------------------------*/
/*  Javascript
/*-----------------------------------------------------------------------------------*/
function mts_nojs_js_class() {
    echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,\'js\' );</script>';
}
add_action( 'wp_head', 'mts_nojs_js_class', 1 );

function mts_add_scripts() {
    $mts_options = get_option( MTS_THEME_NAME );

    wp_enqueue_script( 'jquery' );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    wp_register_script( 'customscript', get_template_directory_uri() . '/js/customscript.js', true );
    if ( ! empty( $mts_options['mts_show_primary_nav'] ) && ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
        $nav_menu = 'both';
    } else {
        if ( ! empty( $mts_options['mts_show_primary_nav'] ) ) {
            $nav_menu = 'primary';
        } elseif ( ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
            $nav_menu = 'secondary';
        } else {
            $nav_menu = 'none';
        }
    }
    wp_localize_script(
        'customscript',
        'mts_customscript',
        array(
            'responsive' => ( empty( $mts_options['mts_responsive'] ) ? false : true ),
            'nav_menu' => $nav_menu
         )
    );
    wp_enqueue_script( 'customscript' );
    
    // Slider
    wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), null, true);
    wp_enqueue_script ('owl-carousel');

    // Ajax nav for widget
    wp_register_script( 'wc-ajax-attr-filters', get_template_directory_uri() . '/js/wc-ajax-attr-filters.js' );
    
    // Parallax pages and posts
    if (is_singular()) {
        if ( basename( mts_get_post_template() ) == 'singlepost-parallax.php' || basename( get_page_template() ) == 'page-parallax.php' ) {
            wp_register_script ( 'jquery-parallax', get_template_directory_uri() . '/js/parallax.js' );
            wp_enqueue_script ( 'jquery-parallax' );
        }
    }

    global $is_IE;
    if ( $is_IE ) {
        wp_register_script ( 'html5shim', "http://html5shim.googlecode.com/svn/trunk/html5.js" );
        wp_enqueue_script ( 'html5shim' );
    }
    
}
add_action( 'wp_enqueue_scripts', 'mts_add_scripts' );
   
function mts_load_footer_scripts() {  
    $mts_options = get_option( MTS_THEME_NAME );
    
    //Lightbox
    wp_register_script( 'prettyPhoto', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', true );
    if ( ! empty( $mts_options['mts_lightbox'] ) ) {
        if ( mts_isWooCommerce() ) {
            // Single product gallery slider lightbox is enabled or disabled inside woocommerce settings pages
            if ( ! is_product() ) {
                if ( ! empty( $mts_options['mts_lightbox'] ) ) { wp_enqueue_script( 'prettyPhoto' ); }
            }
        } else {
            if ( ! empty( $mts_options['mts_lightbox'] ) ) { wp_enqueue_script( 'prettyPhoto' ); }
        }
    }
    
    //Sticky Nav / Floating social buttons
    if ( ! empty( $mts_options['mts_sticky_nav'] ) || ( is_single() && isset( $mts_options['mts_social_button_position'] ) && $mts_options['mts_social_button_position'] == 'floating' ) ) {
        wp_register_script( 'StickyNav', get_template_directory_uri() . '/js/sticky.js', true );
        wp_enqueue_script( 'StickyNav' );
    }
    
    // Ajax Load More and Search Results
    wp_register_script( 'mts_ajax', get_template_directory_uri() . '/js/ajax.js', true );
    if ( ( ((is_archive() && !is_home() && function_exists('is_woocommerce') && !is_woocommerce()) || is_page_template('page-blog.php')) && ! empty( $mts_options['mts_pagenavigation_type'] ) && $mts_options['mts_pagenavigation_type'] >= 2 ) ||
     ( function_exists('is_woocommerce') && is_woocommerce() && !is_home() && !empty( $mts_options['mts_shop_pagenavigation_type'] ) && $mts_options['mts_shop_pagenavigation_type'] >= 1 ) ) {
        wp_enqueue_script( 'mts_ajax' );
        
        wp_register_script( 'historyjs', get_template_directory_uri() . '/js/history.js', true );
        wp_enqueue_script( 'historyjs' );
        
        // Add parameters for the JS
        global $latest_posts;
        global $wp_query;
        $max = (! empty($latest_posts->max_num_pages) ? $latest_posts->max_num_pages : $wp_query->max_num_pages);
        if ($max == 0 && is_page_template('page-blog.php') ) {
            if (get_query_var('page') > 1) {
                $paged = get_query_var('page');
            } elseif (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } else {
                $paged = 1;
            }
            $new_query = new WP_Query(array('paged' => $paged, 'post_type' => 'post'));
            $max = $new_query->max_num_pages;
        }
        $paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
        $autoload = ( (is_woocommerce() && !is_home() && $mts_options['mts_shop_pagenavigation_type'] == 2 ) || ( ( (is_archive() && !is_home() && !is_woocommerce() ) || is_page_template('page-blog.php')) && $mts_options['mts_pagenavigation_type'] == 3 ) );
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_loadposts',
            array(
                'startPage' => $paged,
                'maxPages' => $max,
                'nextLink' => next_posts( $max, false ),
                'autoLoad' => $autoload,
                'is_cart' => function_exists('is_cart') && is_cart(),
                'is_shop' => function_exists('is_woocommerce') && is_woocommerce(),
                'i18n_loadmore' => __( 'Load More', 'mythemeshop' ),
                'i18n_loading' => __('Loading More ...', 'mythemeshop'),
                'i18n_nomore' => __( 'Last page reached.', 'mythemeshop' )
             )
        );
    }
    if ( ! empty( $mts_options['mts_ajax_search'] ) ) {
        wp_enqueue_script( 'mts_ajax' );
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_search',
            array(
                'url' => admin_url( 'admin-ajax.php' ),
                'ajax_search' => '1'
             )
        );
    }

    if ( is_page_template('page-offers.php') && $mts_options['mts_offers_page_filter'] == '1' ) {
        wp_enqueue_script('mts_ajax');
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_offers',
            array(
                'mts_nonce' => wp_create_nonce( 'mts_nonce' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }

    if ( is_home() ) {
        wp_enqueue_script('mts_ajax');
        wp_localize_script(
            'mts_ajax',
            'mts_ajax_tabs',
            array(
                //'mts_nonce' => wp_create_nonce( 'mts_nonce' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
    
}  
add_action( 'wp_footer', 'mts_load_footer_scripts' );  

if( !empty( $mts_options['mts_ajax_search'] )) {
    add_action( 'wp_ajax_mts_search', 'ajax_mts_search' );
    add_action( 'wp_ajax_nopriv_mts_search', 'ajax_mts_search' );
}

/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/
function mts_enqueue_css() {
    $mts_options = get_option( MTS_THEME_NAME );

    wp_enqueue_style( 'stylesheet', get_stylesheet_directory_uri() . '/style.css', 'style' );
    
    // Slider
    // also enqueued in slider widget
    wp_register_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), null);
    wp_enqueue_style('owl-carousel');
    
    // WooCommerce
    if ( mts_isWooCommerce() ) {
        wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
    }

    // Lightbox
    if ( ! empty( $mts_options['mts_lightbox'] ) ) {
        wp_register_style( 'prettyPhoto', get_template_directory_uri() . '/css/prettyPhoto.css', 'style' );
        wp_enqueue_style( 'prettyPhoto' );
    }
    
    //Font Awesome
    wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', 'style' );
    wp_enqueue_style( 'fontawesome' );
    
    //Responsive
    if ( ! empty( $mts_options['mts_responsive'] ) ) {
        wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css', 'style' );
    }
    
    $mts_bg = '';
    if ( $mts_options['mts_bg_pattern_upload'] != '' ) {
        $mts_bg = $mts_options['mts_bg_pattern_upload'];
    } else {
        if( !empty( $mts_options['mts_bg_pattern'] )) {
            $mts_bg = get_template_directory_uri().'/images/'.$mts_options['mts_bg_pattern'].'.png';
        }
    }

    $mts_header_bg = '';
    if ($mts_options['mts_header_bg_pattern_upload'] != '') {
        $mts_header_bg = $mts_options['mts_header_bg_pattern_upload'];
    } else {
        if($mts_options['mts_header_bg_pattern'] != '') {
            $mts_header_bg = get_template_directory_uri().'/images/'.$mts_options['mts_header_bg_pattern'].'.png';
        }
    }

    $mts_footer_bg = '';
    if ($mts_options['mts_footer_bg_pattern_upload'] != '') {
        $mts_footer_bg = $mts_options['mts_footer_bg_pattern_upload'];
    } else {
        if($mts_options['mts_footer_bg_pattern'] != '') {
            $mts_footer_bg = get_template_directory_uri().'/images/'.$mts_options['mts_footer_bg_pattern'].'.png';
        }
    }
    $mts_sclayout = '';
    $mts_shareit_float = '';
    $mts_shareit_left = '';
    $mts_shareit_right = '';
    $mts_author = '';
    $mts_header_section = '';
    $is_breadcrumb_enabled = '';
    if ( is_page() || is_single() ) {
        $mts_sidebar_location = get_post_meta( get_the_ID(), '_mts_sidebar_location', true );
    } elseif (function_exists('is_shop') && is_shop() || function_exists('is_product_category') && is_product_category()) {
        $mts_sidebar_location = get_post_meta( woocommerce_get_page_id('shop'), '_mts_sidebar_location', true );
    } elseif ( is_home() && isset( $mts_options['mts_sidebar_for_home_side'] ) && $mts_options['mts_sidebar_for_home_side'] == '1' ) {
        $mts_sidebar_location = 'right';
    } else {
        $mts_sidebar_location = '';
    }
    if ( $mts_sidebar_location != 'right' && ( $mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left' ) ) {
        $mts_sclayout = '.article { float: right;}
        .sidebar.c-4-12 { float: left; padding-right: 0; }';
    }
    if ( empty( $mts_options['mts_header_section2'] ) ) {
        $mts_header_section = '.logo-wrap { display: none; }
        #navigation { border-top: 0; }
        #header { min-height: 47px; }';
    }
    if ( empty( $mts_options['mts_offers_page_badge'] ) ) {
        $mts_header_section .= '.offers-header-right-inner { margin-left: 0; }';
    }
    if ( isset( $mts_options['mts_social_button_position'] ) && $mts_options['mts_social_button_position'] == 'floating' ) {
        $mts_shareit_left = '.shareit { top: 0; z-index: 100; margin: 0.5em 0 0; width: 90px; position: absolute; overflow: hidden; padding: 0; border:none; border-right: 0;}
        .share-item {margin-bottom: 5px;}
        .post-single-content { padding-left: 90px }';
    }
    if ( ! empty( $mts_options['mts_author_comment'] ) ) {
        $mts_author = '.bypostauthor .fn > span:after { content: "'.__( 'Author', 'mythemeshop' ).'"; margin-left: 10px; padding: 1px 8px; background: '.$mts_options["mts_color_scheme"].'; color: #FFF; -webkit-border-radius: 2px; border-radius: 2px; }';
    }
    if (empty($mts_options['mts_breadcrumb'])) {
        $is_breadcrumb_enabled = '.content-wrap .container #page { margin-top: 30px;}';
    }
    $lightcolor = mts_lighten_color($mts_options['mts_color_scheme3'], $amount = 15);
    $custom_css = "
        body {background-color:{$mts_options['mts_bg_color']}; background-image: url( {$mts_bg} );}
        #header {background-color:{$mts_options['mts_header_bg_color']}; background-image: url({$mts_header_bg});}
        .main-footer {background-color:{$mts_options['mts_footer_bg_color']}; background-image: url({$mts_footer_bg});}
        .secondary-navigation, #mobile-menu-wrapper, #navigation ul ul {background-color:{$mts_options['mts_nav_color']};}

        a:hover, .mark-links a, .textwidget a, .button, input[type='submit'], .tweets a, .comment-meta a, .reply a,.header-search .sbutton, .woocommerce div.product .stock, .woocommerce #content div.product .stock, ul.products li.product.product-home a:hover h3, .toggle-menu-current-item > a, .offers-heading strong { color: {$mts_options['mts_color_scheme']};}
        .woocommerce div.product div.summary a.compare:hover, .woocommerce ul.products li.product .button:hover, .woocommerce ul.products li.product .compare:hover, .woocommerce ul.products li.product:hover .product-title { color: {$mts_options['mts_color_scheme']}!important; }
        .button:hover, input[type='submit']:hover, mark, .format-icon, .toggle-menu .toggle-caret:hover .fa, .social-profile-icons ul li a:hover, .tagcloud a:hover, .mts-subscribe input[type='submit']:hover, .pagination a:hover, .pagination > .current > .currenttext, .pagination ul > .current > .currenttext, .woocommerce-pagination .current, #offers-subscribe .button, p.demo_store,.woocommerce span.new-badge, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce .widget_price_filter .price_slider_amount .button, .mts-cart-button-wrap.cart-content-visible > div, .latestPost-review-wrapper { background-color: {$mts_options['mts_color_scheme']}; color: #fff;}
        .mts-woocart-subscribe .widget #wp-subscribe input.submit, .owl-prev:hover, .owl-next:hover, .owl-controls .owl-dot.active span, .owl-controls .owl-dot:hover span { background-color: {$mts_options['mts_color_scheme']}!important; }
        .offers-badge { border-color: {$mts_options['mts_color_scheme']};}

        #logo a, .offers-filter-menu-item a:hover, .offers-filter-menu-item a.current, .offers-filter-menu-item:first-child:hover:before, .header-inner a:hover, #blog #navigation .menu .wpmm-megamenu-showing a, .slider-nav-item:hover .slidertitle, .slider-nav-item.active .slidertitle { color: {$mts_options['mts_color_scheme2']};}
        #navigation ul li a:hover, .secondary-navigation .current-menu-item a { color: {$mts_options['mts_color_scheme2']}!important;}
        .header-search .sbutton, .pace .pace-progress, .offers-badge, .woocommerce span.onsale, .mts-cart-content-footer a.button.mts-cart-button { background-color: {$mts_options['mts_color_scheme2']}!important}
        .woocommerce ul.products li.product:hover{ border-color: {$mts_options['mts_color_scheme2']}!important}

        .featured-section-header, #slider-nav { background-color: {$mts_options['mts_color_scheme3']}}
        .slider-nav-item.active:after { border-color: #{$lightcolor} rgba(0, 0, 0, 0)}
        .slider-nav-item:hover, .slider-nav-item.active { background-color: #{$lightcolor}}
        .subcategory-item { color: #{$lightcolor}; }
        {$mts_sclayout}
        {$mts_shareit_float}
        {$mts_shareit_left}
        {$mts_shareit_right}
        {$mts_author}
        {$mts_header_section}
        {$is_breadcrumb_enabled}
        {$mts_options['mts_custom_css']}
            ";

    $inline_style_target = 'stylesheet';
    if ( wp_style_is( 'woocommerce', 'enqueued' ) ) { $inline_style_target = 'woocommerce'; }

    wp_add_inline_style( $inline_style_target, $custom_css );
}
add_action( 'wp_enqueue_scripts', 'mts_enqueue_css', 99 );

/*-----------------------------------------------------------------------------------*/
/*  Wrap videos in .responsive-video div
/*-----------------------------------------------------------------------------------*/
function mts_responsive_video( $data ) {
    return '<div class="flex-video">' . $data . '</div>';
}
//add_filter( 'embed_oembed_html', 'mts_responsive_video' );

/*-----------------------------------------------------------------------------------*/
/*  Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'the_content_rss', 'do_shortcode' );

/*-----------------------------------------------------------------------------------*/
/*  Custom Comments template
/*-----------------------------------------------------------------------------------*/
function mts_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment; 
    $mts_options = get_option( MTS_THEME_NAME ); ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
            <div class="comment-author vcard">
                <?php echo get_avatar( $comment->comment_author_email, 64 ); ?>
                <?php printf( '<span class="fn" itemprop="creator" itemscope itemtype="http://schema.org/Person"><i class="fa fa-user"></i><span itemprop="name">%s</span></span>', get_comment_author_link() ) ?> 
                <?php if ( ! empty( $mts_options['mts_comment_date'] ) ) { ?>
                    <span class="ago"><i class="fa fa-clock-o"></i><?php comment_date( get_option( 'date_format' ) ); ?></span>
                <?php } ?>
                <span class="comment-meta">
                    <?php edit_comment_link( __( '( Edit )', 'mythemeshop' ), '  ', '' ) ?>
                </span>
                <span class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] )) ) ?>
                </span>
            </div>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <em><?php _e( 'Your comment is awaiting moderation.', 'mythemeshop' ) ?></em>
                <br />
            <?php endif; ?>
            <div class="commentmetadata">
                <div class="commenttext mark-links" itemprop="commentText">
                    <?php comment_text() ?>
                </div>
            </div>
        </div>
    </li>
<?php }

/*-----------------------------------------------------------------------------------*/
/*  Excerpt
/*-----------------------------------------------------------------------------------*/

// Increase max length
function mts_excerpt_length( $length ) {
    return 100;
}
add_filter( 'excerpt_length', 'mts_excerpt_length', 20 );

// Remove [...] and shortcodes
function mts_custom_excerpt( $output ) {
  return preg_replace( '/\[[^\]]*]/', '', $output );
}
add_filter( 'get_the_excerpt', 'mts_custom_excerpt' );

// Truncate string to x letters/words
function mts_truncate( $str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;' ) {
    if ( $units == 'letters' ) {
        if ( mb_strlen( $str ) > $length ) {
            return mb_substr( $str, 0, $length ) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        $words = explode( ' ', $str );
        if ( count( $words ) > $length ) {
            return implode( " ", array_slice( $words, 0, $length ) ) . $ellipsis;
        } else {
            return $str;
        }
    }
}

if ( ! function_exists( 'mts_excerpt' ) ) {
    function mts_excerpt( $limit = 40 ) {
        return esc_html( mts_truncate( get_the_excerpt(), $limit, 'words' ) );
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Remove more link from the_content and use custom read more
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_content_more_link', 'mts_remove_more_link', 10, 2 );
function mts_remove_more_link( $more_link, $more_link_text ) {
    return '';
}
// shorthand function to check for more tag in post
function mts_post_has_moretag() {
    global $post;
    return strpos( $post->post_content, '<!--more-->' );
}

if ( ! function_exists( 'mts_readmore' ) ) {
    function mts_readmore() {
        ?>
        <div class="readMore">
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="button">
                <?php _e( 'Continue Reading', 'mythemeshop' ); ?>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>
            </a>
        </div>
        <?php 
    }
}

/*-----------------------------------------------------------------------------------*/
/* nofollow to next/previous links
/*-----------------------------------------------------------------------------------*/
function mts_pagination_add_nofollow( $content ) {
    return 'rel="nofollow"';
}
add_filter( 'next_posts_link_attributes', 'mts_pagination_add_nofollow' );
add_filter( 'previous_posts_link_attributes', 'mts_pagination_add_nofollow' );

/*-----------------------------------------------------------------------------------*/
/* Nofollow to category links
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_category', 'mts_add_nofollow_cat' ); 
function mts_add_nofollow_cat( $text ) {
    $text = str_replace( 'rel="category tag"', 'rel="nofollow"', $text ); return $text;
}

/*-----------------------------------------------------------------------------------*/ 
/* nofollow post author link
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_author_posts_link', 'mts_nofollow_the_author_posts_link' );
function mts_nofollow_the_author_posts_link ( $link ) {
    return str_replace( '<a href=', '<a rel="nofollow" href=', $link ); 
}

/*-----------------------------------------------------------------------------------*/ 
/* nofollow to reply links
/*-----------------------------------------------------------------------------------*/
function mts_add_nofollow_to_reply_link( $link ) {
    return str_replace( '" )\'>', '" )\' rel=\'nofollow\'>', $link );
}
add_filter( 'comment_reply_link', 'mts_add_nofollow_to_reply_link' );

/*-----------------------------------------------------------------------------------*/
/* removes the WordPress version from your header for security
/*-----------------------------------------------------------------------------------*/
function mts_remove_wpversion() {
    return '<!--Theme by MyThemeShop.com-->';
}
add_filter( 'the_generator', 'mts_remove_wpversion' );
    
/*-----------------------------------------------------------------------------------*/
/* Removes Trackbacks from the comment count
/*-----------------------------------------------------------------------------------*/
add_filter( 'get_comments_number', 'mts_comment_count', 0 );
function mts_comment_count( $count ) {
    if ( ! is_admin() ) {
        global $id;
        $comments = get_comments( 'status=approve&post_id=' . $id );
        $comments_by_type = separate_comments( $comments );
        return count( $comments_by_type['comment'] );
    } else {
        return $count;
    }
}

/*-----------------------------------------------------------------------------------*/
/* adds a class to the post if there is a thumbnail
/*-----------------------------------------------------------------------------------*/
function has_thumb_class( $classes ) {
    global $post;
    if( has_post_thumbnail( $post->ID ) ) { $classes[] = 'has_thumb'; }
        return $classes;
}
add_filter( 'post_class', 'has_thumb_class' );

/*-----------------------------------------------------------------------------------*/ 
/* AJAX Search results
/*-----------------------------------------------------------------------------------*/
function ajax_mts_search() {
    $query = $_REQUEST['q']; // It goes through esc_sql() in WP_Query
    $type = $_GET['search_post_type'];
    $search_query = new WP_Query( array( 's' => $query, 'posts_per_page' => 4, 'post_type'=> $type )); 
    $search_count = new WP_Query( array( 's' => $query, 'posts_per_page' => -1, 'post_type'=> $type ));
    $search_count = $search_count->post_count;
    if ( !empty( $query ) && $search_query->have_posts() ) : 
        //echo '<h5>Results for: '. $query.'</h5>';
        echo '<ul class="ajax-search-results">';
        while ( $search_query->have_posts() ) : $search_query->the_post();
            ?><li>
                <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="post-title" rel="nofollow">
                    <?php if ( has_post_thumbnail() ) {
                        $image_id = get_post_thumbnail_id();
                        $image_array = wp_get_attachment_image_src( $image_id, 'full' );
                        $image_url = $image_array[0];
                        $thumbnail = bfi_thumb( $image_url, array( 'width' => '244', 'height' => '190', 'crop' => true ) );
                        echo '<img src="'.esc_attr($thumbnail).'" class="wp-post-image">'; ?>
                    <?php } ?>
                </a>
                <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="post-title"><?php the_title(); ?></a>
            </li>   
            <?php
        endwhile;
        echo '</ul>';
        echo '<div class="ajax-search-meta"><span class="results-count">'.$search_count.' '.__( 'Results', 'mythemeshop' ).'</span><a href="'.esc_url( add_query_arg( array( 's' => $query, 'post_type' => $type ), home_url('/') ) ).'" class="results-link">Show all results</a></div>';
    else:
        echo '<div class="no-results">'.__( 'No results found.', 'mythemeshop' ).'</div>';
    endif;
    wp_reset_postdata();
    exit; // required for AJAX in WP
}

/*-----------------------------------------------------------------------------------*/
/* Redirect feed to feedburner
/*-----------------------------------------------------------------------------------*/

if ( $mts_options['mts_feedburner'] != '' ) {
function mts_rss_feed_redirect() {
    $mts_options = get_option( MTS_THEME_NAME );
    global $feed;
    $new_feed = $mts_options['mts_feedburner'];
    if ( !is_feed() ) {
            return;
    }
    if ( preg_match( '/feedburner/i', $_SERVER['HTTP_USER_AGENT'] )){
            return;
    }
    if ( $feed != 'comments-rss2' ) {
            if ( function_exists( 'status_header' )) status_header( 302 );
            header( "Location:" . $new_feed );
            header( "HTTP/1.1 302 Temporary Redirect" );
            exit();
    }
}
add_action( 'template_redirect', 'mts_rss_feed_redirect' );
}

/*-----------------------------------------------------------------------------------*/
/* Single Post Pagination - Numbers + Previous/Next
/*-----------------------------------------------------------------------------------*/
function mts_wp_link_pages_args( $args ) {
    global $page, $numpages, $more, $pagenow;
    if ( !$args['next_or_number'] == 'next_and_number' )
        return $args; 
    $args['next_or_number'] = 'number'; 
    if ( !$more )
        return $args; 
    if( $page-1 ) 
        $args['before'] .= _wp_link_page( $page-1 )
        . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
    ;
    if ( $page<$numpages ) 
    
        $args['after'] = _wp_link_page( $page+1 )
        . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
        . $args['after']
    ;
    return $args;
}
add_filter( 'wp_link_pages_args', 'mts_wp_link_pages_args' );

/*-----------------------------------------------------------------------------------*/
/* WooCommerce
/*-----------------------------------------------------------------------------------*/
if ( mts_isWooCommerce() ) {

    add_theme_support( 'woocommerce' );

    // Disable default woocommerce styles
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );
    
    // Change number or products per row to 4
    add_filter( 'loop_shop_columns', 'loop_columns' );
    if ( !function_exists( 'loop_columns' )) {
        function loop_columns() {
            return 4; // 4 products per row
        }
    }
    
    // Redefine woocommerce_output_related_products()
    function woocommerce_output_related_products() {
        $args = array(
            'posts_per_page' => 4,
            'columns' => 1,
        );
        woocommerce_related_products($args); // Display 4 products in rows of 1
    }
    
    /*** Hook in on activation */
    global $pagenow;
    if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'mythemeshop_woocommerce_image_dimensions', 1 );
     
    /*** Define image sizes */
    function mythemeshop_woocommerce_image_dimensions() {
        $catalog = array(
            'width'     => '166',   // px
            'height'    => '192',   // px
            'crop'      => 1        // true
         );
        $single = array(
            'width'     => '315',   // px
            'height'    => '440',   // px
            'crop'      => 0        // false
        );
        $thumbnail = array(
            'width'     => '64',    // px
            'height'    => '64',    // px
            'crop'      => 1        // true
         ); 
        // Image sizes
        update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
        update_option( 'shop_single_image_size', $single );         // Single product image
        update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
    }
    
    //add_filter( 'woocommerce_product_thumbnails_columns', 'mts_thumb_cols' );
    function mts_thumb_cols() {
     return 4; // .last class applied to every 4th thumbnail
    }
    
    add_filter( 'loop_shop_per_page', 'mts_products_per_page', 20 );
    function mts_products_per_page() {
        $mts_options = get_option( MTS_THEME_NAME );
        return $mts_options['mts_shop_products'];
    }

    // Add woocomerce class to body on homepage
    add_filter( 'body_class', 'woocart_body_classes' );
    function woocart_body_classes( $classes ) {

        $mts_options = get_option( MTS_THEME_NAME );

        if ( is_home() ) {
            $classes[] = 'woocommerce';
            $classes[] = 'woocommerce-boxes-layout';
        }

        if ( is_product() || is_checkout() || is_cart() || is_account_page() || is_page_template('page-offers.php') ) {
            $classes[] = 'woocommerce-layout';
        }

        if ( is_shop() || is_product_taxonomy() ) {
            $classes[] = 'woocommerce-boxes-layout';
        }

        if ( ! ( is_woocommerce() || is_checkout() || is_cart() || is_account_page() || is_page_template('page-offers.php') ) ) {
            $classes[] = 'blog-layout';
        }

        return $classes;
    }
    
    // Ensure cart contents update when products are added to the cart via AJAX
    add_filter( 'add_to_cart_fragments', 'mts_header_add_to_cart_fragment' );
    function mts_header_add_to_cart_fragment( $fragments ) {

        global $woocommerce;

        ob_start();

        $cart_contents_count = $woocommerce->cart->cart_contents_count;
        
        mts_cart_button();

        $fragments['.cart-content-wrapper'] = ob_get_clean();

        return $fragments;
    }

    // Remove WooCommerce breadcrumb from default place, it's called within mts_the_breadcrumb() and placed in the header.php
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

    // Customize WooCommerce breadcrumb defaults
    add_filter('woocommerce_breadcrumb_defaults', 'mts_wc_breadcrumb_defaults');
    function mts_wc_breadcrumb_defaults() {
        $defaults = array(
            'delimiter'   => '<span class="delimiter fa fa-angle-right"></span>',
            'wrap_before' => '',
            'wrap_after'  => '',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'mythemeshop' ),
        );

        return $defaults;
    }

    // Replace shop placeholder image
    add_action( 'init', 'mts_custom_wc_placeholder' );
    function mts_custom_wc_placeholder() {
      add_filter('woocommerce_placeholder_img_src', 'mts_woocommerce_placeholder_img_src');
       
        function mts_woocommerce_placeholder_img_src() {
             
            return get_template_directory_uri().'/images/nothumb-shop.png';
        }
    }

    // Change add to cart text on single products
    add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text', 10, 2 );
    function woo_custom_cart_button_text($text, $product) {
        if ( 'external' === $product->product_type ) return $text;
        return __( '<i class="fa fa-shopping-cart"></i> Add to Cart', 'mythemeshop' );
    }

    // Add product category image to products archives
    add_action( 'woocommerce_before_main_content', 'mts_product_category_ads', 2 );
    function mts_product_category_ads() {
        if ( is_product_category() ) {
            global $wp_query; 
            $term = $wp_query->get_queried_object();
            if ( is_active_sidebar('catalog-ads-'.$term->term_id) ) { ?>
                <div class="mts-ad-widgets catalog-ad-widgets clearfix">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'catalog-ads-'.$term->term_id ) ) : ?><?php endif; ?>
                </div>
            <?php } elseif ( is_active_sidebar('catalog-ads') ) { ?>
                <div class="mts-ad-widgets catalog-ad-widgets clearfix">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'catalog-ads' ) ) : ?><?php endif; ?>
                </div>
            <?php }
        } else if ( is_shop() ) {
            if ( is_active_sidebar('shop-ads') ) { ?>
                <div class="mts-ad-widgets shop-ad-widgets clearfix">
                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( 'shop-ads' ) ) : ?><?php endif; ?>
                </div>
            <?php }
        }
    }

    // Display the "new" badge
    if ( $mts_options['mts_mark_new_products'] ) {
        add_action( 'woocommerce_before_shop_loop_item_title', 'mts_show_product_loop_new_badge', 30 );
    }
    function mts_show_product_loop_new_badge() {

        $mts_options   = get_option( MTS_THEME_NAME );
        $postdate      = get_the_time( 'Y-m-d' );
        $postdatestamp = strtotime( $postdate );
        $newness       = isset( $mts_options['mts_new_products_time'] ) ? $mts_options['mts_new_products_time'] : 30;

        // If the product was published within the newness time frame display the new badge
        if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {

            echo '<span class="new-badge">' . __( 'New!', 'mythemeshop' ) . '</span>';
        }
    }

    // Featured Products
    // before single page footer
    add_action( 'mts_before_single_page_footer', 'mts_page_featured_products', 10 );
    function mts_page_featured_products() {

        $mts_options = get_option( MTS_THEME_NAME );
        $show_featured_products = false;

        if ( $mts_options['mts_featured_products'] == '1' ) {

            if ( is_order_received_page() && isset( $mts_options['mts_featured_products_locations']['thankyou'] ) ) { // order completed

                $show_featured_products = true;

            } elseif ( !is_order_received_page() && is_checkout() && isset( $mts_options['mts_featured_products_locations']['checkout'] ) ) { // checkout
 
                $show_featured_products = true;

            } elseif ( is_cart() && isset( $mts_options['mts_featured_products_locations']['cart'] ) ) { // cart

                $show_featured_products = true;
            }
        }

        if ( $show_featured_products ) {

            wc_get_template( 'featured-products.php' );
        }
    }
    // before single post footer
    add_action( 'mts_before_single_post_footer', 'mts_post_featured_products', 10 );
    function mts_post_featured_products() {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( $mts_options['mts_featured_products'] == '1' && isset( $mts_options['mts_featured_products_locations']['post'] ) ) {
            wc_get_template( 'featured-products.php' );
        }
    }
    // before single product footer
    add_action( 'mts_before_product_footer', 'mts_product_featured_products', 20 );
    function mts_product_featured_products() {
        $mts_options = get_option( MTS_THEME_NAME );
        if ( $mts_options['mts_featured_products'] == '1' && isset( $mts_options['mts_featured_products_locations']['product'] ) ) {
            wc_get_template( 'featured-products.php' );
        }
    }
    // Disable product tumbnails
    remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
    // Disable headings in product tabs
    add_filter( 'woocommerce_product_description_heading', '__return_false' );
    add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );

    add_action( 'woocommerce_single_product_summary', 'mts_wc_single_product_bottom_divider', 33 );
    function mts_wc_single_product_bottom_divider() {
        echo '<hr />';
    }

    //add_action( 'woocommerce_single_product_summary', 'mts_wc_single_product_wishlist', 34 );
    function mts_wc_single_product_wishlist() {
        echo mts_wishlist_button();
    }

    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 31 );

    // Share
    add_action( 'woocommerce_share', 'single_product_share', 10 );
    function single_product_share() {
        //printf( '<a href="#" class="share-link"><i class="fa fa-group"></i>%s</a>', __( 'Share', 'mythemeshop' ) );
        mts_social_buttons('product');
    }

    /*-----------------------------------------------------------------------------------*/
    /* Add icon select option to product category settings
    /*-----------------------------------------------------------------------------------*/
    add_action( 'product_cat_add_form_fields', 'mts_product_cat_tax_add_form_fields' );
    add_action( 'product_cat_edit_form_fields', 'mts_product_cat_tax_edit_form_fields', 10, 2 );
    // Handle creating/editing/deleting of icons
    add_action( 'created_term', 'mts_create_update_product_cat_icon', 10, 3 );
    add_action( 'edited_term', 'mts_create_update_product_cat_icon', 10, 3 );
    add_action( 'delete_term', 'mts_delete_product_cat_icon', 10, 3 );
    // Enqueue scripts & styles
    add_action('admin_enqueue_scripts', 'mts_product_category_scripts');
    
    function mts_product_cat_tax_add_form_fields() {
        $mts_icons = unserialize( MTS_ICONS );
        ?>
        <div class="form-field">
            <label for="mts_product_cat_icon">
                <?php _e( 'Icon: ', 'mythemeshop' ); ?><br />
                <?php 
                echo '<select class="mts-product-cat-icon" id="mts_product_cat_icon" name="mts_product_cat_icon" style="width: 100%; max-width: 240px;">';
                echo '<option value=""'.selected('', '', false).'>'.__('No Icon', 'mythemeshop').'</option>';
                foreach ( $mts_icons as $icon_category => $icons ) {
                    echo '<optgroup label="'.$icon_category.'">';
                    foreach ($icons as $icon) {
                        echo '<option value="'.$icon.'">'.ucwords(str_replace('-', ' ', $icon)).'</option>';
                    }
                    echo '</optgroup>';
                }
        
                echo '</select>';
                ?>
            </label>
        </div>

        <?php
    }

    function mts_product_cat_tax_edit_form_fields( $tag, $taxonomy ) {
        $mts_icons = unserialize( MTS_ICONS );
        // clear value.
        $p_cat_icon = '';

        // tag id
        $id = $tag->term_id;

        $opt_array = get_option('mts_product_cat_icons');

        if ( $opt_array && array_key_exists( $taxonomy, $opt_array ) ) {

            if ( array_key_exists( $id, $opt_array[ $taxonomy ] ) ) {

                $p_cat_icon = $opt_array[ $taxonomy ][ $id ];
            }
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="mts_product_cat_icon"><?php _e( 'Icon:', 'mythemeshop' ); ?></label>
            </th>
            <td>
                <?php 
                echo '<select class="mts-product-cat-icon" id="mts_product_cat_icon" name="mts_product_cat_icon" style="width: 100%; max-width: 240px;">';
                echo '<option value=""'.selected($p_cat_icon, '', false).'>'.__('No Icon').'</option>';
                foreach ( $mts_icons as $icon_category => $icons ) {
                    echo '<optgroup label="'.$icon_category.'">';
                    foreach ($icons as $icon) {
                        echo '<option value="'.$icon.'"'.selected($p_cat_icon, $icon, false).'>'.ucwords(str_replace('-', ' ', $icon)).'</option>';
                    }
                    echo '</optgroup>';
                }
        
                echo '</select>';
                ?>
            </td>
        </tr>
        <?php
    }

    function mts_create_update_product_cat_icon( $term_id, $tt_id, $taxonomy ) {
        $opt_array = get_option('mts_product_cat_icons');

        if ( isset( $_POST['mts_product_cat_icon'] ) ) {

            $opt_array[ $taxonomy ][ $term_id ] = $_POST['mts_product_cat_icon'];
        }

        if ( isset( $opt_array ) ) {

            update_option( 'mts_product_cat_icons' , $opt_array );
        }
    }
    function mts_delete_product_cat_icon( $term_id, $tt_id, $taxonomy ) {
        $opt_array = get_option('mts_product_cat_icons');
        if ( $opt_array && isset( $opt_array[ $taxonomy ][ $term_id ] ) ) {

            unset( $opt_array[ $taxonomy ][ $term_id ] );
            update_option('mts_product_cat_icons', $opt_array);
        }
    }

    function mts_product_category_scripts() {

        $screen = get_current_screen();
        $screen_id = $screen->id;
        if ( 'edit-product_cat' == $screen_id ) {
        
            wp_enqueue_script(
                'select2', 
                get_template_directory_uri().'/options/js/select2.min.js',
                array('jquery'),
                null,
                true
            );
            wp_enqueue_script(
                'cat-icon-select',
                get_template_directory_uri().'/js/cat-icon-select.js',
                array('jquery', 'select2'),
                null,
                true
            );
            wp_enqueue_style(
                'select2',
                get_template_directory_uri().'/options/css/select2.css',
                array(),
                null,
                'all'
            );
            wp_enqueue_style(
                'font-awesome',
                get_template_directory_uri().'/css/font-awesome.min.css',
                array(),
                null,
                'all'
            );
        }
    }

    /*-----------------------------------------------------------------------------------*/
    /* Add color picker option to attribute taxonomies edit screens
    /*-----------------------------------------------------------------------------------*/
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    if ( $attribute_taxonomies ) {
        foreach ( $attribute_taxonomies as $tax ) {
            $attribute = sanitize_title( $tax->attribute_name );

            $taxonomy = wc_attribute_taxonomy_name( $attribute );
            
            add_action( $taxonomy.'_add_form_fields', 'mts_attribute_tax_add_form_fields' );
            add_action( $taxonomy.'_edit_form_fields', 'mts_attribute_tax_edit_form_fields', 10, 2 );
        }

        // Handle creating/editing/deleting of color attribute color
        add_action( 'created_term', 'mts_create_update_attribute_tax_color', 10, 3 );
        add_action( 'edited_term', 'mts_create_update_attribute_tax_color', 10, 3 );
        add_action( 'delete_term', 'mts_delete_attribute_tax_color', 10, 3 );

        // Enqueue color picker
        add_action('admin_enqueue_scripts', 'mts_attribute_tax_picker');
    }

    function mts_attribute_tax_add_form_fields() {
        ?>
        <div class="form-field">
            <label for="ps_color_hex_code">
                <?php _e( 'Choose color: ', 'mythemeshop' ); ?>
            </label>
            <input type="text" id="ps_color_hex_code" name="ps_color_hex_code" class="mts-color-picker-field" value="">
        </div>

        <?php
    }
    function mts_attribute_tax_edit_form_fields( $tag, $taxonomy ) {
        // clear value.
        $color_code = '';

        // tag id
        $id = $tag->term_id;

        $opt_array = get_option('mts_tax_color_codes');

        if ( $opt_array && array_key_exists( $taxonomy, $opt_array ) ) {

            if ( array_key_exists( $id, $opt_array[ $taxonomy ] ) ) {

                $color_code = $opt_array[ $taxonomy ][ $id ];
            }
        }
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="ps_color_hex_code"><?php _e( 'Choose color:', 'mythemeshop' ); ?></label>
            </th>
            <td>
                <input type="text" id="ps_color_hex_code" name="ps_color_hex_code"  class="mts-color-picker-field" value="<?php echo $color_code; ?>">
            </td>
        </tr>
        <?php
    }
    function mts_create_update_attribute_tax_color( $term_id, $tt_id, $taxonomy ) {
        $opt_array = get_option('mts_tax_color_codes');

        if ( isset( $_POST['ps_color_hex_code'] ) ) {

            $opt_array[ $taxonomy ][ $term_id ] = $_POST['ps_color_hex_code'];
        }

        if ( isset( $opt_array ) ) {

            update_option( 'mts_tax_color_codes' , $opt_array );
        }
    }
    function mts_delete_attribute_tax_color( $term_id, $tt_id, $taxonomy ) {
        $opt_array = get_option('mts_tax_color_codes');
        if ( $opt_array && isset( $opt_array[ $taxonomy ][ $term_id ] ) ) {

            unset( $opt_array[ $taxonomy ][ $term_id ] );
            update_option('mts_tax_color_codes', $opt_array);
        }
    }
    function mts_attribute_tax_picker() {
        $taxonomy_screens = array();
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        foreach ( $attribute_taxonomies as $tax ) {
            $attribute = sanitize_title( $tax->attribute_name );

            $taxonomy = wc_attribute_taxonomy_name( $attribute );
            
            $taxonomy_screens[] = 'edit-'.$taxonomy;
        }

        $screen = get_current_screen();
        $screen_id = $screen->id;

        if ( in_array( $screen_id, $taxonomy_screens ) ) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script( 'mts-color-picker-script', get_template_directory_uri() . '/js/color-picker.js', array('wp-color-picker') );
        }
    }

    // Create an array of product attribute taxonomies for use in ajax nav widget
    add_action( 'init', 'mts_wc_ajax_layered_nav_init', 99 );
    function mts_wc_ajax_layered_nav_init() {
        if ( is_active_widget( false, false, 'mts_wc_ajax_filter_widget', true ) && ! is_admin() ) {

            global $_chosen_attributes, $woocommerce, $_mts_attributes_array;

            $_chosen_attributes = $_mts_attributes_array = array();

            $attribute_taxonomies = wc_get_attribute_taxonomies();

            if ( $attribute_taxonomies ) {
                foreach ( $attribute_taxonomies as $tax ) {

                    $attribute = sanitize_title( $tax->attribute_name );

                    $taxonomy = wc_attribute_taxonomy_name($attribute);

                    // create an array of product attribute taxonomies
                    $_mts_attributes_array[] = $taxonomy;

                    $name = 'filter_' . $attribute;
                    $query_type_name = 'query_type_' . $attribute;

                    if ( ! empty( $_GET[ $name ] ) && taxonomy_exists( $taxonomy ) ) {

                        $_chosen_attributes[ $taxonomy ]['terms'] = explode( ',', $_GET[ $name ] );

                        if ( empty( $_GET[ $query_type_name ] ) || ! in_array( strtolower( $_GET[ $query_type_name ] ), array( 'and', 'or' ) ) )
                            $_chosen_attributes[ $taxonomy ]['query_type'] = apply_filters( 'woocommerce_layered_nav_default_query_type', 'and' );
                        else
                            $_chosen_attributes[ $taxonomy ]['query_type'] = strtolower( $_GET[ $query_type_name ] );

                    }
                }
            }

            add_filter('loop_shop_post_in', array( WC()->query, 'layered_nav_query' ));
        }
    }

    // Offers page Ajax category filter
    add_action('wp_ajax_mts_offers_product_filter', 'mts_offers_product_filter');
    add_action('wp_ajax_nopriv_mts_offers_product_filter', 'mts_offers_product_filter');

    function mts_offers_product_filter() {
        if( !isset( $_POST['mts_nonce'] ) || !wp_verify_nonce( $_POST['mts_nonce'], 'mts_nonce' ) ) {
            die('Permission denied');
        }

        $category = $_POST['cat_id'];
        $page = intval($_POST['page']);
        if ($page < 1) $page = 1;

        wc_get_template( 'offer-products.php', array( 'category' => $category, 'offers_page' => $page ) );

        die();
    }

    // Home tabs
    add_action('wp_ajax_mts_homepage_tabs', 'mts_homepage_tabs');
    add_action('wp_ajax_nopriv_mts_homepage_tabs', 'mts_homepage_tabs');

    function mts_homepage_tabs() {

        $tab = $_POST['home_tab'];
        $slug = $_POST['term_slug'];


        if ( 'new_products_tab' === $tab ) {
            $new_products_args = array('posts_per_page' => 4, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'product_cat' => $slug );
            $new_products_query = new WP_Query( $new_products_args );

            if ( $new_products_query->have_posts() ) {

                echo '<ul class="products">';

                while ( $new_products_query->have_posts() ) {
                    $new_products_query->the_post();
                    wc_get_template( 'content-product-home.php' );
                }

                echo '</ul>';
            }

            wp_reset_postdata();

        } else if ( 'top_rated_tab' === $tab )  {
            add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
            $top_rated_args = array('posts_per_page' => 4, 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product', 'product_cat' => $slug );
            $top_rated_args['meta_query'] = WC()->query->get_meta_query();

            $top_rated_query = new WP_Query( $top_rated_args );

            if ( $top_rated_query->have_posts() ) {

                echo '<ul class="products">';

                while ( $top_rated_query->have_posts() ) {
                    $top_rated_query->the_post();
                    wc_get_template( 'content-product-home.php' );
                }

                echo '</ul>';
            }

            remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );

            wp_reset_postdata();

        }

        die();
    }
}

/*-----------------------------------------------------------------------------------*/
/* add <!-- next-page --> button to tinymce
/*-----------------------------------------------------------------------------------*/
add_filter( 'mce_buttons', 'wysiwyg_editor' );
function wysiwyg_editor( $mce_buttons ) {
   $pos = array_search( 'wp_more', $mce_buttons, true );
   if ( $pos !== false ) {
       $tmp_buttons = array_slice( $mce_buttons, 0, $pos+1 );
       $tmp_buttons[] = 'wp_page';
       $mce_buttons = array_merge( $tmp_buttons, array_slice( $mce_buttons, $pos+1 ));
   }
   return $mce_buttons;
}

/*-----------------------------------------------------------------------------------*/
/*  Alternative post templates
/*-----------------------------------------------------------------------------------*/
function mts_get_post_template( $default = 'default' ) {
    global $post;
    $single_template = $default;
    $posttemplate = get_post_meta( $post->ID, '_mts_posttemplate', true );
    
    if ( empty( $posttemplate ) || ! is_string( $posttemplate ) )
        return $single_template;
    
    if ( file_exists( dirname( __FILE__ ) . '/singlepost-'.$posttemplate.'.php' ) ) {
        $single_template = dirname( __FILE__ ) . '/singlepost-'.$posttemplate.'.php';
    }
    
    return $single_template;
}
function mts_set_post_template( $single_template ) {
     return mts_get_post_template( $single_template );
}
add_filter( 'single_template', 'mts_set_post_template' );

/*-----------------------------------------------------------------------------------*/
/*  Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/
function mts_custom_gravatar( $avatar_defaults ) {
    $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$mts_avatar] = 'Custom Gravatar ( /images/gravatar.png )';
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'mts_custom_gravatar' );

/*-----------------------------------------------------------------------------------*/
/*  WP Review Support
/*-----------------------------------------------------------------------------------*/

// Set default colors for new reviews
function new_default_review_colors( $colors ) {
    $colors = array(
        'color' => '#FFCA00',
        'fontcolor' => '#fff',
        'bgcolor1' => '#151515',
        'bgcolor2' => '#151515',
        'bordercolor' => '#151515'
    );
  return $colors;
}
add_filter( 'wp_review_default_colors', 'new_default_review_colors' );
 
// Set default location for new reviews
function new_default_review_location( $position ) {
  $position = 'top';
  return $position;
}
add_filter( 'wp_review_default_location', 'new_default_review_location' );

/*-----------------------------------------------------------------------------------*/
/*  WP Mega Menu Thumb Size
/*-----------------------------------------------------------------------------------*/
function megamenu_thumbnails( $thumbnail_html, $post_id ) {
    $thumbnail_html = '<div class="wpmm-thumbnail">';
    $thumbnail_html .= '<a title="'.esc_attr(get_the_title( $post_id )).'" href="'.esc_url(get_permalink( $post_id )).'">';
    if(has_post_thumbnail($post_id)):
        $id = get_post_thumbnail_id();
        $image     = wp_get_attachment_image_src( $id, 'full' );
        $image_url = $image[0];
        $thumbnail = bfi_thumb( $image_url, array( 'width' => '244', 'height' => '190', 'crop' => true ) );
        $thumbnail_html .= '<img src="'.esc_attr($thumbnail).'" class="wp-post-image">';
    endif;
    $thumbnail_html .= '</a>';
    
    // WP Review
    $thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
    
    $thumbnail_html .= '</div>';

    return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Thumbnail Upscale
/*  Enables upscaling of thumbnails for small media attachments, 
/*  to make sure it fits into it's supposed location.
/*  Cannot be used in conjunction with Retina Support.
/*-----------------------------------------------------------------------------------*/
if ( empty( $mts_options['mts_retina'] ) ) {
    function mts_image_crop_dimensions( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ) {
        if( !$crop )
            return null; // let the wordpress default function handle this
    
        $aspect_ratio = $orig_w / $orig_h;
        $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );
    
        $crop_w = round( $new_w / $size_ratio );
        $crop_h = round( $new_h / $size_ratio );
    
        $s_x = floor( ( $orig_w - $crop_w ) / 2 );
        $s_y = floor( ( $orig_h - $crop_h ) / 2 );
    
        return array( 0, 0, ( int ) $s_x, ( int ) $s_y, ( int ) $new_w, ( int ) $new_h, ( int ) $crop_w, ( int ) $crop_h );
    }
    add_filter( 'image_resize_dimensions', 'mts_image_crop_dimensions', 10, 6 );
}

/*----------------------------------------------------------------------------------------*/
/*  Recalculate $content_width variable
/*----------------------------------------------------------------------------------------*/
add_action( 'template_redirect', 'mts_content_width' );
function mts_content_width() {
    global $content_width;
    // full width
    if ( mts_custom_sidebar() == 'mts_nosidebar' ) {
        $content_width = 1042;
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Modify gallery shortcode output ( dynamic image sizes, captions moved )
/*-----------------------------------------------------------------------------------*/
add_filter( 'post_gallery', 'mts_post_gallery', 10, 2 );
function mts_post_gallery( $output, $attr ) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) ) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( ! $attr['orderby'] ) {
            unset( $attr['orderby'] );
        }
    }

    $html5 = true;//current_theme_supports( 'html5', 'gallery' );
    $atts = shortcode_atts( array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => $html5 ? 'figure'     : 'dl',
        'icontag'    => $html5 ? 'div'        : 'dt',
        'captiontag' => $html5 ? 'figcaption' : 'dd',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => ''
    ), $attr, 'gallery' );

    $id = intval( $atts['id'] );
    if ( 'RAND' == $atts['order'] ) {
        $atts['orderby'] = 'none';
    }

    if ( ! empty( $atts['include'] ) ) {
        $_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( ! empty( $atts['exclude'] ) ) {
        $attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    } else {
        $attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    }

    if ( empty( $attachments ) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment ) {
            $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
        }
        return $output;
    }

    $itemtag = tag_escape( $atts['itemtag'] );
    $captiontag = tag_escape( $atts['captiontag'] );
    $icontag = tag_escape( $atts['icontag'] );
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) ) {
        $itemtag = 'dl';
    }
    if ( ! isset( $valid_tags[ $captiontag ] ) ) {
        $captiontag = 'dd';
    }
    if ( ! isset( $valid_tags[ $icontag ] ) ) {
        $icontag = 'dt';
    }

    $columns = intval( $atts['columns'] );

    $selector = "gallery-{$instance}";

    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} clearfix'>";

    $output = $gallery_div;

    // image width/height based on content width and number of columns
    global $content_width;
    $w = floor($content_width/$columns);

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {

        $attachment_img = wp_get_attachment_image_src( $id, 'full' );
        $attachment_url = $attachment_img[0];
        $image_src      = bfi_thumb( $attachment_url, array( 'width' => $w, 'height' => $w, 'crop' => true ) );

        $image = '<img src="'.$image_src.'" class="wp-post-image">';

        $image_meta  = wp_get_attachment_metadata( $id );

        $caption = '';
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $caption = "
                <{$captiontag} class='wp-caption-text gallery-caption'><div class='gallery-caption-inner'><span>
                " . wptexturize($attachment->post_excerpt) . "
                </span></div></{$captiontag}>";
        }

        if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
            $image_output = '<a href="'.$attachment_url.'">'.$image.$caption.'</a>';
        } elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
            $image_output = $image.$caption;
        } else {
            $attachment_page = get_attachment_link( $id );
            $image_output = '<a href="'.$attachment_page.'">'.$image.$caption.'</a>';
        }

        $orientation = '';
        if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
            $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
        }
        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "
            <{$icontag} class='gallery-icon {$orientation}'>
                $image_output
            </{$icontag}>";
        
        $output .= "</{$itemtag}>";
    }

    $output .= "
        </div>\n";

    return $output;
}

add_filter( 'body_class', 'mts_wp_subscribe_custom_style' );
function mts_wp_subscribe_custom_style($classes) {
    $mts_options = get_option( MTS_THEME_NAME );
    if (!empty($mts_options['mts_offers_subscribe'])) {
        $classes[] = 'mts-woocart-subscribe';
    }
    return $classes;
}

/*-----------------------------------------------------------------------------------*/
/*  Color manipulations
/*-----------------------------------------------------------------------------------*/
function mts_hex_to_hsl( $color ){
    // Sanity check
    $color = mts_check_hex_color($color);
    // Convert HEX to DEC
    $R = hexdec($color[0].$color[1]);
    $G = hexdec($color[2].$color[3]);
    $B = hexdec($color[4].$color[5]);
    $HSL = array();
    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);
    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;
    $L = ($var_Max + $var_Min)/2;
    if ($del_Max == 0) {
        $H = 0;
        $S = 0;
    } else {
        if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
        else            $S = $del_Max / ( 2 - $var_Max - $var_Min );
        $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        if      ($var_R == $var_Max) $H = $del_B - $del_G;
        else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
        else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;
        if ($H<0) $H++;
        if ($H>1) $H--;
    }
    $HSL['H'] = ($H*360);
    $HSL['S'] = $S;
    $HSL['L'] = $L;
    return $HSL;
}
function mts_hsl_to_hex( $hsl = array() ){
    list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );
    if( $S == 0 ) {
        $r = $L * 255;
        $g = $L * 255;
        $b = $L * 255;
    } else {
        if($L<0.5) {
            $var_2 = $L*(1+$S);
        } else {
            $var_2 = ($L+$S) - ($S*$L);
        }
        $var_1 = 2 * $L - $var_2;
        $r = round(255 * mts_huetorgb( $var_1, $var_2, $H + (1/3) ));
        $g = round(255 * mts_huetorgb( $var_1, $var_2, $H ));
        $b = round(255 * mts_huetorgb( $var_1, $var_2, $H - (1/3) ));
    }
    // Convert to hex
    $r = dechex($r);
    $g = dechex($g);
    $b = dechex($b);
    // Make sure we get 2 digits for decimals
    $r = (strlen("".$r)===1) ? "0".$r:$r;
    $g = (strlen("".$g)===1) ? "0".$g:$g;
    $b = (strlen("".$b)===1) ? "0".$b:$b;
    return $r.$g.$b;
}
function mts_huetorgb( $v1,$v2,$vH ) {
    if( $vH < 0 ) {
        $vH += 1;
    }
    if( $vH > 1 ) {
        $vH -= 1;
    }
    if( (6*$vH) < 1 ) {
           return ($v1 + ($v2 - $v1) * 6 * $vH);
    }
    if( (2*$vH) < 1 ) {
        return $v2;
    }
    if( (3*$vH) < 2 ) {
        return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
    }
    return $v1;
}
function mts_check_hex_color( $hex ) {
    // Strip # sign is present
    $color = str_replace("#", "", $hex);
    // Make sure it's 6 digits
    if( strlen($color) == 3 ) {
        $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
    }
    return $color;
}
// Check if color is considered light or not
function mts_is_light_color( $color ){
    $color = mts_check_hex_color( $color );
    // Calculate straight from rbg
    $r = hexdec($color[0].$color[1]);
    $g = hexdec($color[2].$color[3]);
    $b = hexdec($color[4].$color[5]);
    return ( ( $r*299 + $g*587 + $b*114 )/1000 > 130 );
}
// Darken color by given amount in %
function mts_darken_color( $color, $amount = 10 ) {
    $hsl = mts_hex_to_hsl( $color );
    // Darken
    $hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
    $hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L']/100;
    // Return as HEX
    return mts_hsl_to_hex($hsl);
}
// Lighten color by given amount in %
function mts_lighten_color( $color, $amount = 10 ) {
    $hsl = mts_hex_to_hsl( $color );
    // Lighten
    $hsl['L'] = ( $hsl['L'] * 100 ) + $amount;
    $hsl['L'] = ( $hsl['L'] > 100 ) ? 1 : $hsl['L']/100;
    
    // Return as HEX
    return mts_hsl_to_hex($hsl);
}
?>