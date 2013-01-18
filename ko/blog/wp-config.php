<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ko');

/** MySQL database username */
define('DB_USER', 'ko');

/** MySQL database password */
define('DB_PASSWORD', 'cult1905');

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
define('AUTH_KEY',         '8d7[Mt2&o*/)|&+%q[>+7LL&M}==u4O(a%2LWTs.omN]Sp73o!*zQgQ(s#$*>clG');
define('SECURE_AUTH_KEY',  ';srG*kP0<d~gXm?-5+%)N-O;yjM/uF=e g :10.8]fH1i`:.[vQhJpjz^?)HY3R!');
define('LOGGED_IN_KEY',    'A)6+sLQ[OG8dyJ2?t|:D ;5og qn]nq+tH]#jFs4IH?Y:j4<I0-G!#&b)z^Ao+3z');
define('NONCE_KEY',        'F_S}NwV#(0mH-wgtxR}{6YX0Y13b=tHqK8WWrc_~|Fuk,Hr:i-L%~gJaSN:3Jbl-');
define('AUTH_SALT',        'VCjQjZ1/WatJhm`[&_/J3-AtYd0VS~Q(!etULennkd7^])JlQMVncEX(ItZ^qZ.;');
define('SECURE_AUTH_SALT', 'B3e;w(eNW,+6a0315u&7,~n$(/B2Y 4=r,ivtX@iZWMSzgJVEsv)s%=G~{Igc|ot');
define('LOGGED_IN_SALT',   ';uxdMzk4qx+eVdK/,NbYh-VL.UUc<A^FA+2Jt1QCW|6m-U*vJ~[K_M6laqS=@Jm{');
define('NONCE_SALT',       'foT`H{2gS5Xk~z:dUDa3J_++-M2K;Q-gTZ3I2&MH>hq+%RkTsvyDc!*DTTa&b+xJ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
