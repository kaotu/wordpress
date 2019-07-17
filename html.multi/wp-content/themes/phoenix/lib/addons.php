<?php
/**
 * Customize WooCommerce Template
 */

add_theme_support( 'woocommerce' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper',     10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );


add_action( 'woocommerce_before_main_content', 'woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content',  'woocommerce_wrapper_end',   10 );

function woocommerce_wrapper_start() {
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

function woocommerce_wrapper_end() {
    echo '</article>';
}

add_filter( 'woocommerce_show_page_title', 'woocommerce_show_page_title' );
function woocommerce_show_page_title() {
    if ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
        if( strpos($_SERVER['REQUEST_URI'], 'wp-activate.php') === false ){
            if ( is_shop() ) {
                $id = get_option( 'woocommerce_shop_page_id' );
            }
            elseif ( is_cart() ) {
                $id = get_option( 'woocommerce_cart_page_id' );
            }
            elseif ( is_checkout() ) {
                $id = get_option( 'woocommerce_checkout_page_id' );
            }
            elseif ( is_account_page() ) {
                $id = get_option( 'woocommerce_myaccount_page_id' );
            }
        }
    }

    $title = get_the_title( $id );

    $title_location = get_post_meta( $id, 'custom_title_location', true );
    $title_segment = get_post_meta( $id, 'custom_title_location', true );
    $title_spacing = get_post_meta( $id, 'custom_title_spacing', true );
    $title_custom_class = get_post_meta( $id, 'custom_title_custom_class', true );

    if ( $title_location == 'site' ) {
        //Get child theme's name
        $themename = get_option( 'stylesheet' );
        $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
        $site_options = get_option($themename);

        $page_title_option = $site_options['page_title_option'];

        if ( $page_title_option == 'default' ) {
            return '<h1>' . $title . '</h1>';
        }
    }
    elseif ( $title_location == 'default') {
        return '<h1>' . $title . '</h1>';
    }
}

/**
 * Load Jetpack compatibility file.
 */
require( get_template_directory() . '/inc/jetpack.php' );
