<?php

/*
Plugin Name: Gallery Lightbox Lite
Plugin URI: http://www.ghozylab.com/plugins/
Description: Displays your images gallery into the awesome and responsive lightbox slider with very easy
Author: GhozyLab, Inc.
Text Domain: gallery-lightbox-slider
Domain Path: /languages
Version: 1.0.0.29
Author URI: http://www.ghozylab.com/plugins/
*/


if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}


/*-------------------------------------------------------------------------------*/
/*   All DEFINES
/*-------------------------------------------------------------------------------*/
$glg_plugin_url = substr( plugin_dir_url( __FILE__ ), 0, -1 );
$glg_plugin_dir = substr( plugin_dir_path( __FILE__ ), 0, -1 );

define( 'GLG_ITEM_NAME', 'Gallery Lighbox Lite' );
define( 'GLG_VERSION', '1.0.0.29' );
define( 'GLG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GLG_URL', $glg_plugin_url );
define( 'GLG_DIR', $glg_plugin_dir );
define( 'GLG_PLUGIN_SLUG','gallery-lightbox-slider/gallery-lightbox-lite.php' );

/*-------------------------------------------------------------------------------*/
/*   Plugin Init
/*-------------------------------------------------------------------------------*/
add_action( 'init', 'glg_general_init' );

function glg_general_init() {
	
	// Make sure jQuery loaded on frontend
	if( !is_admin() ) {
		
		wp_enqueue_script( 'jquery' );
		
		}
	
	// Global
	load_plugin_textdomain( 'gallery-lightbox-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	include_once( GLG_PLUGIN_DIR . 'inc/functions/glg-functions.php' );
	
	// Only in Admin Area
	if ( is_admin() ) {
		
		add_action( 'admin_menu', 'glg_menu_page' );
		add_action( 'admin_enqueue_scripts', 'glg_admin_enqueue_scripts' );
		add_filter( 'plugin_action_links', 'glg_settings_link', 10, 2 );
		add_action("in_plugin_update_message-".GLG_PLUGIN_SLUG,"glg_plugin_update_changelogs");
		
		include_once( GLG_PLUGIN_DIR . 'inc/gfg-metabox.php' );
		include_once( GLG_PLUGIN_DIR . 'inc/settings/glg-global-settings.php' );
		include_once( GLG_PLUGIN_DIR . 'inc/functions/ajax/glg-admin-ajax.php' );
		
	}
	
	// Outside Admin Area
	if ( ! is_admin() ) {
		
		include_once( GLG_PLUGIN_DIR . 'inc/functions/ajax/glg-frontend-ajax.php' );
		
		add_action( 'wp_enqueue_scripts', 'glg_frontend_enqueue_scripts' );
		add_filter( 'the_content', 'glg_post_page_hook' );	
		
	}
	
}


/*-------------------------------------------------------------------------------*/
/*  Plugin Settings Link @since 1.0.0.15
/*-------------------------------------------------------------------------------*/
function glg_settings_link( $link, $file ) {
	
	static $this_plugin;
	
	if ( !$this_plugin )
		$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=gallery-lightbox-settings' ) . '"><span class="dashicons dashicons-admin-generic"></span>&nbsp;' . __( 'Settings', 'gallery-lightbox-slider' ) . '</a>';
		array_unshift( $link, $settings_link );
	}
	
	return $link;
}


/*-------------------------------------------------------------------------------*/
/*  Plugin Update ChangeLogs
/*-------------------------------------------------------------------------------*/
if( !function_exists( "glg_plugin_update_changelogs" ) ){
	
	function glg_plugin_update_changelogs( $args ){
		
		global $pagenow;
		
		if ( 'plugins.php' === $pagenow ){
			
		wp_enqueue_style( 'glg_update_styles', plugins_url('inc/css/update.css' , __FILE__ ) );
		
		$response = wp_remote_get( 'https://plugins.svn.wordpress.org/gallery-lightbox-slider/trunk/readme.txt' );
		
		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
			
			$matches        = null;
			$regexp         = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote( $args['Version'] ) . '\s*=|$)~Uis';
			$upgrade_notice = '';
			
			if ( preg_match( $regexp, $response['body'], $matches ) ) {
				$changelog = (array) preg_split( '~[\r\n]+~', trim( $matches[1] ) );
				$upgrade_notice .= '<p class="glg_update_changelogs_ttl">Changelog:</p>';
				$upgrade_notice .= '<div class="glg_update_changelogs">';
				foreach ( $changelog as $index => $line ) {

					if ( strpos( $line, '=' ) !== false ) {
						
						$line = preg_replace("/=/","Version ",$line, 1 );
						$line = str_replace("=","",$line );
						$upgrade_notice .= "<p class='glg_version_clttl'>".$line."</p>";
						
					}
						else {
							
							$upgrade_notice .= "<p><span class='dashicons dashicons-arrow-right'></span>".str_replace("*","",$line )."</p>";
							
						}

					}
					$upgrade_notice .= '</div> ';
					echo $upgrade_notice;
				}
			}
		}
	}
}


/*-------------------------------------------------------------------------------*/
/*   Redirect on Activation
/*-------------------------------------------------------------------------------*/
function glg_plugin_activate() {

  add_option( 'activated_glg_plugin', 'glg-activate' );

}
register_activation_hook( __FILE__, 'glg_plugin_activate' );

function glg_load_plugin() {

    if ( is_admin() && get_option( 'activated_glg_plugin' ) == 'glg-activate' ) {
		
		 delete_option( 'activated_glg_plugin' );
		 
		if ( !is_network_admin() ) {
			
			wp_redirect("admin.php?page=gallery-lightbox-settings");
			
			}
			
    }
	
}
add_action( 'admin_init', 'glg_load_plugin' );