<?php
add_filter( 'the_content', 'strip_the_content', 9 );
function strip_the_content( $content ) {
	if ( true == is_home() or true == is_tag() or true == is_category() or true == is_archive() ) {
		$content = strip_tags( $content, '<p>');
		$content = strip_shortcodes( $content );
		$content = clip_sentence( $content, 250 );
		return '<p>' . $content . '</p>';
	} else {
		return $content;
	}
}

if ( ! function_exists( 'get_content_from_link' ) ) {
	function get_content_from_link( $link ) {
		$cachekey = 'blogengine-' . $link;
		$content  = wp_cache_get( $cachekey );
		if ( $content == false ) {
			$doc = new DOMDocument();
			$doc->load( $link );
			foreach ( $doc->getElementsByTagName( 'item' ) as $node ) {
				$content = $node->getElementsByTagName( 'content' )->item( 0 )->nodeValue;
			}
			unset( $doc );
			wp_cache_set( $cachekey, $content, "", 86000 );
		}

		return $content;
	}
}

if ( ! function_exists( 'get_link_url' ) ) {
	function get_link_url( $post, $blog_engine_host, $blog_id ) {
		global $wpdb;

		$sql      = "select * from feed_fetcher_pulled_post where blog_id={$blog_id} and post_id={$post->ID}";
		$result   = $wpdb->get_row( $sql, ARRAY_A );
		$be_link  = $result['blog_engine_link'];
		$link_url = "http://" . $blog_engine_host . $be_link;

		return $link_url;
	}
}

/**
 * Paginations
 */
function pagination( $pages = '', $range = 3 ) {
	$showitems = ( $range * 2 ) + 1;

	# page are currently viewing.
	global $paged;

	if ( empty( $paged ) ) {
		$paged = 1;
	}

	if ( $pages == '' ) {
		global $wp_query;

		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 != $pages ) {
		echo '<div class="text-center">';
		echo '<ul class="pagination">';

		# show the first page
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			echo '<li><a href="' . get_pagenum_link( 1 ) . '">&laquo;</a></li>';
		}
		# show the previous page
		if ( $paged > 1 && $showitems < $pages ) {
			echo '<li><a href="' . get_pagenum_link( $paged - 1 ) . '">&lsaquo;</a></li>';
		}
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				echo ( $paged == $i ) ? '<li class="active"><span>' . $i . '</span></li>' : '<li><a href="' . get_pagenum_link( $i ) . '">' . $i . '</a></li>';
			}
		}
		# show the next page
		if ( $paged < $pages && $showitems < $pages ) {
			echo '<li><a href="' . get_pagenum_link( $paged + 1 ) . '">&rsaquo;</a></li>';
		}
		# show the last page
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			echo '<li><a href="' . get_pagenum_link( $pages ) . '">&raquo;</a></li>';
		}
		echo '</ul>';
		echo '</div>';
	}
}

/**
 * Image for each post
 */
add_image_size( 'featured-large', 800, 250, true );
add_image_size( 'featured-small', 400, 250, true );

function get_post_image( $size, $class = '' ) {
	$image = catch_that_image( $size, $class );

	if ( $image['url'] ) {
		if ( $image['feature_img'] ) {
			$result = $image['feature_img'];
		}
		else {
			$result = '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" class="' .  $class . '" />';
		}
		$result .= '<meta content="'. $image['url'] .'" itemprop="image">';
	}
	else {
		$result = '';
	}

	return $result;
}

/**
 * Show feature image or first image of post
 */
