<?php
/**
 * Phoenix functions and definitions
 *
 * @package Phoenix
 */

/**
 * List of Functions
 * phoenix_setup
 */
require_once locate_template( '/lib/phoenix_settings.php' );

/**
 * List of Functions
 * get_featured_image_size, add_jumpdown_section, get_masthead, get_page_title,
 * get_sidebar_setting, get_header_group, get_footer_group, embed_google_fonts
 */
require_once locate_template( '/lib/site_options.php' );

/**
 * List of Functions
 * improved_trim_excerpt, pagination, get_post_image, catch_that_image, shortcode_exists,
 * custom_edit_post_link, filter_revisions_to_keep, change_comment_class, change_avatar_class,
 * display_discourage_search_engines_notification
 */
require_once locate_template( '/lib/utils.php' );

/**
 * List of Functions
 * phoenix_widgets_init, apply_class_to_widgets, load_extended_widgets
 */
require_once locate_template( '/lib/widgets.php' );

/**
 * List of Functions
 * phoenix_scripts, enqueue_new_relic_rum_script_for_header
 */
require_once locate_template( '/lib/scripts.php' );

/**
 * List of Functions
 * woocommerce_wrapper_start, woocommerce_wrapper_end
 */
require_once locate_template( '/lib/addons.php' );

/**
 * List of Functions
 * pronto_gallery, extend_gallery_shortcode
 */
require_once locate_template( '/lib/pronto-extended-gallery.php' );

/**
 * List of Functions
 * more fileds for Pronto Author Bio on Blog Post
 */
require_once locate_template( '/lib/pronto-author-bio-on-blog-post.php' );

/**
 * List of Functions
 * more fileds for Landing Page Header Group
 */
require_once locate_template( '/lib/landing-page-header-group.php' );

/**
 * List of Functions
 * more fileds for Landing Page Footer Group
 */
require_once locate_template( '/lib/landing-page-footer-group.php' );

/**
 * List of Functions
 * more fileds for Custom Landing Page CSS
 */
require_once locate_template( '/lib/custom-landing-page-css.php' );

/**
 * Remove update notification for WooCommerce
 */
remove_action( 'admin_notices', 'woothemes_updater_notice' );
