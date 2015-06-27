<!DOCTYPE html>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php mts_meta(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body id ="blog" <?php body_class('main'); ?> itemscope itemtype="http://schema.org/WebPage">       
	<div class="main-container">
		<header id="site-header" class="main-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
			<div id="header">
				<div class="container header-container">
				<?php if ( $mts_options['mts_show_primary_nav'] == '1' ): ?>
					<div class="primary-navigation">
		        		<nav id="top-navigation" class="clearfix" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
		        			<?php if ( has_nav_menu( 'primary-menu' ) ) { ?>
		        				<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
		        			<?php } else { ?>
		        				<ul class="menu clearfix">
		        					<?php wp_list_pages('title_li='); ?>
		        				</ul>
		        			<?php } ?>
		        		</nav>
		        	</div>
		        <?php endif; ?>
		        	<div class="header-inner">
						<div class="logo-wrap">
							<?php if ($mts_options['mts_logo'] != '') { ?>
								<?php if( is_front_page() || is_home() || is_404() ) { ?>
										<h1 id="logo" class="image-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>
										</h1><!-- END #logo -->
								<?php } else { ?>
									  	<h2 id="logo" class="image-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a>
										</h2><!-- END #logo -->
								<?php } ?>
							<?php } else { ?>
								<?php if( is_front_page() || is_home() || is_404() ) { ?>
										<h1 id="logo" class="text-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
										</h1><!-- END #logo -->
								<?php } else { ?>
									  <h2 id="logo" class="text-logo" itemprop="headline">
											<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
										</h2><!-- END #logo -->
								<?php } ?>
								<div class="site-description" itemprop="description">
									<?php bloginfo( 'description' ); ?>
								</div>
							<?php } ?>
						</div>
						<?php if($mts_options['mts_header_cart'] == '1') { ?>
        					<div class="mts-cart-button-wrap cart-content-hidden">
           						<div class="mts-cart-button cart-contents">
									<?php mts_cart_button(); ?>
								</div>
							</div>
						<?php } ?>
						<?php if($mts_options['mts_header_search_form'] == '1') { ?>
							<div class="header-search"><?php if ( mts_isWooCommerce() ) { get_product_search_form(); } else { get_search_form(); } ?></div>
						<?php } ?>
					</div>
				</div>
			</div><!--#header-->
			<?php if ( $mts_options['mts_show_secondary_nav'] == '1' ): ?>
			<?php if ( $mts_options['mts_sticky_nav'] == '1' ) { ?>
				<div class="clear" id="catcher"></div>
				<div id="sticky" class="secondary-navigation">
			<?php } else { ?>
			<div class="secondary-navigation">
			<?php } ?>
				<div class="container">
					<nav id="navigation" class="clearfix" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
						<a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu','mythemeshop'); ?></a>
						<?php if ( has_nav_menu( 'secondary-menu' ) ) { ?>
							<?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
						<?php } else { ?>
							<ul class="menu clearfix">
								<?php wp_list_categories('title_li='); ?>
							</ul>
						<?php } ?>
					</nav>
				</div><!--.container-->
			</div>
			<?php endif; ?>
		</header>
		<?php if ( $mts_options['mts_show_quick_links_nav'] == '1' && !is_page_template('page-offers.php') ) { ?>
		<div class="quick-links-menu">
			<div class="container">
				<nav id="quick-links-navigation" class="clearfix" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
					<?php if ( has_nav_menu( 'quick-links-menu' ) ) { ?>
						<?php wp_nav_menu( array( 'theme_location' => 'quick-links-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
					<?php } else { ?>
						<ul class="menu clearfix">
							<?php wp_list_categories('title_li='); ?>
						</ul>
					<?php } ?>
				</nav>
			</div>
		</div>
		<?php } ?>
		<?php if ( !is_home() && !is_page_template('page-offers.php') && $mts_options['mts_breadcrumb'] == '1' ) { ?>
		<div class="breadcrumb-wrap">
			<div class="container">
				<div class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#"><?php mts_the_breadcrumb(); ?></div>
			</div>
		</div>
		<?php } ?>
		<div class="content-wrap">
			<div class="container">