<?php

//// Staging
//$_SERVER = array(
    //'HTTP_HOST'      => 'bypronto.com',
    //'SERVER_NAME'    => 'bypronto-staging',
    //'REQUEST_URI'    => '/',
    //'REQUEST_METHOD' => 'GET'
//);

//// Production
//$_SERVER = array(
    //'HTTP_HOST'      => 'bypronto.com',
    //'SERVER_NAME'    => 'Bypronto-ZoneC-HHVM',
    //'REQUEST_URI'    => '/',
    //'REQUEST_METHOD' => 'GET'
//);

//// Dev
//$_SERVER = array(
    //'HTTP_HOST'      => 'dev.bypronto.com',
    //'SERVER_NAME'    => 'bypronto-dev',
    //'REQUEST_URI'    => '/',
    //'REQUEST_METHOD' => 'GET'
//);

// Local
$_SERVER = array(
    'HTTP_HOST'      => 'local.bypronto.dev',
    'SERVER_NAME'    => 'local.bypronto.dev',
    'REQUEST_URI'    => '/',
    'REQUEST_METHOD' => 'GET'
);

// Stating and Production
require_once( '/var/www/bypronto/wp-load.php' );

// Dev
//require_once( '/home/deployer/current/wp-load.php' );

// Local
require_once( '/srv/www/wordpress-develop/bypronto/wp-load.php' );

function get_ms_options( $blog_id, $option_name ) {
    global $wpdb;

    $select_statement = "SELECT * FROM `" . DB_NAME . "`.`" . $wpdb->get_blog_prefix( $blog_id ) . "options` WHERE `option_name` LIKE '" . $option_name . "'";
    $sql = $wpdb->prepare( $select_statement, '' );

    $option_value = $wpdb->get_results( $sql, ARRAY_A );

    return $option_value[0]['option_value'];
}

function list_sites_that_have_active( $desired_plugin ){
    $args = array(
        'archived' => false,
        'limit'    => null
    );
    $sites = wp_get_sites( $args );

    foreach ( $sites as $site ) {
        $siteurl = get_ms_options( $site['blog_id'], 'siteurl' );
        $active_plugins = unserialize( get_ms_options( $site['blog_id'], 'active_plugins' ) );

        foreach ( $active_plugins as $plugin ) {
            if ( $plugin == $desired_plugin ) {
                var_dump( $siteurl );
                break;
            }
        }
    }
}

list_sites_that_have_active( 'woocommerce/woocommerce.php' );
