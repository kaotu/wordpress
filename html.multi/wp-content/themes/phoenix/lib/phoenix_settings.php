<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( ! function_exists( 'phoenix_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features,custom_sidebar_setting_enable such as indicating
	 * support post thumbnails.
	 */
	define('WP_PHOENIX_DIR', WP_CONTENT_DIR.'/themes/phoenix');
	function phoenix_setup() {

		/**
		 * Custom template tags for this theme.
		 */
		require( get_template_directory() . '/inc/template-tags.php' );

		/**
		 * Custom functions that act independently of the theme templates
		 */
		require( get_template_directory() . '/inc/extras.php' );

		/**
		 * Customizer additions
		 */
		require( get_template_directory() . '/inc/customizer.php' );

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on Phoenix, use a find and replace
		 * to change 'phoenix' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'phoenix', get_template_directory() . '/languages' );

		// This theme styles the visual editor.
		add_editor_style( 'editor-style.css' );

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable support for Post Formats
		 */
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery' ) );
	}
endif; // phoenix_setup

/**
 * Set the content width in pixels based on the theme's design and stylesheet.
 */

if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

add_action( 'after_setup_theme', 'phoenix_setup' );

/**
 * Require to call Theme Customiser
 */
require_once( WP_CONTENT_DIR . '/themes/phoenix/options.php' );

// Choose priority 101 because we want this css to enqueue right before css genereated by custom css.
add_action( 'wp_head', 'add_css_to_header', 101 );

function add_css_to_header() {
	// for make uniqe on when we use cdn . when file is change it will push new data time modify
	$filename = WP_PHOENIX_DIR.'/css/main.css';
	$datemodify = filemtime( $filename );
	//echo 'get_stylesheet_directory'.get_stylesheet_directory();

	$stylecss = get_stylesheet_directory() . '/style.css';
	if ( file_exists( $stylecss ) ) {
		$stylecssmodify = filemtime( $stylecss );
	}
	else {
		$stylecssmodify = '';
	}

	echo '<link rel="stylesheet" id="main-css" href="' . get_template_directory_uri() . '/css/main.css?' . $datemodify . '" type="text/css" media="all" />';
	echo "\n";
	echo '<link rel="stylesheet" id="style-css" href="' . get_stylesheet_uri() . '?' .$stylecssmodify . '" type="text/css" media="all" />';
	echo "\n";
	get_color_palette();
}

add_action( 'wp_head', 'add_css_to_header', 101 );

/**
 * Enqueue Theme Customizer Live Preview javascript
 */
function theme_customizer_live_preview() {
	//Get child theme's name
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option($themename);

	$inner_page_option = $site_options['background_inner_page'];

	// Get option from site_background options.
	$background_customizer_option = $site_options['site_background'];
	$site_bg_color_option         = $background_customizer_option['color'];
	$site_bg_img_option           = $background_customizer_option['image'];
	$site_bg_position_option      = $background_customizer_option['position'];
	$site_bg_repeat_option        = $background_customizer_option['repeat'];
	$site_bg_attachment_option    = $background_customizer_option['attachment'];
	$site_bg_size_option          = $background_customizer_option['size'];

	wp_enqueue_script(
		'theme_customizer_preview',
		get_template_directory_uri() . '/js/theme-customizer.js',
		array( 'customize-preview' ),
		'1.0.2',
		true
	);
	wp_localize_script(
		'theme_customizer_preview',
		'theme_name_var',
		array(
			'theme_name'                => $themename,
			'inner_option'              => $inner_page_option,
			'site_bgcolor_option'       => $site_bg_color_option,
			'site_bgimg_option'         => $site_bg_img_option,
			'site_bgposition_option'    => $site_bg_position_option,
			'site_bgrepeat_option'      => $site_bg_repeat_option,
			'site_bgattachement_option' => $site_bg_attachment_option,
			'site_bgsize_option'        => $site_bg_size_option
		)
	);
}

add_action( 'customize_preview_init', 'theme_customizer_live_preview' );

/**
 * Activate WordPress Plugins Automatically When Activate Phoenix Theme
 */
if ( ! function_exists( 'plugins_to_active' ) ) {
	function plugins_to_active() {
		$plugins_to_active = array(
			'pronto-breadcrumb-navigation/pronto-breadcrumb-navigation.php',
			'pronto-header-manager/header-custom-types.php',
			'pronto-footer-manager/footer-custom-types.php',
			'pronto-page-option-settings/pronto_page_option_setting.php',
			'pronto-shortcode/pronto-shortcode.php',
			'pronto-shortcodes-ui/pronto-shortcodes-ui.php',
			'pronto-sidebar-navigation/pronto-sidebar-navigation.php',
			'pronto-site-option-backup/pronto-site-option-backup.php',
			'pronto-company-information/pronto-company-information.php',
			'add-descendants-as-submenu-items/add-descendants-as-submenu-items.php',
			'simple-page-ordering/simple-page-ordering.php',
			'wooslider/wooslider.php',
			'woosidebars/woosidebars.php',
			'gravityforms/gravityforms.php',
			'jetpack/jetpack.php',
			'pronto-jetpack-overrides/pronto-jetpack-overrides.php',
			'pronto-locations-manager/pronto-locations-manager.php',
			'advanced-custom-fields-pro/acf.php'
		);

		return $plugins_to_active;
	}
}

add_action( 'after_switch_theme', 'run_activate_plugin', 10 );

function run_activate_plugin() {
	$current = get_option( 'active_plugins' );
	$plugins_to_active = plugins_to_active();

	foreach ( $plugins_to_active as $plugin ) {
		$plugin = plugin_basename( trim( $plugin ) );
		if ( ! in_array( $plugin, $current ) ) {
			$current[] = $plugin;
			sort( $current );
			do_action( 'activate_plugin', trim( $plugin ) );
			update_option( 'active_plugins', $current );
			do_action( 'activate_' . trim( $plugin ) );
			do_action( 'activated_plugin', trim( $plugin ) );
		}
	}

	return null;
}

/**
 * Disable Lazy Load for newsletter post type
 */
function disable_lazy_load_on_newsletter_page() {
	if ( 'newsletter' == get_post_type() && is_single() ) {
		remove_filter( 'the_content',         array( 'LazyLoad_Images', 'add_image_placeholders' ), 99 );
		remove_filter( 'post_thumbnail_html', array( 'LazyLoad_Images', 'add_image_placeholders' ), 11 );
		remove_filter( 'get_avatar',          array( 'LazyLoad_Images', 'add_image_placeholders' ), 11 );
	}
}

add_action( 'template_redirect', 'disable_lazy_load_on_newsletter_page' );

if ( is_plugin_active( 'pronto-lead-insights/pronto-lead-insights.php' ) ) {
	$gravityforms_api_option = get_option('gravityformsaddon_gravityformswebapi_settings');
	if ( $gravityforms_api_option["enabled"] == 0 ) {
		update_option(
			'gravityformsaddon_gravityformswebapi_settings',
			array(
				"enabled" => "1",
				"public_key" => substr(wp_hash(site_url()), 0, 10),
				"private_key" => substr(wp_hash(get_bloginfo("admin_email")), 0, 15),
				"impersonate_account" => "1"
			)
		);
	}
}

/**
 * Filter the upload size limit
 */
function filter_site_upload_size_limit( $size ) {
	// Set the upload size limit to 23 MB
	$size = 2448 * 10000;
	return $size;
}

add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

function phoenix_wooslider_override ( $start ) {
	if ( is_plugin_active( 'wooslider/wooslider.php' ) ) {
		$start .= 'var wooslider_holder = jQuery(slider).find("li.slide"); if(0 !== wooslider_holder.length){ var wooslides = ([]).concat(wooslider_holder.splice(0,2), wooslider_holder.splice(-2,2), jQuery.makeArray(wooslider_holder)); jQuery.each(wooslides, function(i,el){ var content = jQuery(this).attr("data-wooslidercontent"); if(typeof content == "undefined" || false == content) return; jQuery(this).append(content).removeAttr("data-wooslidercontent"); }); } jQuery(slider).fitVids(); var maxHeight = 0; jQuery(slider).find(".wooslider-control-nav li").each(function(i,el) { maxHeight = maxHeight > jQuery(this).height() ? maxHeight : jQuery(this).height(); }); jQuery(slider).css("margin-bottom", "0" + "px");';
		return $start;
	}
}

add_filter( 'wooslider_callback_start', 'phoenix_wooslider_override', 10, 3 );

/**
 * Enqueue style and script for blog style C
 */
function enqueue_style_and_script_for_blog_style_c() {
	$themename    = get_option( 'stylesheet' );
	$themename    = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option( $themename );
	if ( 'c' == $site_options['blog_style'] && is_home() ) {
		wp_enqueue_style( 'blog_style_c', get_template_directory_uri() . '/css/blog_style_c.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_style_and_script_for_blog_style_c' );

/**
 * Hiding Gutenburg (block editor in WordPress 5)
 */
add_filter( 'use_block_editor_for_post', '__return_false' );
