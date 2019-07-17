<?php

/* Set Background of Segment Styles */
function get_background_styles($background_customize) {
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option( $themename );

    $background_customize_style = $site_options[$background_customize];

    $bg_css = '';
    if($background_customize_style['color']){
        $bg_css .= 'background-color: ' . $background_customize_style['color'] . ';';
    }

    if($background_customize_style['image']){

        $bg_css .= ' background-image: url(' . $background_customize_style['image'] . ');';

        if($background_customize_style['repeat']){
            $bg_css .= ' background-repeat: ' . $background_customize_style['repeat'] . ';';
        }
        if($background_customize_style['position']){
            $bg_css .= ' background-position: ' . $background_customize_style['position'] . ';';
        }
        if($background_customize_style['attachment']){
            $bg_css .= ' background-attachment: ' . $background_customize_style['attachment'] . ';';
        }
        if($background_customize_style['size'] && $background_customize_style['size'] != 'auto'){
            $bg_css .= ' background-size: ' . $background_customize_style['size'] . ';';
        }
    }
    return $bg_css;
}

/* Set Background for Mobile and Tablet screen */
function get_background_media_query($background_customize) {
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option( $themename );

    $background_customize_style = $site_options[$background_customize];

    $bg_media_css = '';
    if($background_customize_media['color']) {
        $bg_media_css .= 'background: ' . $background_customize_media['color'] . ' !important;' ;
    }

    return $bg_media_css;
}

function get_background_inner_page($background_customize_inner) {
    $themename = get_option( 'stylesheet' );
    $themename = preg_replace( "/\W/", "_", strtolower( $themename ) );
    $site_options = get_option( $themename );

    $background_inner_page_option = $site_options[$background_customize_inner];

    if ( $background_inner_page_option ) {
        $background_customize_inner_page = $site_options['site_background'];

        $bg_inner_page = '';
        if ( $background_customize_inner_page['color'] ) {
            $bg_inner_page .= 'background-color: ' . $background_customize_inner_page['color'] . ';';
        }

        if ( $background_customize_inner_page['image'] ) {
            $bg_inner_page .= ' background-image: url(' . $background_customize_inner_page['image'] . ');';

            if ( $background_customize_inner_page['repeat'] ) {
                $bg_inner_page .= ' background-repeat: ' . $background_customize_inner_page['repeat'] . ';';
            }
            if ( $background_customize_inner_page['position'] ) {
                $bg_inner_page .= ' background-position: ' . $background_customize_inner_page['position'] . ';';
            }
            if ( $background_customize_inner_page['attachment'] ) {
                $bg_inner_page .= ' background-attachment: ' . $background_customize_inner_page['attachment'] . ';';
            }
            if ( $background_customize_inner_page['size'] && $background_customize_inner_page['size'] != 'auto' ) {
                $bg_inner_page .= ' background-size: ' . $background_customize_inner_page['size'] . ';';
            }
        }
    }

    return $bg_inner_page;
}
