<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'asdfasdfasdfasfsdfasdfasdfasdfasdfasdf');

/** MySQL database username */
define('DB_USER', 'asfdasdfasdfasdfasdfasdfas');

/** MySQL database password */
define('DB_PASSWORD', 'asdfasdfasdfasdfasdasdf1234214121234');

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
define('AUTH_KEY',         'J<H~T]!_r>JY4-4(r|V1fUZM#{ceb`iFOQ-|NV4DdzrHfg<!16u+xLGPc2,2WI5r');
define('SECURE_AUTH_KEY',  'sb%[Ed[Gqz<=a0)1w)?`R1P|IFFe7Q6#2w^^hy<3^N~m68-0oFp B^{5kRe^n72t');
define('LOGGED_IN_KEY',    'Rt/QR+]&apHA[zB?5}H|_tFVHgMOPk m4JB:1$Xm<3uh60@2`:L?>$~_:T8qu6#Q');
define('NONCE_KEY',        '<LJcp/+37V9|l:o_r0  Fs;#by0Rat&E=W2aPFsL2;/g]iz9&TU*<9aHNrS4SAK>');
define('AUTH_SALT',        '!/kU:IL0vksy!;;Yma.Y?U&Weg_C-jB.nxz|W.%/sFsq,OA | c:S12LH6PJ3O,w');
define('SECURE_AUTH_SALT', 'Vqqn 0Z$C$`Qz^o18O{!qJ;I-VE/&|NL[S!YjwxzgA|H&4WMk&SJh|>(0BV{t{8J');
define('LOGGED_IN_SALT',   'INk*rH42-+`a[JvW9#KI#}/eza{,KL+-|NNi`O-~d`=sn[yl*NmKD>*#|Y0%.RY5');
define('NONCE_SALT',       '<?fCs8nEi?*C]C5I7Re#`Y+l|Jo(xl_ZXlfZ|Qd73^+.2<@suyO4Cj?J@W eM9<X');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'asdfasdfasdfasdfasdfasdfa_';

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
