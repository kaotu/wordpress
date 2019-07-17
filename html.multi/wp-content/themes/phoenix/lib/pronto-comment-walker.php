<?php
/*
 *  Pronto Custom Comment Walker
 *  Credit: http://core.trac.wordpress.org/browser/tags/3.6/wp-includes/comment-template.php#L0
 *  Credit: http://shinraholdings.com/621/custom-walker-to-extend-the-walker_comment-class/
 */

class ProntoCustomCommentWalker extends Walker_Comment {
    // init classwide variables
    var $tree_type = 'comment';
    var $db_fields = array(
        'parent' => 'comment_parent', 
        'id'     => 'comment_ID' 
    );

    // Start_lvl
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; 
        echo '<ul>';
    }
}