function catch_that_image( $size, $class ) {
	global $post;

	$image = array();

	$post_id = get_the_ID();

	if( has_post_thumbnail() ) {
		if( $class ) {
			$img_attr = array( 'class' => $class );
		}
		else {
			$img_attr = array();
		}
		$image['feature_img'] = get_the_post_thumbnail( $post_id, $size, $img_attr );
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		$image_attributes  = wp_get_attachment_image_src( $post_thumbnail_id, $size, 0 );
		$image['url'] = $image_attributes[0];
		$image['alt'] = '';
	}
	else {
		$image['feature_img'] = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all( '/<img[^>]*src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
		$post_thumbnail = $matches[0][0];
		if( empty( $post_thumbnail ) ) {
			$image['url'] = '';
			$image['alt'] = '';
		}
		else {
			preg_match( '/src *= *"(?P<url>[^"]*)/' , $post_thumbnail, $match_url );
			$image['url'] = $match_url['url'];

			if ( preg_match('/alt *= *"(?P<alt>[^"]*)/', $post_thumbnail, $match_alt ) ) {
				$image['alt'] = $match_alt['alt'];
			}
			else {
				$image['alt'] = get_the_title( $post_id );
			}
		}
	}

	return $image;
}

/*
 * Check if a shortcode is registered.
 * Credit: https://gist.github.com/r-a-y/1887242
 */
if ( ! function_exists( 'shortcode_exists' ) ) {
	function shortcode_exists( $shortcode = false ) {
		global $shortcode_tags;

		if ( ! $shortcode ) {
			return false;
		}
		elseif ( array_key_exists( $shortcode, $shortcode_tags ) ) {
			return true;
		}
	}
}

/**
 * Add class to edit button
 */
if ( ! function_exists( 'custom_edit_post_link' ) ) {
	function custom_edit_post_link( $output ) {
		$output = str_replace( 'post-edit-link', 'post-edit-link btn btn-default', $output );

		return $output;
	}
}

add_filter( 'edit_post_link', 'custom_edit_post_link' );

if ( ! function_exists( 'filter_revisions_to_keep' ) ) {
	function filter_revisions_to_keep( $num, $post ) {
		if( 'safecss' == $post->post_type ) {
			$num = 20;
		}
		else {
			$num = 5;
		}

		return $num;
	}
}

add_filter( 'wp_revisions_to_keep', 'filter_revisions_to_keep', 10, 2 );

if( ! function_exists( 'clip_sentence' ) ) {
	function clip_sentence( $content, $max_char, $added_text='' ) {
		$offset = 0;

		preg_match( '/<img[^>]+\>/i', $content, $img_match, PREG_OFFSET_CAPTURE );
		if ( $img_match ) {
			$max_char = $max_char + strlen( $img_match[0][0] );
		}

		preg_match( '/([a-z]{2}\.)/', $content, $match, PREG_OFFSET_CAPTURE, $max_char );
		if ( $match ) {
			$offset = $match[1][1];
		}

		if ( 0 != $offset ) {
			//+3 because it return first char that match and we match 3 char
			$content = substr( $content, 0, $offset + 3 );
			$content = $content . $added_text;
		}

		return $content;
	}
}

/**
 * Add filter for comment_class for show comment box <li class="...">
 */
function change_comment_class( $classes ) {
	foreach( $classes as $key => $class ) {
		switch( $class ) {
			case 'even':
			case 'thread-even':
			case 'odd':
			case 'thread-odd':
			case 'alt':
			case 'thread-alt':
				unset( $classes[ $key ] );

				break;
			case 'depth-1':
				$classes[] = 'well';

				break;
			default:
				continue;
		}
	}

	return $classes;
}

add_filter( 'comment_class', 'change_comment_class' );

/**
* Add filter to change class avatar for comment box
*/
function change_avatar_class( $class ) {
	$class = str_replace( 'avatar avatar-60 photo', 'avatar photo media-object img-rounded', $class );

	return $class;
}

add_filter( 'get_avatar', 'change_avatar_class' );

if( ! function_exists( 'clip_text' ) ) {
	function clip_text( $content, $max_char, $added_text='' ) {
		$count_char = 0;
		$words = explode( ' ', $content );
		$words = array_reverse( $words );
		$content_array = array();
		if ( strlen( $content ) >= $max_char ) {
			while ( $count_char < $max_char and !empty( $words ) ) {
				$word = array_pop( $words );
				array_push( $content_array, $word );
				$count_char = $count_char + strlen( $word ) + 1 ;
			}
			$content = implode( ' ', $content_array );
			$content = $content . $added_text;
		}
		else {
			return $content;
		}
		return $content;
	}
}

add_action( 'masthead_jumpdown_hook', 'masthead_jumpdown_hook_func' );

function masthead_jumpdown_hook_func() {
	global $post;

	$current_page_id = NULL;
	if ( is_page() ) {
		$current_page_id = get_the_ID();
	}
	elseif ( is_home() ) {
		$current_page_id = get_option('page_for_posts', true);
	}
	elseif( 'landing_page' == $post->post_type ) {
		$current_page_id = $post->ID;
	}
	elseif ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
		if( strpos($_SERVER['REQUEST_URI'], 'wp-activate.php') === false ){
			if( is_shop() ) {
				$current_page_id = get_option( 'woocommerce_shop_page_id' );
			}
			elseif ( is_cart() ) {
				$current_page_id = get_option( 'woocommerce_cart_page_id' );
			}
			elseif ( is_checkout() ) {
				$current_page_id = get_option( 'woocommerce_checkout_page_id' );
			}
			elseif ( is_account_page() ) {
				$current_page_id = get_option( 'woocommerce_myaccount_page_id' );
			}
		}
	}

	if ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
		if( strpos($_SERVER['REQUEST_URI'], 'wp-activate.php') === false ){
			if ( is_page() or is_home() or is_shop() or is_cart() or is_checkout() or is_account_page() ) {
				echo get_masthead();
				echo get_page_title( 'segment', $current_page_id );
			}
		}
	}
	else {
		if ( is_page() or is_home() ) {
			echo get_masthead();
			echo get_page_title( 'segment', $current_page_id );
		}
	}
}

