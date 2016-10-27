<?php

/* 
 * Set up the Amanzi Mobile Menu
 */

add_action( "admin_menu", "amanzi_mobile_admin_menu" );

function amanzi_mobile_admin_menu() {
	add_menu_page(
		"Amanzimtoti Mobile Notifications", 
		"Mobile Notifications", 
		"manage_options", 
		"amanzimtoti_mobile",
		"amanzi_mobile_notifications",
		"dashicons-smartphone"
	);
	
	add_submenu_page(
		"amanzimtoti_mobile", 
		"Send New Notification", 
		"Send New", 
		"manage_options", 
		"amanzimtoti_mobile_new_notification",
		"amanzi_mobile_new_notification"
	);
}

function amanzi_notifications_action() {
	global $wpdb;
	global $id;
	if (empty($_POST)) {
		include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notifications.php';
		return;
	}
	
	$notifications_action = $_POST["notificationsaction"];
	if (isset($_POST["notificationid"])) {
		$id = $_POST["notificationid"];
	}
	
	switch ( $notifications_action ) {
		case "list":
			include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notifications.php';
			break;
		
		case "insert":
			include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notification-insert.php';
			break;
		
		case "edit":
			include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notification-edit.php';
			break;
		
		case "handleupdate":
			amanzi_handle_notification_update();
			include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notifications.php';
			break;
		
		case "handledelete":
			amanzi_handle_notification_delete();
			include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notifications.php';
			break;
		
		case "handleinsert":
			amanzi_handle_notification_insert();
			include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notifications.php';
			break;

		default:
			echo "<h2>Nothing found!</2>";
			break;
	}
}

/**
 * Displays all the notifications page
 */
function amanzi_mobile_notifications() {
	global $wpdb;
	if (!current_user_can( "manage_options" )) wp_die ( "You do not have sufficient permissions!" );
	amanzi_notifications_action();
}

/**
 * Displays the insert notification page
 */
function amanzi_mobile_new_notification() {
	global $wpdb;
	if (!current_user_can( "manage_options" )) wp_die ( "You do not have sufficient permissions!" );
	
	if (!empty($_POST)) {
		amanzi_notifications_action();
	} else {
		include_once AMANZI_MOBILIE_PLUGIN_DIR . 'admin/notification-insert.php';
	}
}

/**
 * Deletes a notification
 */
function amanzi_handle_notification_delete() {
	global $wpdb;
	if (isset($_POST["notificationid"])) {
		$id = $_POST["notificationid"];
		
		$query = "DELETE FROM {$wpdb->prefix}amanzi_mobile_notifications WHERE id={$id} LIMIT 1;";
		$wpdb->query($query);
	}
}

/**
 * Updates the notification
 * @global type $wpdb
 */
function amanzi_handle_notification_update() {
	global $wpdb;
	$table = $wpdb->prefix."amanzi_mobile_notifications";
	extract($_POST);
	$currently_logged_in_user = wp_get_current_user();
	
	$update_fields = array();
	$update_fields_format = array();	// "%s", "%d", etc.
	$where = array("id" => (int)$notificationid);
	$where_format = array("%d");
	
	if (isset($heading)) {
		$update_fields["heading"] = htmlspecialchars($heading);
		$update_fields_format[] = "%s";
	}
	
	if (isset($description)) {
		$update_fields["description"] = htmlspecialchars(trim($description));
		$update_fields_format[] = "%s";
	}
		
	$update_fields["last_updated_by"] = $currently_logged_in_user->data->ID;
	$update_fields_format[] = "%d";
	
	date_default_timezone_set("Africa/Johannesburg");	
	$update_fields["updated_on"] = date("Y-m-d H:i:s");
	$update_fields_format[] = "%s";
	
	$wpdb->update(
		$table,
		$update_fields,
		$where,
		$update_fields_format,
		$where_format
	);
}

/**
 * Inserts a new notification
 * @global type $wpdb
 */
function amanzi_handle_notification_insert() {
	global $wpdb;
	$table = $wpdb->prefix."amanzi_mobile_notifications";
	extract($_POST);
	$currently_logged_in_user = wp_get_current_user();
	
	$insert_fields = array();
	$insert_fields_format = array();	// "%s", "%d", etc.
	
	if (isset($heading)) {
		$insert_fields["heading"] = htmlspecialchars($heading);
		$insert_fields_format[] = "%s";
	}
	
	if (isset($description)) {
		$insert_fields["description"] = htmlspecialchars(trim($description));
		$insert_fields_format[] = "%s";
	}
		
	$insert_fields["created_by"] = $currently_logged_in_user->data->ID;
	$insert_fields_format[] = "%d";
	$insert_fields["last_updated_by"] = $currently_logged_in_user->data->ID;
	$insert_fields_format[] = "%d";
	
	date_default_timezone_set("Africa/Johannesburg");	
	$insert_fields["created_on"] = date("Y-m-d H:i:s");
	$insert_fields_format[] = "%s";
	$insert_fields["updated_on"] = date("Y-m-d H:i:s");
	$insert_fields_format[] = "%s";
	
	$wpdb->insert(
		$table,
		$insert_fields,
		$insert_fields_format
	);
}