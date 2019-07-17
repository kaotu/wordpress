<?php
// Locahost
$db_name = 'bypronto';
$conn = mysqli_connect('127.0.0.1', 'root', 'root', $db_name);

if (!$conn) {
   die('Could not connect: ' . mysqli_error());
}
echo 'Connected successfully';

$blogs = array();
$tokens = array();

$sql_get_sites = "select * from wp_blogs";
$sites = mysqli_query($conn, $sql_get_sites);
while ( $row = $sites->fetch_assoc() ) {
    array_push( $blogs, $row );
}

$iphoenix_client_plugin_path = 'iphoenix-client/iphoenix-client.php';
foreach( $blogs as $blog ) {
	if ( $blog["archived"] != 1 and $blog["blog_id"] != 1 ) {
		$blog_id = $blog["blog_id"];

		$sql_get_active_plugins = "select * from wp_{$blog_id}_options where option_name = 'active_plugins'";
		$get_active_plugins = mysqli_query($conn, $sql_get_active_plugins);
		$active_plugins = array( $get_active_plugins->fetch_assoc() );
		foreach( $active_plugins as $plugin ) {
			if( strpos($plugin["option_value"], $iphoenix_client_plugin_path ) ) { 
				$sql_get_token = "select * from wp_{$blog_id}_options where option_name = 'iphoenix_client_token'";
				$data = mysqli_query($conn, $sql_get_token);
				while ( $row = $data->fetch_assoc() ) {
					$iphoenix_token = $row["option_value"];
					$token_data = $blog_id . "," . $iphoenix_token;
					var_dump($token_data);
					array_push( $tokens, $token_data );
				}
			}		
		}
		sleep(2);
	}
}

$file = fopen('iphoenix_tokens.csv', 'w');
fputcsv($file, array('site_id', 'iphoenix_client_token'));

$token_array = array( $tokens );

foreach ( $tokens as $line ) {
	$val = explode("," ,$line);
	fputcsv($file, $val);
}
fclose($file);

$conn->close();
