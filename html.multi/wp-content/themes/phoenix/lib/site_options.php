<?php

function get_featured_image_size() {
    //Get child theme's name
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option($themename);

    $style = $site_options['blog_style'];

    switch ( $style ) {
        case 'b':
            return 'featured-large';
        default:
            return 'featured-small';
    }
}

function get_masthead() {
    if ( is_page() ) {
        $id = get_the_ID();
    }
    elseif ( is_home() ) {
        $id = get_option( 'page_for_posts', true );
    }
    elseif ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
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
    else {
        return;
    }

    $enable_masthead_settings = get_post_meta( $id, 'custom_masthead_enable', true );
    if ( $enable_masthead_settings == 'Enable' ) {
        $masthead_content      = get_post_meta( $id, 'custom_masthead_content', true );
        $masthead_segment      = get_post_meta( $id, 'custom_masthead_background', true );

        $masthead_custom_class = get_post_meta( $id, 'custom_masthead_custom_class', true );
        if ( $masthead_segment == 'segment_custom' ) {
            $masthead_segment = 'segment';

            $masthead_background_color      = get_post_meta( $id, 'custom_masthead_color', true );
            $masthead_background_image      = get_post_meta( $id, 'custom_masthead_image', true );
            $masthead_background_repeat     = get_post_meta( $id, 'custom_masthead_background_repeat', true );
            $masthead_background_position   = get_post_meta( $id, 'custom_masthead_background_position', true );
            $masthead_background_attachment = get_post_meta( $id, 'custom_masthead_background_attachment', true );
            $masthead_background_size       = get_post_meta( $id, 'custom_masthead_background_size', true );

            if ( ! ( $masthead_background_color == '' && $masthead_background_image == '' ) ) {
                if ( $masthead_background_color != '' ) {
                    $background_color = 'background-color: ' . $masthead_background_color . '; ';
                }
                else {
                    $background_color = '';
                }
                if ( $masthead_background_image != '' ) {
                    $background_image      = 'background-image: url(\'' . $masthead_background_image . '\');';
                    $background_repeat     = ' background-repeat: ' . $masthead_background_repeat . ';';
                    $background_position   = ' background-position: ' . preg_replace( '/[\s_]/', ' ', $masthead_background_position ) . ';';
                    $background_attachment = ' background-attachment: ' . $masthead_background_attachment . ';';
                    if ( $masthead_background_size == 'auto' ) {
                        $background_size = '';
                    }
                    else {
                        $background_size = ' background-size: ' . $masthead_background_size  . ';';
                    }
                }
                else {
                    $background_image      = '';
                    $background_repeat     = '';
                    $background_position   = '';
                    $background_attachment = '';
                    $background_size       = '';
                }
                $masthead_style = 'style="' . $background_color . $background_image . $background_repeat . $background_position . $background_attachment . $background_size . '"';
            }
        }
        else {
            $masthead_style = '';
        }

        //Get child theme's name
        $themename = get_option( 'stylesheet' );
        $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
        $site_options = get_option($themename);

        $site_layout = $site_options['site_layout'];
        $segment_size = get_post_meta( $id, 'custom_masthead_spacing', true );
        $masthead_font_jumbotron = get_post_meta( $id, 'custom_masthead_font_jumbotron', true );
        if ( 'Use Jumbotron Styling' == $masthead_font_jumbotron ) {
            $jumboTron_class = ' jumbotron';
        }
        else {
            $jumboTron_class = '';
        }

        $render_segment_size = ' space-' . $segment_size;

        if ( $site_layout == 'fixed' ) {
            $render_container = ' container';
            $render_div_class = '';
        }
        else {
            $render_container = '';
            $render_div_class = ' class="container"';
        }
        if ( $masthead_custom_class ) {
            $masthead_custom_class = ' ' . $masthead_custom_class;
        }

        // Jumpdown option
        $jumpdown_enable    = get_post_meta( $id, 'custom_masthead_jumpdown_enable', true );
        $jumpdown_target    = get_post_meta( $id, 'custom_masthead_jumpdown_target', true );
        $jumpdown_duration  = get_post_meta( $id, 'custom_masthead_jumpdown_duration', true );
        $jumpdown_animation = get_post_meta( $id, 'custom_masthead_jumpdown_animation', true );

        $jumpdown_button    = '';

        $jumpdown_target_empty = '';
        if ( $jumpdown_enable == "Enable" ) {
            $jumpdown_button .= '<div class="footer-cta animate hidden-xs">';
            //$target                = "";
            //$jumpdown_target_empty = "";
            if ( '' != $jumpdown_target ) {
                $target = $jumpdown_target;
            } else {
                $target                = 'jumpdown-target';
                $jumpdown_target_empty = '<div id="jumpdown-target"><!--Jumpdown Target--></div>';
            }

            $jumpdown_button .= sprintf( '<a href="#%s" rel="jumpdown" class="halfCircle scroll" id="jumpdown" data-animation="%s" data-duration="%s">', $target, $jumpdown_animation, $jumpdown_duration );
            $jumpdown_button .= '<i class="fa fa-angle-down"></i>';
            $jumpdown_button .= '</a></div>';
            //$jumpdown_button .= $jumpdown_target_empty;
        }

        $masthead_html  = '<div class="' . $masthead_segment . $render_container . $render_segment_size . $jumboTron_class . $masthead_custom_class . '"' . $masthead_style . '>';
        $masthead_html .= '<div' . $render_div_class . '>';
        $masthead_html .= '<div class="row">';
        $masthead_html .= '<div class="col-md-12">';
        $masthead_html .= do_shortcode( $masthead_content );
        $masthead_html .= '</div></div></div>';
        $masthead_html .= $jumpdown_button;
        $masthead_html .= '</div>';
        $masthead_html .= $jumpdown_target_empty;

        return $masthead_html;
    }
}

