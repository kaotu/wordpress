<?php
/**
 * The template for displaying search sesults pages.
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
        global $query_string;
        //undo filtering pages from search
        //query_posts( $query_string . '&post_type=post' );
        if( have_posts() ) : ?>
        <h1 class="page-title">
        <?php
            printf( __( 'Search Results for: %s', 'phoenix' ), '<span>' . get_search_query() . '</span>' );
        ?>
        </h1>
        <?php
            while ( have_posts() ) : the_post();
            //undo filtering pages from search
            //    if (is_search() && ($post->post_type=='page')) continue;
                get_template_part( 'content', 'search' );
            endwhile;

            // Reset Query
            wp_reset_query();

            pagination();
        else:
            if ( 'location' == $_GET['post_type'] ) {
                echo '<div class="row">';
                $form  = '<form id="searchform" role="search" class="form-inline" method="get" ' . 'action="' . esc_url( home_url( '/' ) ) . '"> <div class="form-group col-md-10 col-sm-8 col-xs-6">';
                $form .= '<input type="text" class="form-control" id="s" name="s" placeholder="Search Locations by City or Zipcode"';
                $form .= ' value="' . $_GET['s'] . '" /></div>';
                $form .= '<input type="hidden" name="post_type" value="location" />';
                $form .= '<button class="btn btn-primary" type="submit">Search Locations</button>';
                $form .= '</form>';
                echo $form;
                echo '</div>';
                echo '<div class="row">';
                echo '<div class="col-md-12">';
                echo apply_filters('location_not_found_result', '<h2>No Locations Found Within 100 Miles</h2><p>Try using a more specific search.</p>');
                echo '</div>';
                echo '</div>';
            }
            else {
                get_template_part( 'no-results', 'search' );
            }
        endif;
    ?>
</article>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
