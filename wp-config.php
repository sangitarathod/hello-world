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
define('DB_NAME', 'woocommerce_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'ac3r');

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
define('AUTH_KEY',         'dF6W2#`^xK~z5$~A#UsZ!S^PcTmoGH<X)|aiu`2@5lqKI928Bwj-9}?eDS<=#zf6');
define('SECURE_AUTH_KEY',  'y<W8lV<-gsLxC/^&Xnw8`ncTPI=Lk%N!,fjS$sjm^vr~qyUVe75s.>F?yH^0+F;g');
define('LOGGED_IN_KEY',    '~shc/7za]0)o-xa,Nv2Hyks}WGyx9&>pqVKR{Z|lDeQ&QJgV+-Tug,aZ%l~`8FhU');
define('NONCE_KEY',        '+22 t|(BGn64g[NY=ch%=_;{*r&7<8JGvbp:#*6!oD_R{S@R4{(Y_f0%V.{/u?n~');
define('AUTH_SALT',        '2G<@4dmDxKaWn<r=7^a:$A?FHnv<cGZq*Zj,ZxdMs!?h.uJ+|sHuNpGJ8}X^_V.L');
define('SECURE_AUTH_SALT', '^c:8t{L*`=.H1&Q#d-Hs{Di3C@0k {9MnY@!|45zfEcidPhw@]Z d2UxfHLXrua!');
define('LOGGED_IN_SALT',   'YdlPcgn6AZQPo;SsC? ,wDhC&fbfMQ{n=58s;Qv@?28BJ9<8sp,{wh=bk25}Q-rt');
define('NONCE_SALT',       'pH32SJ2B,e?2!K.8v%9:0e[z?Ss+}i}E[S5VVdPF?%TMa5!m7LgG$/O1MX*4X;-S');

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
define('FS_METHOD','direct');
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
