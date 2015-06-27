<?php
$mts_options = get_option(MTS_THEME_NAME);
// default = 3
$top_footer_num = empty($mts_options['mts_top_footer_num']) ? 3 : $mts_options['mts_top_footer_num'];
?>
    	   </div><!--#page-->
        </div><!--.container-->
    </div><!--.content-wrap-->
	<footer id="site-footer" class="main-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
            <?php if ($mts_options['mts_top_footer']) : ?>
    			<div class="footer-widgets top-footer-widgets widgets-num-<?php echo $top_footer_num; ?>">
                    <?php
                    for ( $i = 1; $i <= $top_footer_num; $i++ ) {
                        $sidebar = ( $i == 1 ) ? 'footer-top' : 'footer-top-'.$i;
                        $class = ( $i == $top_footer_num ) ? 'f-widget last f-widget-'.$i : 'f-widget f-widget-'.$i;
                        ?>
                        <div class="<?php echo $class;?>">
                            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $sidebar ) ) : ?><?php endif; ?>
                        </div>
                        <?php
                    }
                    ?>
    			</div><!--.top-footer-widgets-->
            <?php endif; ?>
            
            <div id="footer-separator">
                <div class="left-border"><span></span></div>
                <a href="#blog" id="footer-to-top" class="to-top"><i class="fa fa-angle-double-up"></i></a>
                <div class="right-border"><span></span></div>
            </div>

            <?php if ($mts_options['mts_bottom_footer']) : ?>
                <div class="bottom-footer">
                    <?php echo $mts_options['mts_bottom_footer_content']; ?>
                </div>
            <?php endif; ?>

            <div id="footer">
                <div class="copyrights">
                    <?php echo $mts_options['mts_copyrights']; ?>
                </div>
                <div class="footer-navigation">
                    <nav id="footer-navigation" class="clearfix" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
                        <?php if ( has_nav_menu( 'footer-menu' ) ) { ?>
                            <?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'footer-menu clearfix', 'container' => '' ) ); ?>
                        <?php } else { ?>
                            <ul class="footer-menu clearfix">
                                <?php wp_list_pages('title_li='); ?>
                            </ul>
                        <?php } ?>
                    </nav>
                </div>
                <div class="footer-right">
                    <?php mts_credit_cards(); ?>
                </div>
            </div><!--#footer-->
		</div><!--.container-->
	</footer><!--footer-->
</div><!--.main-container-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>