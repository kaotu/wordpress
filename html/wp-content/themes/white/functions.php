<?php

if( ! function_exists('setup_script')):
function setup_script(){
 //bootstrap
 wp_enqueue_style( 'wp_theme-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css' );

  // Add main theme stylesheet
  wp_enqueue_style( 'wp_theme-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'setup_script' );

endif;

 ?>
