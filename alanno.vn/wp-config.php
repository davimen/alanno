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
define('DB_NAME', 'toanprint_alonadev');

/** MySQL database username */
define('DB_USER', 'toanprint_user'); 

/** MySQL database password */
define('DB_PASSWORD', 'GHBxi27iF7');

/** MySQL hostname */
define('DB_HOST', '103.27.60.132');

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
define('AUTH_KEY',         'rhmBZNaoWd:f.])$V7aucdId9c*d#4F}Lyr>5N<S(xF3cPPkcZMmru>2X]r#mj|/');
define('SECURE_AUTH_KEY',  'Lv[f>SW$GrYWOk-cfz-9!pj{$QEu~Qn  6C]-#l^SkCtd|**YD4K}Zu{#Z=z6]H|');
define('LOGGED_IN_KEY',    '}(}fR:w87ZYFz]2|O=LQd.+g#+U_LQPV@!]YXlq@|mc.NXU_6#4N~b/<>C4;1bfu');
define('NONCE_KEY',        'wu9mX7b3#h)RbmmTL7@292nXnQ#kh]+Xr++SKbv$,-E=]p>L2?$$dyFa0Jh%q.vZ');
define('AUTH_SALT',        '&iC]6j9N2FFjv@?AOUk4pm0$,P X{+nJl[XK*JPDgeHX|NS2KIp3o5P3dt4 =Rz:');
define('SECURE_AUTH_SALT', 'h$9wE5:z$kb^}3wL2]cK@iQ>;0[dwbXb-06KDHOzX6+zagsd2)?r$~LN[Si*0$~5');
define('LOGGED_IN_SALT',   '$o?8WvwZ5a|9O? tWW4jF!z$TAu JYOw|9*^S&{iAwvXAFeL9@i?BJ/7b`(P,6;b');
define('NONCE_SALT',       'sVm{y8eBLHvx8|0<F_v&7I>Aig T={4XC)IZahc!d2g0`6Rn^RPRiFv,}J+lv<hJ');

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
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