function get_page_title( $location, $id ) {
    $title = get_the_title( $id );

    /* Get Options */
    $option       = get_post_meta( $id, 'custom_title_location', true );
    $custom_class = get_post_meta( $id, 'custom_title_custom_class', true );

    if ( $custom_class and $option != 'site' ) {
        $custom_class = ' ' . $custom_class;
    }
    else {
        $custom_class = '';
    }

    //Get child theme's name
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option($themename);

    $site_layout = $site_options['site_layout'];

    if ( $option == 'site' or $option == '' ) {
        $segment_style = $site_options['page_title_segment'];
        $spacing       = $site_options['page_title_spacing'];
        $option        = $site_options['page_title_option'];
    }
    else {
        $segment_style = get_post_meta( $id, 'custom_title_segment', true );
        $spacing       = get_post_meta( $id, 'custom_title_spacing', true );
    }

    /* Render HTML */
    if ( $option == 'segment' && $location == 'segment' ) {
        if ( $site_layout == 'fixed' ) {
            $open_page_title_tag = '<div class="' . $segment_style . $custom_class . ' space-' . $spacing . ' container"><div><h1>';
        }
        else {
            $open_page_title_tag = '<div class="' . $segment_style . $custom_class . ' space-' . $spacing . '"><div class="container"><h1>';
        }
        $close_page_title_tag = '</h1></div></div>';
        $page_title_segment   =  $open_page_title_tag . $title . $close_page_title_tag;

        return $page_title_segment;
    }
    elseif( $option == 'default' && $location == 'default' ) {
        return '<h1>' . $title . '</h1>';
    }

    return;
}

/**
* Get Sidebar Setting in Per Page Setting or Site Options.
*/
function get_sidebar_setting( $id ) {
    if ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
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

    $chosen_sidebar = get_post_meta( $id, 'custom_page_sidebar', true );

    //Get child theme's name
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option($themename);

    // If the search result page is being displayed,
    // use the default sidebar setting in site options.
    if ( $chosen_sidebar == 'default' or $chosen_sidebar == '' or is_search() ) {
        $chosen_sidebar = $site_options['default_sidebar'];
    }
    if ( 'default' !== $site_options['default_post_sidebar'] and NULL !== $site_options['default_post_sidebar'] ){
        if ( is_single( $id ) and 'post' === get_post_type( $id ) ) {
            $chosen_sidebar = $site_options['default_post_sidebar'];
        }
    }
    return $chosen_sidebar;
}

/**
* Get Header Group in Per Page Setting or Site Options.
*/
function get_header_group( $id ) {
    if ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
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

    $enable_header_setting = get_post_meta( $id, 'custom_header_group', true );
    if ( $enable_header_setting != 'default' and $enable_header_setting != '' ){
        $group = get_post_meta( $id, 'custom_header_group', true );
        $class = get_post_meta( $id, 'custom_header_additional_class', true );
        $chosen_header_group = array(
            'header_group'            => $group,
            'header_additional_class' => $class
        );
    }
    else {
        $chosen_header_group = get_option( 'header_group' );
    }
    return $chosen_header_group;
}

/**
* Get Footer Group in Per Page Setting or Site Options.
*/
function get_footer_group( $id ) {
    if ( is_plugin_active ( $plugin = 'woocommerce/woocommerce.php') ) {
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

    $enable_footer_setting = get_post_meta( $id, 'custom_footer_group', true );

    if ( $enable_footer_setting != 'default' and $enable_footer_setting != '' ) {
        $group = get_post_meta( $id, 'custom_footer_group', true );
        $class = get_post_meta( $id, 'custom_footer_additional_class', true );
        $chosen_footer_group = array(
            'footer_group'            => $group,
            'footer_additional_class' => $class
        );
    }
    else {
        $chosen_footer_group = get_option( 'footer_group' );
    }

    return $chosen_footer_group;
}

/**
 * Embedded Google Fonts in <head>
 */
function embed_google_fonts() {
    $embed_google_fonts = '';
    //Get child theme's name
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option($themename);

    $header_font = $site_options['headers_font_options'];
    $body_font   = $site_options['body_font_options'];

    $font_google_keys = array(
        '"Open Sans", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
        'Lato, sans-serif',
        '"Source Sans Pro", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
        '"Signika", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, Tahoma, sans-serif',
        '"Vollkorn", Georgia, serif',
        '"Lora", Georgia, serif'
    );
    $font_google = array(
        $font_google_keys[0] => 'Open Sans',
        $font_google_keys[1] => 'Lato',
        $font_google_keys[2] => 'Source Sans Pro',
        $font_google_keys[3] => 'Signika',
        $font_google_keys[4] => 'Vollkorn',
        $font_google_keys[5] => 'Lora'
    );

    if ( $header_font ) {
        if ( array_key_exists( $header_font, $font_google ) ) {
            $h_font = str_replace( ' ', '+', $font_google[ $header_font ] );
            if ( '' != $h_font ) {
                $embed_google_fonts  = '<link href="//fonts.googleapis.com/css?family=' . $h_font . '" rel="stylesheet" type="text/css">';
                $embed_google_fonts .= "\n";
            }
        }
    }

    if ( $body_font ) {
        if ( array_key_exists( $body_font, $font_google ) ) {
            $b_font = str_replace( ' ', '+', $font_google[ $body_font ] );
            if ( '' != $b_font ) {
                if ( $h_font != $b_font ) {
                    $embed_google_fonts .= '<link href="//fonts.googleapis.com/css?family=' . $b_font . '" rel="stylesheet" type="text/css">';
                    $embed_google_fonts .= "\n";
                }
            }
        }
    }

    return $embed_google_fonts;
}
