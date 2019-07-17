<?php

require_once WP_PLUGIN_DIR.'/pronto-sidebar-navigation/pronto-page-walker.php';
require_once WP_PLUGIN_DIR.'/pronto-sidebar-navigation/pronto-sidebar-navigation.php';

/**
 * The Sidebar containing the main widget areas.
 *
 * @package Phoenix
 */

if ( is_page() ) {
    $id = get_the_ID();
}
elseif ( is_home() ) {
    $id = get_option( 'page_for_posts', true );
}

$sidebar_position = get_sidebar_setting( $id );

if ( ( 'right' == $sidebar_position ) || ( 'left' == $sidebar_position ) ) {
    if ( 'right' == $sidebar_position  ) {
        $sidebar_setting = 'pull';
        echo '<div role="complementary" class="col-md-3 col-sm-4 col-xs-12 col-md-' . $sidebar_setting . '-9 col-sm-' . $sidebar_setting . '-8">';
    }
    elseif ( 'left' == $sidebar_position ) {
        echo '<div role="complementary" class="col-md-3 col-sm-4 col-xs-12">';
    }
?>
        <div class="inner">
        <?php 
            do_action( 'before_sidebar' );
            if ( ! dynamic_sidebar( 'sidebar-1' ) ) : 
        ?>
                <div id="search" class="widget search-widget clearfix">
                    <?php get_search_form(); ?>
                </div>

                <div id="archives" class="widget clearfix">
                    <h3 class="widget-title"><?php _e( 'Archives', 'phoenix' ); ?></h1>
                    <ul>
                        <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                    </ul>
                </div>

                <div id="meta" class="widget clearfix">
                    <h3 class="widget-title"><?php _e( 'Meta', 'phoenix' ); ?></h1>
                    <ul>
                        <?php wp_register(); ?>
                        <li><?php wp_loginout(); ?></li>
                        <?php wp_meta(); ?>
                    </ul>
                </div>
        <?php 
            endif; // end sidebar widget area 
        ?>
        </div>
    </div>
<?php 
} 
?>
