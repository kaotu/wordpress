<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Phoenix
 */

if ( is_page() ) {
    $id = get_the_ID();
}
elseif ( is_home() ) {
    $id = get_option( 'page_for_posts', true );
}

echo get_page_title( 'default', $id );

the_content();

wp_link_pages( array( 
    'before' => '<div class="page-links">' . __( 'Pages:', 'phoenix' ), 
    'after'  => '</div>' 
) );

edit_post_link( 
    __( 'Edit', 'phoenix' ), 
    '<footer class="entry-meta"><span class="edit-link">', 
    '</span></footer>' 
); 
?>
