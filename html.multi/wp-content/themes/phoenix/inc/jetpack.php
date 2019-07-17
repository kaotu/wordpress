<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Phoenix
 */


/**
 * Showcase Custom Post Type Infinite Scroll Support
 * See: http://jetpack.me/support/infinite-scroll/
 */

function infinite_scroll_layouts() {
	if( is_post_type_archive('showcase') || is_tax('showcase_category') || is_tax('showcase_tag')) :
		if(have_posts()) : while(have_posts()) : the_post();
			$render_showcase_html = '';
			//get the site url
			$site_url = get_post_meta(get_the_ID(), 'pronto_custom_showcase_site_url', true  );
			//showcase site screenshot
			echo '<div class="col-md-4 col-sm-6 col-xs-6 margin-bottom-40 showcase-item">';
			echo '<div class="showcase-shadow">';
			echo '<div class="showcase-screenshot">';
			echo '<img class="img-responsive" src="//screenshots.prontomarketing.com/mshots/v1/' . urlencode($site_url) . '%2F?w=360">';
			echo '<div class="caption">';
			echo '<a href="' . $site_url . '" target="_blank">';
			echo '<i class="fa fa-link fa-2x"></i>';
			echo '</a>';
			echo '<a href="' . get_permalink() . '">';
			echo '<i class="fa fa-book fa-2x margin-left-10"></i>';
			echo '</a>';
			echo '</span>';
			echo '</div>';
			echo '</div>';
			echo '<br><h4 class="text-center margin-bottom-20">' . get_the_title() . '</h4>';
			echo '</div>';
			echo '</div>';
		endwhile; endif;
	endif;        
}

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function phoenix_infinite_scroll_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'toc',
		'footer'    => 'footer',
		'render'    => 'infinite_scroll_layouts',
		'wrapper'   => 	false,
	) );
}

add_action( 'after_setup_theme', 'phoenix_infinite_scroll_setup' );

/**
* Enqueue Jetpack sharing script
*
*/
if ( file_exists( WP_PLUGIN_DIR . '/jetpack/class.jetpack.php' ) ) {
	if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
		require_once( WP_PLUGIN_DIR . '/jetpack/class.jetpack.php' );
		if ( Jetpack::is_module_active( 'sharedaddy' ) ) {
			wp_enqueue_script( 'sharing-js-fe', '/wp-content/plugins/jetpack/modules/sharedaddy/sharing.js', array(), 4, true );
		}
	}
}