add_action('normal_page_end_header', 'normal_page_end_header_func');

function normal_page_end_header_func(){
	/* Check if page template is not 'template-fullwidth.php' or post type is not Landing Page */
	global $post;

	//Get child theme's name
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option($themename);

	if ( 'landing_page' != $post->post_type or is_search() ) {
		if ( ! is_page_template( 'template-fullwidth.php' ) and  $post->post_type != 'showcase' ) {
			//get setting of Site Layout
			$site_layout = $site_options['site_layout'];

			if ( $site_layout == 'fixed' ) {
				$render_segment = ' container shadow-side';
				$render_container = '';
			} else {
				$render_segment = '';
				$render_container = ' class="container"';
			}
			echo '<div class="segment body-background' . $render_segment . '">';
			echo '<div' . $render_container . '>';
			echo '<div class="row content">';
		}
	}
}

add_action('normal_page_start_footer', 'normal_page_start_footer_func');

function normal_page_start_footer_func(){
/* Check if page template is not 'template-fullwidth.php' or post type is not Landing Page */
	global $post;
	if ( $post->post_type != 'landing_page' or is_search() ) {
		if ( ! is_page_template( 'template-fullwidth.php' ) ) {
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
}

require_once ( WP_CONTENT_DIR . '/themes/phoenix/color-palette.php' );
function get_color_palette(){
	echo "<style>\n";
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
	$site_options = get_option( $themename );

	echo "h1, h2, h3, h4, h5, h6 { font-family: {$site_options['headers_font_options']}; color: {$site_options['default_heading_color']}; }\n";
	echo "input, button, select, textarea, body { font-family: {$site_options['body_font_options']}; }\n";
	echo "body { color: {$site_options['default_body_text_color']};";
	$background_customize = 'site_background';

	echo get_background_styles($background_customize) . "}\n";

	get_background_inner_page($background_customize_inner);
	$background_customize_inner = 'background_inner_page';

	echo ".body-background { " . get_background_inner_page($background_customize_inner). "}\n";
	echo "a { color: {$site_options['default_link_color']}; }\n";

	echo ".segment1 { color:{$site_options['body_text_color_segment_1']};";
	$background_customize = 'background_segment_1';
	echo get_background_styles($background_customize). "}\n";
	echo ".segment1 h1, .segment1 h2, .segment1 h3, .segment1 h4, .segment1 h5, .segment1 h6 { color: {$site_options['heading_font_color_segment_1']}; }\n";

	echo ".segment2 { color: {$site_options['body_text_color_segment_2']};";
	$background_customize = 'background_segment_2';
	echo get_background_styles($background_customize) . "}\n";
	echo ".segment2 h1, .segment2 h2, .segment2 h3, .segment2 h4, .segment2 h5, .segment2 h6 { color: {$site_options['heading_font_color_segment_2']}; }\n";

	echo ".segment3 { color: {$site_options['body_text_color_segment_3']};";
	$background_customize = 'background_segment_3';
	echo get_background_styles($background_customize) . "}\n";
	echo ".segment3 h1, .segment3 h2, .segment3 h3, .segment3 h4, .segment3 h5, .segment3 h6 { color: {$site_options['heading_font_color_segment_3']}; }\n";

	echo ".segment4 { color: {$site_options['body_text_color_segment_4']};";
	$background_customize = 'background_segment_4';
	echo get_background_styles($background_customize). "}\n";
	echo ".segment4 h1, .segment4 h2, .segment4 h3, .segment4 h4, .segment4 h5, .segment4 h6 { color: {$site_options['heading_font_color_segment_4']}; }\n";

	echo ".segment5 { color: {$site_options['body_text_color_segment_5']};";
	$background_customize = 'background_segment_5';
	echo get_background_styles($background_customize) . "}\n";
	echo ".segment5 h1, .segment5 h2, .segment5 h3, .segment5 h4, .segment5 h5, .segment5 h6 { color: {$site_options['heading_font_color_segment_5']}; }\n";

	echo ".accent { color: {$site_options['accent_color']}; }\n";

	echo "/* Tablets */\n";
	echo "@media (max-width: 768px) {\n";
	echo "body {";
	$background_customize = 'site_background';
	echo get_background_media_query($background_customize) . "}\n";
	echo "}\n";

	echo "/* Landscape phones and down */\n";
	echo "@media (max-width: 480px) {\n";
	echo "body {";
	$background_customize = 'site_background';;
	echo get_background_media_query($background_customize) . "}\n";
	echo "}\n";

	echo "</style>";
}

function search_highlight( $content, $search_keyword ) {

	$keys = implode( '|', explode(' ', $search_keyword ));
	$content = preg_replace( '/(' . $keys .')/iu', '<strong class="search-highlight">\0</strong>', $content);

	return $content;
}

/**
* Load default options when new site created
**/
add_action( 'init', 'set_default_site_options' );
function set_default_site_options() {
	//Get child theme's name
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );

	if ( false == get_option( $themename ) ) {
		$output = array();
		include_once( WP_CONTENT_DIR . '/themes/phoenix/options.php' );
		$theme_options = optionsframework_options();
		foreach ( $theme_options as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			$output[$option['id']] = $option['std'];
		}
		if ( isset( $output ) ) {
			add_option( $themename, $output );
		}
	}
}

add_filter( 'srm_max_redirects', 'dbx_srm_max_redirects' );
function dbx_srm_max_redirects() {
	return 500;
}

/*
 * Force URLs in srcset attributes into HTTPS scheme
 * Credit: https://wordpress.org/support/topic/responsive-images-src-url-is-https-srcset-url-is-http-no-images-loaded?replies=16
 */
function ssl_srcset( $sources ) {
	if ( is_ssl() ) {
		foreach ( $sources as &$source ) {
			$source['url'] = set_url_scheme( $source['url'], 'https' );
		}
	}
	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'ssl_srcset' );

function get_fav_icon() {
	$fav_icon_post_id = get_option( "site_icon" );
	$fav_icon_post = get_post( $fav_icon_post_id );
	$fav_icon_path = $fav_icon_post->guid;
	if ( true == is_ssl() ) {
		$fav_icon_path = str_replace( "http://", "https://", $fav_icon_path );
	}
	$favicon_tags = '<link rel="shortcut icon" href="' . $fav_icon_path . '" type="image/x-icon" />';

	return $favicon_tags;
}

function use_https_for_fav_icon( $url, $size, $blog_id ) {
	if ( true == is_ssl() ) {
		return str_replace( "http://", "https://", $url );
	}
	return $url;
}
add_filter( "get_site_icon_url", "use_https_for_fav_icon", 10, 3 );

/**
 * Update Admin Email in Theme Customize and then update in General Settings too.
 * Avoid to send email to confirm after changed admin email.
 */
add_action( 'customize_save_after', 'update_admin_email' );
function update_admin_email() {
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace( "/\W/", "_", strtolower( $themename ) );

	$admin_email_option = get_option($themename)['admin_email'];
	$admin_email_db = get_option('admin_email');

	if ( $admin_email_option != $admin_email_db ) {
		update_option( 'admin_email', $admin_email_option );
		delete_option( 'adminhash' );
		delete_option( 'new_admin_email' );
	}
}

/**
 * Hide Yoast SEO Ads in WP Admin.
 */
add_action( 'admin_head', 'hide_yoast_seo_ads');
function hide_yoast_seo_ads() {
	if ( is_plugin_active ( $plugin = 'wordpress-seo/wp-seo.php') ) {
		echo "<style>";
		echo ".wpseo_content_wrapper #sidebar-container.wpseo_content_cell, ";
		echo "li#toplevel_page_wpseo_dashboard ul.wp-submenu.wp-submenu-wrap li:last-child";
		echo "{ display: none; }";
		echo "</style>\n";
	}
}

/**
 * Allow attributes for HTML tag when saved
 */
add_filter( 'init', 'pheonix_allow_attributes_for_html');
function pheonix_allow_attributes_for_html() {
	global $allowedposttags;
	$extra_attributes = array(
		'data-toggle'        => true,
		'data-target'        => true,
		'data-dismiss'       => true,
		'data-spy'           => true,
		'data-placement'     => true,
		'data-container'     => true,
		'data-content'       => true,
		'data-loading-text'  => true,
		'data-complete-text' => true,
		'data-parent'        => true,
		'data-offset-bottom' => true,
		'data-offset-top'    => true,
		'title'              => true,
		'tabindex'           => true,
		'rel'                => true,
		'role'               => true,
		'autocomplete'       => true,
		'id'                 => true,
	);
	$video_attributes = array(
		'data-vide-bg'      => true,
		'data-vide-options' => true,
	);
	$allowedposttags['section'] = array_merge($allowedposttags['section'], $video_attributes);
	$allowedposttags['button']  = array_merge($allowedposttags['button'], $extra_attributes);
	$allowedposttags['div']     = array_merge($allowedposttags['div'], $extra_attributes);
	$allowedposttags['a']       = array_merge($allowedposttags['a'], $extra_attributes);
	$allowedposttags['span']    = array_merge($allowedposttags['span'], $extra_attributes);
	$allowedposttags['iframe']  = array(
		'id'          => true,
		'width'       => true,
		'height'      => true,
		'frameborder' => true,
		'border'      => true,
		'src'         => true,
		'class'       => true
	);
	$allowedposttags['script'] = array(
		'src'  => true,
		'type' => true
	);
	$allowedposttags['link'] = array(
		'rel'  => true,
		'type' => true,
		'href' => true
	);
	$allowedposttags['style'] = array();
	$seo_attributes = array(
		'itemtype'  => true,
		'itemscope' => true,
		'itemprop'  => true,
	);
	$allowedposttags['div'] = array_merge($allowedposttags['div'], $seo_attributes);
	$allowedposttags['strong'] = array_merge($allowedposttags['strong'], $seo_attributes);
	$allowedposttags['input'] = array(
		'name'                         => true,
		'id'                           => true,
		'class'                        => true,
		'type'                         => true,
		'value'                        => true,
		'placeholder'                  => true,
		'required'                     => true,
		'data-fv-notempty'             => true,
		'data-fv-notempty-message'     => true,
		'data-fv-identical'            => true,
		'data-fv-identical-field'      => true,
		'data-fv-identical-message'    => true,
		'data-fv-emailaddress'         => true,
		'data-fv-stringlength'         => true,
		'data-fv-stringlength-min'     => true,
		'data-fv-stringlength-max'     => true,
		'data-fv-stringlength-message' => true
	);
}

/**
 * Prevent gform fields both required and hidden
 */
add_action( 'gform_admin_pre_render', 'check_gform_field_required_and_hidden' );
function check_gform_field_required_and_hidden( $form ) {
?>
	<script type="text/javascript">
	gform.addFilter( 'gform_pre_form_editor_save', 'check_form' );
	function check_form( form ) {
		for ( field of form.fields ) {
			if ( true == field.isRequired && ( 'hidden' == field.visibility || 'hidden' == field.cssClass ) ) {
				alert( <?php echo json_encode( "There's a field on this form that is required and hidden in the same field. Gform accepts only 'hidden' OR 'required' in the same field." ); ?> );
				wp_die();
			}
		}
		return form;
	}
	</script>
<?php
return $form;
}

/**
 * Discourage Search Engines Notification
 */
function display_discourage_search_engines_notification() {
	if ( 0 == get_option( 'blog_public' ) ) {
		echo '<div class="error"><h3>"Discourage search engines from indexing this site" is checked.</h3></div>';
	}
}
add_action( 'admin_notices', 'display_discourage_search_engines_notification' );

/**
 * Adding .svg extension
 */
function allow_svg_mime_types( $mime_types ) {
	$mime_types['svg'] = 'image/svg+xml';
	return $mime_types;
}
add_filter( 'upload_mimes', 'allow_svg_mime_types' );

/**
 * Custom robot tags
 */
function custom_no_robots() {
	remove_action( 'login_head', 'wp_no_robots' );
	echo '<meta name="robots" content="noindex, nofollow" />';
}
add_action( 'login_head', 'custom_no_robots', 9 );


/**
 * Remove FontAwesome File From Plugins which confict with our platform.
 */

function remove_fontawesome_css_from_quickiebarpro_plugin() {
	if ( is_plugin_active( 'quickiebar-pro/quickiebar-pro.php' ) ) {
		wp_dequeue_style( 'fontawesome' );
	}
}
add_action( 'wp_print_styles', 'remove_fontawesome_css_from_quickiebarpro_plugin', 99 );


function remove_fontawesome_css_from_wpnotificationbar_plugin() {
	if ( is_plugin_active( 'mts-wp-notification-bar/mts-wp-notification-bar.php' ) ) {
		wp_dequeue_style( 'fontawesome' );
	}
}
add_action( 'wp_print_styles', 'remove_fontawesome_css_from_wpnotificationbar_plugin', 99 );


/**
 * Change Yoast Settings to Noindex
 */
function change_yoast_settings_to_noindex() {
	$wpseo_options = get_option('wpseo_titles');

	// Search Appearance: Partner Manager
	if ( is_plugin_active( 'pronto-partners-manager/pronto-partner.php' ) ) {
		if ( ! $wpseo_options['noindex-tax-partner_group'] ) {
			$wpseo_options['noindex-tax-partner_group'] = true;
		}
		if ( ! $wpseo_options['noindex-partner'] ) {
			$wpseo_options['noindex-partner'] = true;
		}
		if ( ! $wpseo_options['noindex-ptarchive-partner'] ) {
			$wpseo_options['noindex-ptarchive-partner'] = true;
		}
	}

	// Search Appearance: Team Member
	if ( is_plugin_active( 'pronto-team-members-manager/pronto-team-member.php' ) ) {
		if ( ! $wpseo_options['noindex-tax-team_member_group'] ) {
			$wpseo_options['noindex-tax-team_member_group'] = true;
		}
		if ( ! $wpseo_options['noindex-team_member'] ) {
			$wpseo_options['noindex-team_member'] = true;
		}
		if ( ! $wpseo_options['noindex-ptarchive-team_member'] ) {
			$wpseo_options['noindex-ptarchive-team_member'] = true;
		}
	}

	// Search Appearance: Testimonials
	if ( is_plugin_active( 'pronto-testimonials-manager/pronto-testimonial.php' ) ) {
		if ( ! $wpseo_options['noindex-tax-testimonial_group'] ) {
			$wpseo_options['noindex-tax-testimonial_group'] = true;
		}
		if ( ! $wpseo_options['noindex-testimonial'] ) {
			$wpseo_options['noindex-testimonial'] = true;
		}
		if ( ! $wpseo_options['noindex-ptarchive-testimonial'] ) {
			$wpseo_options['noindex-ptarchive-testimonial'] = true;
		}
	}

	// Search Appearance: Taxonomies
	if ( ! $wpseo_options['noindex-tax-slide-page'] ) {
		$wpseo_options['noindex-tax-slide-page'] = true;
	}

	if ( ! $wpseo_options['noindex-tax-location_group'] ) {
		$wpseo_options['noindex-tax-location_group'] = true;
	}

	if ( ! $wpseo_options['noindex-tax-header_group'] ) {
		$wpseo_options['noindex-tax-header_group'] = true;
	}

	if ( ! $wpseo_options['noindex-tax-footer_group'] ) {
		$wpseo_options['noindex-tax-footer_group'] = true;
	}

	if ( ! $wpseo_options['noindex-tax-post_tag'] ) {
		$wpseo_options['noindex-tax-post_tag'] = true;
	}

	if ( ! $wpseo_options['noindex-tax-category'] ) {
		$wpseo_options['noindex-tax-category'] = true;
	}

	if ( ! $wpseo_options['noindex-author-wpseo'] ) {
		$wpseo_options['noindex-author-wpseo'] = true;
	}

	// Search Appearance: Media & attachment URLs
	if ( ! $wpseo_options['disable-attachment'] ) {
		$wpseo_options['disable-attachment'] = true;
	}

	update_option( 'wpseo_titles', $wpseo_options );
}
add_action( 'init', 'change_yoast_settings_to_noindex' );