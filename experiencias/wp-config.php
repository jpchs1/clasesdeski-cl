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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wwimpo_experiencias' );

/** Database username */
define( 'DB_USER', 'wwimpo_experiencias' );

/** Database password */
define( 'DB_PASSWORD', 'wwimpo_experiencias' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'Xa,(:Q=WVzQq!gilshDiL;g,RG}NQwlv?Va@Qa-^rIk_y|=%zzJB`6.J<.u;[[p1' );
define( 'SECURE_AUTH_KEY',  'i0R(ZQI]87c8mpdX~n!8DyliW=J>E~d?<94B;yzmGy64<V7:9K(Pn3w}jn69G9b~' );
define( 'LOGGED_IN_KEY',    'OWX;Lw+(;=Bd*-eF%E@W*6WUh;]h?Msn3Rhq=@oD&oRaO-`MZNV`4L[!j?-K4W]?' );
define( 'NONCE_KEY',        'V4jYO-z8>]<o~;TXo&od#~qvE $!{rz[YN[5JN?Z>75aL30r{~Yg~n1Qm>qB_Z7c' );
define( 'AUTH_SALT',        'kRJ-&?/=>A%.,B{f!>1|p.m@#|WU5:f|MYC {vA7w ea,=0Di75jPi<hFK`5~bL~' );
define( 'SECURE_AUTH_SALT', '?H2f^CS8kF?1F##qrKifs<DR/F2MSvPQ<?< gG&K)V]kC],qBAnJ[OFNCHdLk!;z' );
define( 'LOGGED_IN_SALT',   ';FR@t2>om*0kiF0PFQ@}4}|xQl-o]wxh#T-[/@)4^pyR`}:<>A%rOpaV5F{g$fuS' );
define( 'NONCE_SALT',       'rXDybH6^-,#*:]Uc1-5XZoPqI~@2^l%-kqyMl4RlkraJtNZ}6v3<}YH%/ssbRch@' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
