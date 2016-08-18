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
define('DB_NAME', 'a3329320_amanzi');

/** MySQL database username */
define('DB_USER', 'a3329320_fant6x');

/** MySQL database password */
define('DB_PASSWORD', 'Fant6xIMY320DB');

/** MySQL hostname */
define('DB_HOST', 'mysql7.000webhost.com');

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
define('AUTH_KEY',         '[5{||m>a+T.;[6OR_6Xk|#Us>.@^%XfqH]YfXkd9 Kc5**lK3-%YOSf&ZCfTN%)h');
define('SECURE_AUTH_KEY',  '+-#5XshLbX!<?u<bqPl}i--rkjeEUyKO|N+#q(Jw[D.Myb?1uG/9`gl(r|%cU&|A');
define('LOGGED_IN_KEY',    'Ug<u{2LgtfNvyLg(vCo8$jLI^0Znxdm3x/Hi<(G/N>7a41_r1`5VJ8%wEP#-$OsL');
define('NONCE_KEY',        '.)r!$|,q&Q<^D+K:TS]4IzT%D:5bxe_9.9J<EN-Dk[rU|>Cg{8|sPC<=<<8h2r%M');
define('AUTH_SALT',        'rREg_{-pJFK_Pl*ni:]TKL:k>)c+iIc#[iYMQ`.qA(Yeu_8RfR0C$n*Wl`0qjIP$');
define('SECURE_AUTH_SALT', 'OXdz3M_-l/kBtzc20-LLlUh[e@9rrVgfK+uN&{LLPFy+BN/|(O*^fE~.2pB6nWSV');
define('LOGGED_IN_SALT',   'Z$GI?IX@s;PPTl+&=mX2| [xG$s3mh -Ho:KfV(g,,+-hPhfo;|$EE-?#4bsVml}');
define('NONCE_SALT',       'zz;+di4|=>I--yFbVF?zMI]tCcPIN13g`@G{TD}Nf]l{:Prx3wIyuoZTh.e9P=&@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'dbf6_';

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
