<?php
/**
 * Enqueue scripts and styles
 */
function phoenix_scripts() {
    wp_enqueue_script(
        'skip-link-focus-fix',
        get_template_directory_uri() . '/js/skip-link-focus-fix.js',
        array(),
        '20130115',
        true
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    if ( is_singular() && wp_attachment_is_image() ) {
        wp_enqueue_script(
            'keyboard-image-navigation',
            get_template_directory_uri() .
            '/js/keyboard-image-navigation.js',
            array( 'jquery' ),
            '20120202'
        );
    }
}

add_action( 'wp_enqueue_scripts', 'phoenix_scripts' );