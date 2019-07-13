<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_name');
/** MySQL database username */
define( 'DB_USER', 'root_username');
/** MySQL database password */
define( 'DB_PASSWORD', 'root_password');
/** MySQL hostname */
define( 'DB_HOST', 'db');
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8');
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '');
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'f222a8d067ad19eb7ce1dc755e30c04ef53a40b1');
define( 'SECURE_AUTH_KEY',  '25f93e9cf6ab0559ab578671cd80d5968f42ea70');
define( 'LOGGED_IN_KEY',    '681062147a42e44631f56e14c68e49c494164a3c');
define( 'NONCE_KEY',        '6069d1052bf9aaf9e260ce7de8dc05817d26766e');
define( 'AUTH_SALT',        '39bb0b372c6d313509886c092ebc2bf9dc130ad9');
define( 'SECURE_AUTH_SALT', '6d8fe1cd57f9fb56bb604cc8224f114025d729b1');
define( 'LOGGED_IN_SALT',   'c215851164bf3127527cd416849091c05a3f6305');
define( 'NONCE_SALT',       '68b9ded6dd08cddded9a664df546a81d76ea8c09');
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
// If we're behind a proxy server and using HTTPS, we need to alert Wordpress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}
/* That's all, stop editing! Happy publishing. */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'phoenix_domain_name');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
define('WP_ALLOW_REPAIR', true);

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */

require_once( ABSPATH . 'wp-settings.php' );
