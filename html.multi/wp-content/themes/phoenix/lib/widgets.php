<?php
/**
 * Register widgetized area and update sidebar with default widgets
 */
function phoenix_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'phoenix' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ) );
}

add_action( 'widgets_init', 'phoenix_widgets_init' );

function apply_class_to_widgets( $params ) {
    global $wp_registered_widgets;

    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[ $widget_id ];
    $widget_opt = get_option( $widget_obj['callback'][0]->option_name );
    $widget_num = $widget_obj['params'][0]['number'];

    if ( 'Search' == $params[0]['widget_name'] ) {
        $params[0]['before_widget'] = '<div class="widget clearfix search-widget">' ;
    }

    if ( array_key_exists( 'tag', $widget_opt[ $widget_num ] ) ) {
        if ( 1 == $widget_opt[ $widget_num ]['tag'] ) {
            if ( "Archives" == $params[0]['widget_name'] || "Categories" == $params[0]['widget_name'] ) {
                $params[0]['before_widget'] = '<div class="widget clearfix tag">';
            }
        }
    }

    return $params;
}

add_filter( 'dynamic_sidebar_params', 'apply_class_to_widgets' );

function load_extended_widgets() {
    require_once( get_template_directory() . '/lib/pronto-extended-widgets.php' );

    unregister_widget( "WP_Widget_Archives" );
    register_widget( "Pronto_Widget_Archives" );
    unregister_widget( "WP_Widget_Categories" );
    register_widget( "Pronto_Widget_Categories" );
}

add_action( 'widgets_init', 'load_extended_widgets' );
