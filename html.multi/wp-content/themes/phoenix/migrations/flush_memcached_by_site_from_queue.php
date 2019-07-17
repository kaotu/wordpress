<?php
/*
 * Credentials need to be located at /var/www/.aws/credentials
 * It depends on who is execute PHP
 * Key looks like this:
 * [default]
 * aws_access_key_id = ..
 * aws_secret_access_key = ..
 */

//// Staging
$_SERVER = array(
    'HTTP_HOST'      => 'bypronto.com',
    'SERVER_NAME'    => 'bypronto-staging',
    'REQUEST_URI'    => '/',
    'REQUEST_METHOD' => 'GET'
);

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

// $_SERVER = array(
//     'HTTP_HOST'      => 'local.bypronto.dev',
//     'SERVER_NAME'    => 'local.bypronto.dev',
//     'REQUEST_URI'    => '/',
//     'REQUEST_METHOD' => 'GET'
// );

// Stating and Production
require_once( '/var/www/bypronto/web/wp-load.php' );

// Dev
//require_once( '/home/deployer/current/wp-load.php' );

// Local
// require_once( '/srv/www/wordpress-develop/web/wp-load.php' );

require_once(ABSPATH . "../lib/aws/aws-autoloader.php");
use Aws\Sqs\SqsClient;

function retrieve_keys( $server, $port = 11211 ) {
    $memcache = new Memcache();
    $memcache->connect( $server, $port );
    $list = array();
    $allSlabs = $memcache->getExtendedStats( 'slabs' );
    $items = $memcache->getExtendedStats( 'items' );
    foreach ( $allSlabs as $server => $slabs ) {
        foreach( $slabs as $slabId => $slabMeta ) {
        if ( !empty( $slabId ) ) {
            $cdump = $memcache->getExtendedStats( 'cachedump', (int) $slabId, 0 );
            foreach( $cdump as $keys => $arrVal ) {
                if ( !is_array( $arrVal ) ) continue;
                foreach( $arrVal as $k => $v ) {
                    $list[] = $k;
                }
            }
        }
        }
    }
        return $list;
}

function flush_memcached( $server, $current_blog_id ) {
       global $wp_object_cache;
       global $blog_id;
       $blog_id = 0;
       $cleared = 0;
       foreach ( array_keys( $wp_object_cache->mc ) as $name ) {
               $servers = $wp_object_cache->mc[ $name ]->getExtendedStats();
               foreach ( array_keys( $servers ) as $server ) {
                       $list = retrieve_keys( $server );
                       foreach ( $list as $item ) {
                               $parts = explode( ':', $item );
                               if ( is_numeric( $parts[0] ) ) {
                                   $blog_id = array_shift( $parts );
                                   $group = array_shift( $parts );
                               } else {
                                   $group = array_shift( $parts );
                                   $blog_id = 0;
                               }

                               if ( count( $parts ) > 1 ) {
                                   $key = join( ':', $parts );
                               } else {
                                   $key = $parts[0];
                               }
                               $group_key = $blog_id . $group;
                               if ( isset( $keymaps[$group_key] ) ) {
                                   $keymaps[$group_key][2][] = $key;
                               } else {
                                   $keymaps[$group_key] = array( $blog_id, $group, array( $key ) );
                               }
                       }
                       ksort( $keymaps );
                       foreach ( $keymaps as $group => $values ) {
                               list( $blog_id, $group, $keys ) = $values;
                               if($blog_id == $current_blog_id){
                                       foreach ( $keys as $key ) {
                                               $mc_key = $current_blog_id . ':' . $group . ':' . $key;
                                               $wp_object_cache->mc[ $name ]->delete( $mc_key );
                                               $cleared++;
                                       }
                               }
                       }
               }
       }
       return $cleared;
}

$client = SqsClient::factory(array(
        'region'  => 'eu-west-1',
        'version' => 'latest',
    ));

$queue = $client->createQueue(array('QueueName' => 'clear_memcached'));
$queue_url = $queue->get('QueueUrl');

$get_attribute = $client->getQueueAttributes(array(
    // QueueUrl is required
    'QueueUrl' => $queue_url,
    'AttributeNames' => array('ApproximateNumberOfMessages')
));

$all_messages_in_queue = intval($get_attribute['Attributes']['ApproximateNumberOfMessages']);

while( $all_messages_in_queue > 0 ){
    $result = $client->receiveMessage(array(
        'QueueUrl' => $queue_url
    ));

    $get_attribute = $client->getQueueAttributes(array(
        'QueueUrl' => $queue_url,
        'AttributeNames' => array('ApproximateNumberOfMessages')
    ));
    $all_messages_in_queue = intval($get_attribute['Attributes']['ApproximateNumberOfMessages']);

    if ($result['Messages'] == null &&  $all_messages_in_queue != 0) {
       continue;
    } elseif ( $result['Messages'] == null && $all_messages_in_queue == 0){
       break;
     }

    $message_handle = $result['Messages'][0]['ReceiptHandle'];
    $blog_id = intval($result['Messages'][0]['Body']);

    $server = explode(':', $memcached_servers['default'][0])[0];
    flush_memcached($server, $blog_id);

    $client->deleteMessage(array(
        'QueueUrl' => $queue_url,
        'ReceiptHandle' => $message_handle,
    ));
}
