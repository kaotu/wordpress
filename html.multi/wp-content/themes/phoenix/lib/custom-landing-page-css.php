<?php
/**
* Custom Landing Page CSS
**/
function custom_landing_page_css_func(){
    global $post;

    $current_page_id = NULL;
    if ( is_page() ) {
        $current_page_id = get_the_ID();
    }
    elseif ( is_home() ) {
        $current_page_id = get_option('page_for_posts', true);
    }
    elseif( 'landing_page' == $post->post_type and !is_search() ) {
        $current_page_id = $post->ID;
    }
    elseif ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
        if( strpos($_SERVER['REQUEST_URI'], 'wp-activate.php') === false){
            if ( is_shop() ) {
                $current_page_id = get_option( 'woocommerce_shop_page_id' );
            }
            elseif ( is_cart() ) {
                $current_page_id = get_option( 'woocommerce_cart_page_id' );
            }
            elseif ( is_checkout() ) {
                $current_page_id = get_option( 'woocommerce_checkout_page_id' );
            }
            elseif ( is_account_page() ) {
                $current_page_id = get_option( 'woocommerce_myaccount_page_id' );
            }
        }
    }
    $custom_landing_page_css = get_post_meta( $current_page_id, 'custom_landing_page_css', true );
    $custom_per_page_css = get_post_meta( $current_page_id, 'custom_per_page_css', true );
    if ( '' != $custom_per_page_css or '' != $custom_landing_page_css ) {
        echo '<!---Per Page CSS --->' . "\n";
        echo '<style type="text/css">' . "\n";
        if ( '' != $custom_per_page_css ) {
            echo $custom_per_page_css . "\n";
        }
        elseif ( '' != $custom_landing_page_css ) {
            echo $custom_landing_page_css . "\n";
        }
        echo '</style>' . "\n";
    }
}

add_action('custom_landing_page_css', 'custom_landing_page_css_func');
