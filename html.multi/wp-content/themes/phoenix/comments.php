<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to phoenix_comment() which is
 * located in the inc/template-tags.php file.
 *
 * @package Phoenix
 */

    /*
     * If the current post is protected by a password and
     * the visitor has not yet entered the password we will
     * return early without loading the comments.
     */
    if ( post_password_required() ) {
        return;
    }
?>

    <div class="comments">
    <?php // You can start editing here -- including this comment! ?>
    <?php if ( have_comments() ) : ?>
        <h3 class="postcomment"><?php comments_number('No Comment', '1 Comment', '% Comments'); ?></h3>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
        <nav id="comment-nav-above" class="navigation-comment" role="navigation">
            <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'phoenix' ); ?></h1>
            <div class="previous"><?php previous_comments_link( __( '&larr; Older Comments', 'phoenix' ) ); ?></div>
            <div class="next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'phoenix' ) ); ?></div>
        </nav><!-- #comment-nav-before -->
        <?php endif; // check for comment navigation ?>

        <ol class="commentlist unstyled">
            <?php
                /* Loop through and list the comments. Tell wp_list_comments()
                 * to use phoenix_comment() to format the comments.
                 * If you want to overload this in a child theme then you can
                 * define phoenix_comment() and that will be used instead.
                 * See phoenix_comment() in inc/template-tags.php for more.
                 */

                // Define path ro require file pronto-comment-walker.php
                if ( ! defined( 'WP_PHOENIX_DIR' ) ) {
                    define( 'WP_PHOENIX_DIR', WP_CONTENT_DIR . '/themes/phoenix' );
                }
                require_once WP_PHOENIX_DIR . '/lib/pronto-comment-walker.php';

                // Declare $walker to use in wp_list_comments()
                $walker = new ProntoCustomCommentWalker();
                wp_list_comments(array( 
                    'callback' => 'phoenix_comment', 
                    'walker'   => $walker 
                ));
            ?>
        </ol><!-- .comment-list -->

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
        <nav id="comment-nav-below" class="navigation-comment" role="navigation">
            <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'phoenix' ); ?></h1>
            <div class="previous"><?php previous_comments_link( __( '&larr; Older Comments', 'phoenix' ) ); ?></div>
            <div class="next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'phoenix' ) ); ?></div>
        </nav><!-- #comment-nav-below -->
        <?php endif; // check for comment navigation ?>

    <?php endif; // have_comments() ?>

    <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'phoenix' ); ?></p>
    <?php endif; ?>

    <?php if ( comments_open() ): ?>
    <div class="respond">
        <h3 id="comment-form-title"><?php comment_form_title( __("Leave a comment!","phoenix"), __("Leave a comment to","phoenix") . ' %s' ); ?>
        <small><?php cancel_comment_reply_link( __("Cancel Reply","phoenix") ); ?></small>
        </h3>
        <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
              <div class="help">
                  <p><?php _e("You must be","phoenix"); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e("logged in","phoenix"); ?></a> <?php _e("to post a comment","phoenix"); ?>.</p>
              </div>
        <?php else : ?>
            <div class="row content">
                <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="col-md-12 form-horizontal">
                <p class="comment-notes">All fields marked with an asterisk<span class="required">*</span> are required.</p>
                <?php if ( $user_ID ) : ?>
                    <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
                <?php else : ?>
                    <div class="form-group margin-0">
                        <div class="col-md-6">
                            <input type="text" name="author" id="author" class="form-control margin-bottom-20" title="Name" value="<?php echo $comment_author; ?>" placeholder="Name<?php if ($req) echo "*"; ?>" />
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="email" id="email" class="form-control margin-bottom-20" title="Email" value="<?php echo $comment_author_email; ?>" placeholder="Email<?php if ($req) echo "*"; ?>" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="comment" id="comment-message" class="form-control" title="Comment" cols="45" rows="5" placeholder="Comment<?php if ($req) echo "*"; ?>"></textarea>
                    </div>
                </div>
                <p>
                    <input name="submit" type="submit" id="submit" class="btn btn-primary" value="Post Comment" />
                    <?php comment_id_fields(); ?>
                </p>
                <?php do_action('comment_form()', $post->ID); ?>
                </form>
            </div>
        <?php endif; ?>
    </div> <!-- .respond -->
    <?php endif; ?>
</div><!-- .comments -->
