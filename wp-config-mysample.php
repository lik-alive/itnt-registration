<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

$env = parse_ini_file('.env');

//Protection
error_reporting(0);
@ini_set('display_errors', 0);

/** Easy Chair access */
define('EC_LOGIN', $env['EC_LOGIN']);
define('EC_PASSWORD', $env['EC_PASSWORD']);

/** Mail access */
define('MAIL_FROM_NAME', $env['MAIL_FROM_NAME']);
define('MAIL_SEC_ADDRESS', $env['MAIL_SEC_ADDRESS']);
define('MAIL_SEC_PASSWORD', $env['MAIL_SEC_PASSWORD']);
define('MAIL_TECH_ADDRESS', $env['MAIL_TECH_ADDRESS']);
define('MAIL_TECH_PASSWORD', $env['MAIL_TECH_PASSWORD']);
define('MAIL_IMAP_PATH', $env['MAIL_IMAP_PATH']);

define('WP_SITEURL', $env['WP_SITEURL']);
define('WP_HOME', $env['WP_SITEURL']);

/** Add subfolder to all paths */
if (isset($env['BASE_URL'])) $_SERVER['REQUEST_URI'] = $env['BASE_URL'] . $_SERVER['REQUEST_URI'];

/** Define schema for proxy_pass https->http */
if (strpos($env['WP_SITEURL'], 'https://') !== false) $_SERVER['HTTPS'] = 'on';

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $env['DB_NAME']);

/** MySQL database username */
define('DB_USER', $env['DB_USER']);

/** MySQL database password */
define('DB_PASSWORD', $env['DB_PASS']);

/** MySQL hostname */
define('DB_HOST', $env['DB_HOST']);

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'zi_';

/**
 * Default theme
 */
define('WP_DEFAULT_THEME', 'simplified');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
