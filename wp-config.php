<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wwimpo_wp292' );

/** MySQL database username */
define( 'DB_USER', 'wwimpo_wp292' );

/** MySQL database password */
define( 'DB_PASSWORD', 'c[9@Spe5u3' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'eleyeighibzn7mdkdksdxtyufwrkmwna8alwakksqwo5hj2aquddesfeoddrohvp' );
define( 'SECURE_AUTH_KEY',  'zyz9reyvgwygtpumoder58ziew3hqkboz5iqp2vnkqlcspawcu1onkvvqw1bj3ir' );
define( 'LOGGED_IN_KEY',    'glepvkb9ayxjcf6shrcpmmngpkkxumc6hejryjdfiyi9ulwbvrx3gyonb39abjve' );
define( 'NONCE_KEY',        '4umh91tl4nyluik5fuckkuptm45rzx2dxc49mnprzcf89viisuymyfaw2vpmhiub' );
define( 'AUTH_SALT',        'ctaeciyklnd9ilgahfffzs9fb5emg2rsulkpszliy22qsvo7ifayd3nzr9u12duw' );
define( 'SECURE_AUTH_SALT', 'ncliktvkpmhbi0z434gahort4c5tvgy6wx5kn9zbu1pvotq4x6fnhzmremfawvwe' );
define( 'LOGGED_IN_SALT',   'gfy9bjlk2dy0blajiwhtuexb49v2rtxmdjzhfw0p6ymasrs3od3un1gm1kimg0xb' );
define( 'NONCE_SALT',       'k1uo5lbulqtegjuijad0hfa6mq7edaafaxk80inqcvi7nssin74s1qhsl8ltxjcv' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp35_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
