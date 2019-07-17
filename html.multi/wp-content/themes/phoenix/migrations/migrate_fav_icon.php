<?php

// Staging
// $_SERVER = array(
//   "HTTP_HOST" => "bypronto.com",
//   "SERVER_NAME" => "bypronto-staging",
//   "REQUEST_URI" => "/",
//   "REQUEST_METHOD" => "GET"
// );

// Production
// $_SERVER = array(
//   "HTTP_HOST" => "bypronto.com",
//   "SERVER_NAME" => "Bypronto-ZoneC-HHVM",
//   "REQUEST_URI" => "/",
//   "REQUEST_METHOD" => "GET"
// );

// Local
$_SERVER = array(
    'HTTP_HOST'      => 'local.bypronto.dev',
    'SERVER_NAME'    => 'local.bypronto.dev',
    'REQUEST_URI'    => '/',
    'REQUEST_METHOD' => 'GET'
);

// Load wordpress api.
// require_once('/vagrant/www/wordpress-develop/bypronto/wp-load.php');
require_once('/var/www/bypronto/wp-load.php');

/* Get all sites */
$sites = wp_get_sites( array(
    'archived' => 0,
    'limit' => null
) );
$count_fav_icon = 0;
$count_no_fav_icon = 0;
$count_failed = 0;
$error_log_file = "/var/www/migrate_fav_icon.log";

foreach ( $sites as $site ) {
    // var_dump($site['blog_id']);
    if ( $site['blog_id'] != 1 ) {
        $sql_get_options = "select * from wp_{$site['blog_id']}_options where option_name = 'phoenix_child'";
        $results = $wpdb->get_row( $sql_get_options );
        sleep(1);
        $site_options = unserialize( $results->option_value );

        $fav_icon_src = $site_options[ 'fav_icon' ];
        if ( $fav_icon_src ) {
            $check_protocol = strpos( $fav_icon_src, '://' );
            if ( $check_protocol == false ) {
                $blog_details = get_site( $site['blog_id'] );
                $network_domain = $blog_details->domain;
                $fav_icon_path = 'http://' . $network_domain . $fav_icon_src;
            } else {
                $fav_icon_path = $fav_icon_src;
            }

            $sql_get_fav_icon_path = "select * from wp_{$site['blog_id']}_posts where guid = '{$fav_icon_path}'";
            $post_id = (int)$wpdb->get_var( $sql_get_fav_icon_path );

            $get_site_icon = "select * from wp_{$site['blog_id']}_options where option_name = 'site_icon'";
            $site_icon_row = $wpdb->get_row( $get_site_icon );

            try{
                if ( is_null( $site_icon_row ) ) {
                    $insert_site_icon = "insert into wp_{$site['blog_id']}_options values (NULL, 'site_icon', $post_id, 'yes')";
                    $inserted = $wpdb->query( $insert_site_icon );
                    error_log( "Success to insert for Blog ID: {$site['blog_id']} ", 3, $error_log_file );
                    error_log( "with favicon path: {$fav_icon_path}\n", 3, $error_log_file );
                } else {
                    $update_site_icon = "update wp_{$site['blog_id']}_options set option_value = '{$post_id}' where option_name = 'site_icon'";
                    $updated = $wpdb->query( $update_site_icon );
                    error_log( "Success to update for Blog ID: {$site['blog_id']} ", 3, $error_log_file );
                    error_log( "with favicon path: {$fav_icon_path}\n", 3, $error_log_file  );
                }
                sleep(1);
                $count_fav_icon++;
            } catch ( Exception $e ) {
                error_log( "Failed for Blog ID: {$site['blog_id']}\n", 3, $error_log_file );
                error_log( "$e\n", 3, $error_log_file );
                $count_failed++;
            }
        }
        else {
        	error_log( "Blog ID: {$site['blog_id']} no favicon\n", 3, $error_log_file );
            $count_no_fav_icon++;
        }
    }
}
error_log( "Migrated for sites: {$count_fav_icon}\n", 3, $error_log_file );
error_log( "No Favicon: {$count_no_fav_icon}\n" );
error_log( "Failed: {$count_failed}\n" );
