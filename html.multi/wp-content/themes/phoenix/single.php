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
    echo '<div id="toc" class="col-md-9 col-sm-8 col-md-' . $sidebar_setting . '-3 col-sm-' . $sidebar_setting . '-4">';
}
elseif ( 'left' == $sidebar_position ) {
    echo '<div id="toc" class="col-md-9 col-sm-8">';
}
else {
    echo '<div id="toc" class="col-md-12">';
}
?>
    <div class="well well-blog">
        <?php
            while ( have_posts() ) : the_post();
                get_template_part( 'content-single', get_post_format() );
                if (esc_attr( get_the_author_meta( 'author_box', $user->ID )) == "yes")
                    include '_pronto-author-bio.php';
                if( comments_open() || '0' != get_comments_number() ) {
                    comments_template();
                }
            endwhile;
        ?>
        <div class="additional">
            <ul class="pager">
                <?php previous_post_link( '<li class="previous">%link</li>', '&larr; Older' ); ?>
                <?php next_post_link( '<li class="next">%link</li>','Next &rarr;' ); ?>
            </ul>
        </div>
    </div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
