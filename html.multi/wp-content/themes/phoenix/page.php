<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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

if ( 'full-width' == get_post_meta( $id, 'custom_page_sidebar', true ) ) {
    echo '<article class="col-md-12">';
}
else {
    // Sidebar setting here is opposite to the sidebar setting in sidebar.php
    $sidebar_position = get_sidebar_setting( $id );

    if ( 'right' == $sidebar_position  ) {
        $sidebar_setting = 'push';
        echo '<article class="col-md-9 col-sm-8 col-md-' . $sidebar_setting . '-3 col-sm-' . $sidebar_setting . '-4">';
    }
    elseif ( 'left' == $sidebar_position ) {
        echo '<article class="col-md-9 col-sm-8">';
    }
    else {
        echo '<article class="col-md-12">';
    }
}

while ( have_posts() ) : the_post();
    get_template_part( 'content', 'page' );
    // If comments are open or we have at least one comment,
    // load up the comment template
    if ( comments_open() || '0' != get_comments_number() ) {
        comments_template();
    }
endwhile;

echo '</article>';

get_sidebar();
get_footer();
