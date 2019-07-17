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

if ( 'right' == $sidebar_position  ) {
    $sidebar_setting = 'push';
    echo '<article id="toc" class="col-md-9 col-sm-8 col-md-' . $sidebar_setting . '-3 col-sm-' . $sidebar_setting . '-4">';
}
elseif ( 'left' == $sidebar_position ) {
    echo '<article id="toc" class="col-md-9 col-sm-8">';
}
else {
    echo '<article id="toc" class="col-md-12">';
}
?>
    <?php
        while ( have_posts() ) : the_post();
            get_template_part( 'content', get_post_format() );
        endwhile;
    ?>
    <?php pagination(); ?>
</article>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
