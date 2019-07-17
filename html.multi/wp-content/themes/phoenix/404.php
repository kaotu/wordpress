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
    <header class="entry-header">
        <h1 class="entry-title"><?php _e( 'Well this is somewhat embarrassing, isn\'t it?', 'phoenix' ); ?></h1>
    </header>
    <div class="entry-content">
        <p>
            It seems we can't find what you're looking for. Perhaps searching can help.
        </p>
        <?php
            get_search_form();
        ?>
    </div>
</article>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
