<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'testwork' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'Ceq}!5p9P@@i@QjoW!}V<;?U]mewgs[VUgdG^`]2X0a{78!)o&SW -w&Yvb)sYdI' );
define( 'SECURE_AUTH_KEY',  '%a]E]}xpKAyVIy/.!QzoLC]7H$%W S]>br;z@QXE6<s}z`<*!&)?*]WEyX]E|5?b' );
define( 'LOGGED_IN_KEY',    '=$1Tz%Zc):52c.4VQM$rh95fEfds.$7OR=s<>:c4Trovt!s-Pah?@HAl2$N]SE&r' );
define( 'NONCE_KEY',        'k?JVE= eW0$P<NZ3NGI6h,t/)s|=LrsR_[kl6X&,ybuQwDy4t^P XIl4GcEiJo0.' );
define( 'AUTH_SALT',        '0R($Id&s~2Vcfv,Cyj&--,8~ZC>PUi#l^qRJ(LgmY;c|ZMxcq2>:% x&;tt,^U3)' );
define( 'SECURE_AUTH_SALT', 'J`w32.X7V&#+lkWe*yB[:Ot^W>y]+F5`vetXJAB-2Aeh@pSxJ#W(#}Vo5w$$l=?E' );
define( 'LOGGED_IN_SALT',   'FZLG;OS?Vg<K8P}&$Nv!B4gtu``yb2/|AoYBs*7t7)9KH5t:b.wiFWJJjN*>M:;.' );
define( 'NONCE_SALT',       'HqS|VK:ClzvS{lYNSl|VSOqO) R{nDII?K8CpDdY}s0v&Zi$N2Q,R3Z3@alB|)x#' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );

define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);


/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
