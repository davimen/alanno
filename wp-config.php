<?php
/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'toanprint_web');

/** MySQL database username */
define('DB_USER', 'toanprint_web');

/** MySQL database password */
define('DB_PASSWORD', 'WkGbFgtdi');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'hv}wi,_c7q&+NN[$fx}S(AR+6lx[7|QY!Ioap[?<PV|Z~z0vYw5|1n 136g7w<F|');
define('SECURE_AUTH_KEY',  '{z$87`uETlNkd?~c6c52o])xn%QQCG7$W2cUise]+]o&9+qexIA:]~/aO*:p0B_W');
define('LOGGED_IN_KEY',    'a.P(v}Q[PzJ-lcfxBqpw/h8{|FdG-Fd,.q><>%n}asL1pKd_TI5!YH]S6y+yv1u*');
define('NONCE_KEY',        '?/2n*Dz(]cI]8)bvjon*g{P+;fqF6.h7pfu(YX+sJ>`+tP ?Cn-#3[q/,[r(aA!)');
define('AUTH_SALT',        'j7W-Iw /udy=NJ@%D<>r:XU6>-I4Kff(&WvmRP}mRw5L*U)8GQZhgg,[R)H#UY+w');
define('SECURE_AUTH_SALT', '}AS?~c]w(u54f1>DkF,|1S v@>Ou]BGqGZ{}rd>thK>Ss]tsx wVu]@21bOx0%<{');
define('LOGGED_IN_SALT',   'i|M<!U5nS-tR?MghOi4uyE-j>V)^Vi7RWeNr2;-[O/yz>C(9S2lLBZe!s~X{ -UZ');
define('NONCE_SALT',       'j^h96y8?D+xmLT(aQip,7LT3Ve|Iy.oE|?]qW$who6(=I3N% ^lZVk~axhU|B<-%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
