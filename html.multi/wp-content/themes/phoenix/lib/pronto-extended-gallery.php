<?php

/*
 * Override the WP gallery shortcode.
 * Clean it up and make it follow Bootstrap 3.
 * Credit: https://github.com/roots/roots/blob/master/lib/gallery.php
 */

// In order to use is_plugin_active outside the admin pages, we need to include this file.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_action( 'wp_enqueue_media', 'custom_media_gallery' );
function custom_media_gallery(){
    add_action( 'admin_print_footer_scripts', 'remove_columns_option');
}

function remove_columns_option() {
?>
    <script type="text/javascript">
        jQuery(function(){
            if(wp.media.view.Settings.Gallery){
                wp.media.view.Settings.Gallery = wp.media.view.Settings.extend({
                    className: "gallery-settings",
                    template: wp.media.template("gallery-settings"),
                    render: function() {
                        wp.media.View.prototype.render.apply( this, arguments );
                        this.$('select.columns > option[value="1"]').remove();
                        this.$('select.columns > option[value="5"]').remove();
                        this.$('select.columns > option[value="7"]').remove();
                        this.$('select.columns > option[value="8"]').remove();
                        this.$('select.columns > option[value="9"]').remove();
                        // Select the correct values.
                        _( this.model.attributes ).chain().keys().each( this.update, this );
                        return this;
                    }
                });
            }
        });
    </script>';
<?php
}

function pronto_gallery( $output, $attr ) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
        if ( empty( $attr['orderby'] ) ) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby']);
        
        if ( ! $attr['orderby'] ) {
            unset( $attr['orderby'] );
        }
    }

    extract( shortcode_atts( array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => '',
        'icontag'    => '',
        'captiontag' => '',
        'columns'    => 4,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => 'file'
    ), $attr ) );

    $id = intval( $id );

    if ( 'RAND' == $order ) {
        $orderby = 'none';
    }
    
    if ( 3 == $columns ) {
        $grid = 'col-xs-4 col-sm-4 col-md-4'; 
    }
    elseif ( 2 == $columns or 3 == $columns or 6 == $columns ) {
        $sm = 12 / $columns;
        $md = 12 / $columns;
        $grid = 'col-xs-6 col-sm-' . $sm . ' col-md-' . $md; 
    }
    else {
        $columns = 4;
        $grid = 'col-xs-6 col-sm-3 col-md-3'; 
    }

    

    if ( ! empty( $include ) ) {
        $_attachments = get_posts( array( 
            'include'        => $include, 
            'post_status'    => 'inherit', 
            'post_type'      => 'attachment', 
            'post_mime_type' => 'image', 
            'order'          => $order, 
            'orderby'        => $orderby
        ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[ $val->ID ] = $_attachments[ $key ];
        }
    } 
    elseif ( ! empty( $exclude ) ) {
        $attachments = get_children( array( 
            'post_parent'    => $id, 
            'exclude'        => $exclude, 
            'post_status'    => 'inherit', 
            'post_type'      => 'attachment', 
            'post_mime_type' => 'image', 
            'order'          => $order, 
            'orderby'        => $orderby 
        ) );
    } 
    else {
        $attachments = get_children( array( 
            'post_parent'    => $id, 
            'post_status'    => 'inherit', 
            'post_type'      => 'attachment', 
            'post_mime_type' => 'image', 
            'order'          => $order, 
            'orderby'        => $orderby
        ) );
    }

    if ( empty( $attachments ) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = '\n';
        foreach ( $attachments as $att_id => $attachment ) {
            $output .= wp_get_attachment_link( $att_id, $size, true ) . '\n';
        }

        return $output;
    }

    $unique = ( get_query_var( 'page' ) ) ? $instance . '-p' . get_query_var( 'page' ): $instance;
    $output = '<div class="gallery gallery-' . $id . '-' . $unique . ' popup-gallery">';

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $image = ( 'file' == $link ) ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );

        $output .= ( $i % $columns == 0  ) ? '<div class="row gallery-row">': '';
        $output .= '<div class="' . $grid .'">' . $image;

        if ( trim( $attachment->post_excerpt ) ) {
            $output .= '<div class="caption hidden">' . wptexturize( $attachment->post_excerpt ) . '</div>';
        }

        $output .= '</div>';
        $i++;
        $output .= ( $i % $columns == 0 ) ? '</div>' : '';
    }

    $output .= ( $i % $columns != 0 ) ? '</div>' : '';
    $output .= '</div>';

    return $output;
}

function extend_gallery_shortcode() {
    // To remove that inline <style> tag
    add_filter( 'use_default_gallery_style', '__return_null' );

    // To hook into the WP gallery shortcode
    add_filter( 'post_gallery', 'pronto_gallery', 10, 2 );

    /*
     * Add class="thumbnail img-thumbnail" to attachment items
     */
    function pronto_attachment_link_class( $html ) {
        $post_id = get_the_ID();
        $html    = str_replace( '<a', '<a class="thumbnail img-thumbnail"', $html );
        
        return $html;
    }

    add_filter( 'wp_get_attachment_link', 'pronto_attachment_link_class', 10, 1 );
}

if ( is_plugin_active( 'jetpack/jetpack.php' ) or is_plugin_active( 'pronto-toolbox/pronto-toolbox.php' ) ) {
    $jetpack_active_modules = get_option( 'jetpack_active_modules' );

    if ( NULL == $jetpack_active_modules or ! in_array( 'tiled-gallery', $jetpack_active_modules ) ) {
        extend_gallery_shortcode();
    }
}
elseif ( ! is_plugin_active( 'jetpack/jetpack.php' ) and ! is_plugin_active( 'pronto-toolbox/pronto-toolbox.php' ) ) {
    extend_gallery_shortcode();
}
?>