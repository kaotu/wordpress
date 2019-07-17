<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Phoenix
 */

get_header();

if ( is_page() ) {
	$id = get_the_ID();
}
elseif ( is_home() ) {
	$id = get_option( 'page_for_posts', true );
}

// Sidebar setting here is opposite to the sidebar setting in sidebar.php
$sidebar_position = get_sidebar_setting( $id );
$themename        = get_option( 'stylesheet' );
$themename        = preg_replace( "/\W/", "_", strtolower( $themename ) );
$site_options     = get_option( $themename );

$filter_key  = array( 'blog_filter_except_category', 'blog_filter_except_tag', 'blog_filter_only_category', 'blog_filter_only_tag' );
$filter_data = array();

$new_query = false;
foreach ( $filter_key as $key ) {
	$option = $site_options[ $key ];
	if ( NULL != $option ) {
		$new_query = true;
		$option_list = explode( ',', $option );
		$list_data = array();
		$term_type = explode( '_', $key )[3];
		if ( 'tag' == $term_type ) {
			$term_type = 'post_tag';
		}
		foreach ( $option_list as $opt ) {
			$obj = get_term_by( 'slug', $opt, $term_type );
			array_push( $list_data, intval( $obj->term_id ) );
		}
		$filter_data[ $key ] = $list_data;
	}
}
$blog_style = $site_options['blog_style'];

if ( 'c' == $blog_style ) {
	echo '<div id="toc" class="col-md-12">';
} else {
	if ( 'right' == $sidebar_position ) {
		$sidebar_setting = 'push';
		echo '<div id="toc" class="col-md-9 col-sm-8 col-md-' . $sidebar_setting . '-3 col-sm-' . $sidebar_setting . '-4">';
	} elseif ( 'left' == $sidebar_position ) {
		echo '<div id="toc" class="col-md-9 col-sm-8">';
	} else {
		echo '<div id="toc" class="col-md-12">';
	}
}

if ( $new_query ) {
	global $wp_query;
	$paged = ( get_query_var('paged') ) ? get_query_var( 'paged' ) : 1;
	$args = array(
		'category__in'     => $filter_data['blog_filter_only_category'],
		'category__not_in' => $filter_data['blog_filter_except_category'],
		'tag__in'          => $filter_data['blog_filter_only_tag'],
		'tag__not_in'      => $filter_data['blog_filter_except_tag'],
		'paged'            => $paged,
	);
	$wp_query = new WP_Query( $args );
}

echo get_page_title( 'default', $id );

if ( 'c' == $blog_style ) {
	$categories = get_categories();
	$columns = 3;

	echo '<article class="col-md-12">';
	echo '<div class="blog-tab-design-c">';
	echo '<ul class="nav nav-tabs category-tab">';
	render_nav_bar_from_category_for_blog_style_c( $categories );
	echo '</ul>';
	echo '<div class="clearfix visible-xs visible-sm"></div>';
	echo '<div class="tab-content">';
	render_posts_from_category_for_blog_style_c( $columns );
	echo '</div>';
	echo '</div>';
	pagination();
	echo '</article>';
	wp_reset_query();
} else {
	while ( have_posts() ) : the_post();
		get_template_part( 'content', get_post_format() );
	endwhile;
}

function render_nav_bar_from_category_for_blog_style_c ( $categories ) {
	$page_for_posts_url = get_permalink( get_option( 'page_for_posts' ) );
	if ( ! $_GET['category'] || 'all' === $_GET['category'] ) {
		echo '<li class="active"><a href="' . $page_for_posts_url . '?category=all">All</a></li>';
	} else {
		echo '<li><a href="' . $page_for_posts_url . '?category=all">All</a></li>';
	}

	foreach ( $categories as $category ) {
		if ( $category->slug === $_GET['category'] ) {
			echo '<li class="active"><a href="' . $page_for_posts_url . '?category=' . $category->slug . '">' .  $category->name . '</a></li>';
		} else {
			echo '<li><a href="' . $page_for_posts_url . '?category=' . $category->slug . '">' .  $category->name . '</a></li>';
		}
	}

}

function render_posts_from_category_for_blog_style_c( $columns ) {
	if ( $_GET['category'] && 'all' != $_GET['category'] ) {
		echo '<div class="tab-pane active" id="' . $_GET['category'] . '">';
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'paged' => $paged,
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $_GET['category'],
				),
			),
		);
		query_posts( $args );
	} else {
		echo '<div class="tab-pane active" id="all">';
	}

	while ( have_posts() ) : the_post();
		get_template_part( 'content', get_post_format() );
	endwhile;

	echo '</div>';
}

if ( 'c' != $blog_style ) {
	if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
		$jetpack_active_modules = get_option( 'jetpack_active_modules' );
		if ( NULL == $jetpack_active_modules or ! in_array( 'infinite-scroll', $jetpack_active_modules ) ) {
			pagination();
		}
	} else {
		pagination();
	}
}

?>
</div>
<?php
if ( 'c' != $blog_style ) {
	get_sidebar();
}
get_footer();
?>
