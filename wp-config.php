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
define('DB_NAME', 'chapinometro');

/** MySQL database username */
define('DB_USER', 'rodrigo');

/** MySQL database password */
define('DB_PASSWORD', 'fnrY!/3{"7j.fK*!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '0GEymiAhc`x^F}S;$I?qAI)ZX^!Jts`e-1#SZdx5=_e^M.9gt7FtQM,RL1`P0oEC');
define('SECURE_AUTH_KEY',  '=zKbwC)hO3#@4PR4X(0|~be+^blycvw9&|O|l$A3hJ]cBW8FbT>Z:(1||2>,+Lu9');
define('LOGGED_IN_KEY',    'UQ|PIVxS]ckD!<oMVy(Tw[4z!ynExLR#u5fm]3Q![?A2yYXd45_hH85.}qc]&O!`');
define('NONCE_KEY',        'W]j35xtTCX?^)2ce:F0~TlWOfX)VPl)P_-Jcux8rug>:9m5,q+LzT:{%!njhB+|;');
define('AUTH_SALT',        ';L{2v?PC7}wor3bsZ^U|=!FF8?O%HL.N^]8Z-:XH>~mRP_Pb:$i%[tb&:f]_i>Xt');
define('SECURE_AUTH_SALT', '[Dab1i:?VL<Db.%a)$9UC/J^{XJQ3UlZ5&@AO+Mw^nq+j@tbCsq-f*oD!QmGKq|i');
define('LOGGED_IN_SALT',   '{2p<7d9H]n+|`E-p[;Aav0Ek/0II73H8-:goyp1m+|[[Fw0s!&;uuiDm~s+P$UHf');
define('NONCE_SALT',       '4q|9]8(w1gK 7<$OWHffES8W&-Gvj&WiC~|Im4:$~_pi|hFc+FYD?-Jd#J<DLAZy');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ch_';

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
