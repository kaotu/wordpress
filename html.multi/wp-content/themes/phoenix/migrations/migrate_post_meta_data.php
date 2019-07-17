<?php

/**
* Update Post Meta Data option from Site Options to Theme Customizer
*/

//Staging
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
  "HTTP_HOST" => "local.bypronto.dev",
  "SERVER_NAME" => "local.bypronto.dev",
  "REQUEST_URI" => "/",
  "REQUEST_METHOD" => "GET"
);

// Load wordpress api.
//require_once('/vagrant/www/wordpress-develop/bypronto/wp-load.php');
require_once('/var/www/bypronto/wp-load.php');

$error_log_file = "/var/www/migrate_post_meta_data.log";
// $site_options = get_option('phoenix_child');

$count_all_sites = 0;
$count_failed = 0;
/* Get all sites */
$sites = wp_get_sites( array(
    'archived' => 0,
    'limit' => null
) );

foreach ( $sites as $site ) {
	if ( $site['blog_id'] != 1 ) {
		$sql_get_options = "select * from wp_{$site['blog_id']}_options where option_name = 'phoenix_child'";
		$results = $wpdb->get_row( $sql_get_options );
		sleep(1);
		$site_options = unserialize( $results->option_value );

		if ( $site_options['post_meta_data']['date'] == "0" ) {
		    $site_options['post_meta_data']['date'] = false;
		}
		if ( $site_options['post_meta_data']['author'] == "0" ) {
		    $site_options['post_meta_data']['author'] = false;
		}
		if ( $site_options['post_meta_data']['categories'] == "0" ) {
		    $site_options['post_meta_data']['categories'] = false;
		}
		if ( $site_options['post_meta_data']['tags'] == "0" ) {
		    $site_options['post_meta_data']['tags'] = false;
		}

		$site_options['post_meta_date'] = $site_options['post_meta_data']['date'];
		$site_options['post_meta_author'] = $site_options['post_meta_data']['author'];
		$site_options['post_meta_categories'] = $site_options['post_meta_data']['categories'];
		$site_options['post_meta_tags'] = $site_options['post_meta_data']['tags'];
		$options = serialize( $site_options );
		try {
			$update_post_meta = "update wp_{$site['blog_id']}_options set option_value = '{$options}' where option_name = 'phoenix_child'";
			$updated = $wpdb->query( $update_post_meta );
			error_log( "Success to update for Blog ID: {$site['blog_id']}\n", 3, $error_log_file );
			sleep(1);
			$count_all_sites++;
		} catch ( Exception $e ) {
			error_log( "Failed for Blog ID: {$site['blog_id']}\n", 3, $error_log_file );
            error_log( "$e\n", 3, $error_log_file );
            $count_failed++;
		}
	}
}
error_log( "Migrated for sites: {$count_all_sites}\n" );
error_log( "Failed: {$count_failed}\n" );
