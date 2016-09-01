<?php

/*
  Plugin Name: Amanzimtoti Volunteers and Benefactors User Information
  Plugin URI: http://amanzimtoti.netau.net/avbur-plugin
  Description: This displays or add the extra user information from/to the database.
  Version: 1.0.0
  Author: SC Simelane
  Author URI: http://amanzimtoti.netau.net/
 */

define( "AMANZIMTOTI_MEMBERS_PLUGIN_DIR", plugin_dir_path( __FILE__ ) );

require_once 'includes/amanzimtoti-member-shortcodes.php';

// Load external files
add_action( "wp", "amanzi_load_external_files" );
add_action( "wp_enqueue_scripts", "amanzi_plugin_styles");

function amanzi_plugin_styles() {
	wp_enqueue_style("amanzi_member_style", plugins_url( "/css/styles.css", __FILE__ ));
}

function amanzi_load_external_files() {
	
	wp_register_script( "handle-form-js", plugins_url( "/jscripts/handle-form.js", __FILE__ ), array( "jquery" ) );

	wp_enqueue_script( "jquery" );
	wp_enqueue_script( "handle-form-js" );

	/* global $post;
	  wp_localize_script("handle-form-js", "MemberForm", array(
	  "action"	=>	"create_member_form",
	  "pageId"	=>	$post->ID
	  )); */

	amanzi_member_post_action();
}

function amanzi_member_post_action() {
	global $wpdb;
	if ( !empty( $_POST ) && isset( $_POST["listaction"] ) ) {
		$listaction = $_POST["listaction"];
		switch ( $listaction ) {
			case "insert":
				amanzi_insert_additional_details();
				break;

			default:
				break;
		}
	}
}
