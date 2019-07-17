<?php

// Locahost
$db_name = 'bypronto';
$conn = mysqli_connect('127.0.0.1', 'root', 'root', $db_name);

if (!$conn) {
   die('Could not connect: ' . mysqli_error());
}

echo 'Connected successfully';

$blog_ids = array();
$sql_get_sites = "select * from wp_blogs";
$sites = mysqli_query($conn, $sql_get_sites);
while ( $row = $sites->fetch_assoc() ) {
    array_push( $blog_ids, $row );
}

foreach( $blog_ids as $blog_id ) {
	$blog_id = $blog_id["blog_id"];
	if ( $blog_id != "1" ) {
		$sql_show_token = "select * from wp_{$blog_id}_options where option_name = 'show_iphoenix_token'";
		// $sql_show_token = "select * from wp_options where option_name = 'show_iphoenix_token'";
		$result = mysqli_query($conn, $sql_show_token);
		$result = array( $result->fetch_assoc() );
		$show_token = $result[0]["option_value"];
		var_dump($show_token);
		if ( $show_token == "true" ) {
			$sql_update = "update wp_{$blog_id}_options set option_value = 'false' where option_name = 'show_iphoenix_token'";
			$updated = mysqli_query($conn, $sql_update);
		}
	}
}

$conn->close();