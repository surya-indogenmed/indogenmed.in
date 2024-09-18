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
define( 'DB_NAME', 'u341032289_newwpdb2k24s11' );

/** Database username */
define( 'DB_USER', 'u341032289_nwpusrtvg311' );

/** Database password */
define( 'DB_PASSWORD', 'Z54ULw[s!' );

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
define( 'AUTH_KEY',         'D(@9jwW.V[vk($]O2 J0MvRb9)kXXTcXeh56`A8Nu>{bv0hkxYenc;-B~~6aJtlE' );
define( 'SECURE_AUTH_KEY',  '^^o)IGah1ZZa]3APj^l__-lxoVm-E!Id`4+(uB|SdqHA%zcqwFy(MjW?cMkdic$m' );
define( 'LOGGED_IN_KEY',    ':iR--~-EwZz: +o#xjSVhTea$bBp&U0l7L5$B6#UVlnGCxUQ:F)RU}H1LbM(d>@S' );
define( 'NONCE_KEY',        'rp)55f~Dz3X*eT9N{$Vq]uq&ntB1P5hDE]wsW] a6R1Whd.FJ_LR`qR~n.%@4a^O' );
define( 'AUTH_SALT',        'WWiD{x#]=m0O/&u9()<Gc_:b|]wvd&yDE|O5VN=^-wJvpSoP[d|N*%JCufo SY|4' );
define( 'SECURE_AUTH_SALT', 'VeCu LB@+-peSq87)+nTJK16Fr$.!8$wF((;XM-oigGiD!,WlT}$KEZ~~oTQE}Si' );
define( 'LOGGED_IN_SALT',   'g|1#C}sqP<|vOcw2|bk_|(AC=~WK R8aCNhwj@;98uQRfRKP+(BKwmgqWp`0kj2k' );
define( 'NONCE_SALT',       '09~Lg0z.)/KVMKkFHCo/Hq1}K*5XkNkxdude0KQ9ugA_Qi(<GKu9b$LHoXN=x~<(' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
