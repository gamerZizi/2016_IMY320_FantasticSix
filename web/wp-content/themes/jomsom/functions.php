<?php
/**
 * Functions and definitions
 *
 * Sets up the theme using core jomsom-core and provides some helper functions using jomsom-custon-functions.
 * Others are attached to action and
 * filter hooks in WordPress to change core functionality
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

//define theme version
if ( !defined( 'JOMSOM_THEME_VERSION' ) ) {
	$theme_data = wp_get_theme();

	define ( 'JOMSOM_THEME_VERSION', $theme_data->get( 'Version' ) );
}

/**
 * Implement the core functions
 */
require get_template_directory() . '/inc/jomsom-core.php';