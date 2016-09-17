<?php
    global $wpdb;
    if (!defined('WPLANG') || WPLANG == '') {
        define('SCS_WPLANG', 'en_GB');
    } else {
        define('SCS_WPLANG', WPLANG);
    }
    if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

    define('SCS_PLUG_NAME', basename(dirname(__FILE__)));
    define('SCS_DIR', WP_PLUGIN_DIR. DS. SCS_PLUG_NAME. DS);
    define('SCS_TPL_DIR', SCS_DIR. 'tpl'. DS);
    define('SCS_CLASSES_DIR', SCS_DIR. 'classes'. DS);
    define('SCS_TABLES_DIR', SCS_CLASSES_DIR. 'tables'. DS);
	define('SCS_HELPERS_DIR', SCS_CLASSES_DIR. 'helpers'. DS);
    define('SCS_LANG_DIR', SCS_DIR. 'lang'. DS);
    define('SCS_IMG_DIR', SCS_DIR. 'img'. DS);
    define('SCS_TEMPLATES_DIR', SCS_DIR. 'templates'. DS);
    define('SCS_MODULES_DIR', SCS_DIR. 'modules'. DS);
    define('SCS_FILES_DIR', SCS_DIR. 'files'. DS);
	define('SCS_JS_DIR', SCS_DIR. 'js'. DS);
    define('SCS_ADMIN_DIR', ABSPATH. 'wp-admin'. DS);

	define('SCS_PLUGINS_URL', plugins_url());
    define('SCS_SITE_URL', get_bloginfo('wpurl'). '/');
    define('SCS_JS_PATH', SCS_PLUGINS_URL. '/'. SCS_PLUG_NAME. '/js/');
    define('SCS_CSS_PATH', SCS_PLUGINS_URL. '/'. SCS_PLUG_NAME. '/css/');
    define('SCS_IMG_PATH', SCS_PLUGINS_URL. '/'. SCS_PLUG_NAME. '/img/');
    define('SCS_MODULES_PATH', SCS_PLUGINS_URL. '/'. SCS_PLUG_NAME. '/modules/');
    define('SCS_TEMPLATES_PATH', SCS_PLUGINS_URL. '/'. SCS_PLUG_NAME. '/templates/');
    
    define('SCS_URL', SCS_SITE_URL);

    define('SCS_LOADER_IMG', SCS_IMG_PATH. 'loading.gif');
	define('SCS_TIME_FORMAT', 'H:i:s');
    define('SCS_DATE_DL', '/');
    define('SCS_DATE_FORMAT', 'm/d/Y');
    define('SCS_DATE_FORMAT_HIS', 'm/d/Y ('. SCS_TIME_FORMAT. ')');
    define('SCS_DATE_FORMAT_JS', 'mm/dd/yy');
    define('SCS_DATE_FORMAT_CONVERT', '%m/%d/%Y');
    define('SCS_WPDB_PREF', $wpdb->prefix);
    define('SCS_DB_PREF', 'scs_');
    define('SCS_MAIN_FILE', 'scs.php');

    define('SCS_DEFAULT', 'default');
    define('SCS_CURRENT', 'current');
	
	define('SCS_EOL', "\n");    
    
    define('SCS_PLUGIN_INSTALLED', true);
    define('SCS_VERSION', '1.2.6');
    define('SCS_USER', 'user');
    
    define('SCS_CLASS_PREFIX', 'scsc');     
    define('SCS_FREE_VERSION', false);
	define('SCS_TEST_MODE', true);
    
    define('SCS_SUCCESS', 'Success');
    define('SCS_FAILED', 'Failed');
	define('SCS_ERRORS', 'scsErrors');
	
	define('SCS_ADMIN',	'admin');
	define('SCS_LOGGED','logged');
	define('SCS_GUEST',	'guest');
	
	define('SCS_ALL',		'all');
	
	define('SCS_METHODS',		'methods');
	define('SCS_USERLEVELS',	'userlevels');
	/**
	 * Framework instance code, unused for now
	 */
	define('SCS_CODE', 'scs');

	define('SCS_LANG_CODE', 'scs_lng');
	/**
	 * Plugin name
	 */
	define('SCS_WP_PLUGIN_NAME', 'Coming Soon Supsystic');
	/**
	 * Custom defined for plugin
	 */
	define('SCS_COMMON', 'common');
	define('SCS_FB_LIKE', 'fb_like');
	define('SCS_VIDEO', 'video');
	
	define('SCS_HOME_PAGE_ID', 0);
	/**
	 * Our product name
	 */
	define('SCS_OUR_NAME', 'Coming Soon');
	/**
	 * Shortcode name
	 */
	define('SCS_SHORTCODE', 'supsystic-coming-soon');
