<?php
/*
  Plugin Name:	Amanzimtoti Mobile
  Description:	Handles all the mobile specific functionality utilizing the WP REST API v2.
  Version:		1.0.0
  Author:		SC Simelane
  Authir URI:	http://amanzimtoti.byethost33.com
  License:		GPL2
  License URI:	https://www.gnu.org/licenses/gpl-2.0.html
 */

define("AMANZI_MOBILIE_PLUGIN_DIR", plugin_dir_path( __FILE__ ));

require_once 'includes/amanzi_mobile_admin.php';

/**
 * Adding Custom Route
 */
function amanzi_create_route() {	
	// Adding a Login Route
	register_rest_route(
		"amanzimtoti-mobile/v1", "/auth/(?P<username>[\\w-]+)/(?P<password>[\\w-]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_authorize_mobile_user_callback"
		)
	);
	// Adding a Logout Route
	register_rest_route(
		"amanzimtoti-mobile/v1", "/logout/(?P<id>[\\d]+)/(?P<nots>[\\w-]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_signout_callback"
		)
	);
	// Adding a Events Route (Without Params)
	register_rest_route(
		"amanzimtoti-mobile/v1", "/events", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_all_events_callback"
		)
	);
	// Adding a Events Route (With Params)
	register_rest_route(
		"amanzimtoti-mobile/v1", "/events/(?P<cat>[\\w-]+)/(?P<org>[\\w-]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_events_callback"
		)
	);
	// Adding a Events Route
	register_rest_route(
		"amanzimtoti-mobile/v1", "/event/(?P<id>[\\d]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_event_callback"
		)
	);
	// Adding a Calendar Route
	register_rest_route(
		"amanzimtoti-mobile/v1", "/calendar", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_calendar_callback"
		)
	);	
	// Adding a News Route
	register_rest_route(
		"amanzimtoti-mobile/v1", "/news", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_news_callback"
		)
	);
	// Adding a News By ID Route
	register_rest_route(
		"amanzimtoti-mobile/v1", "/news/(?P<id>[\\d]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_news_by_id_callback"
		)
	);		
	// Get all notifications
	register_rest_route(
		"amanzimtoti-mobile/v1", "/notifications/(?P<count>[\\d]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_get_notications_callback"
		)
	);		
	// Get notification by id
	register_rest_route(
		"amanzimtoti-mobile/v1", "/notification/(?P<id>[\\d]+)", 
		array(
			"methods" => "GET",
			"callback" => "amanzi_get_notification_by_id_callback"
		)
	);
}

function amanzi_signout_callback( $data ) {
	global $wpdb;
	$id = (int)$data["id"];
	$read_notifications = $data["nots"];
	$query = "UPDATE {$wpdb->prefix}amanzi_members SET read_notifications='{$read_notifications}' WHERE user_id={$id} LIMIT 1;";
	$wpdb->query($query);
	
	wp_logout();
	$results = new stdClass();
	$results->status = 200;
	$results->message = "Successfully logged out.";
	return $results;
}

function amanzi_authorize_mobile_user_callback( $data ) {
	$credintials = array(
		"user_login" => $data["username"],
		"user_password" => $data["password"],
		"remember" => true
	);

	$user = wp_signon( $credintials, false );
	if ( is_wp_error( $user ) ) {
		$fail = new stdClass();
		$fail->ID = 0;
		$fail->status = 404;
		return $fail;
		//return $user->get_error_message();
	}
	$user->data = amanzi_ignore_fields($user->data, array("user_activation_key", "user_pass", "user_status", "user_url"), true);
	$user->amanzimtoti_member_info = amanzi_get_member_by_id( $user->ID );
	update_amanzi_member_has_mobile_app($user->ID);
	return $user;
}

function amanzi_events_callback( $data = array() ) {
	global $wpdb;
	$filter = true;
	if (isset($data) && !empty($data)) {
		$term_ids = str_replace("-", ",", trim($data["cat"]));
		$meta_ids = str_replace("-", ",", trim($data["org"]));
	} else {
		$filter = false;
	}
		
	$query = "SELECT events.*, terms.name AS event_category, terms.slug AS event_slug FROM {$wpdb->prefix}posts AS events "
			."INNER JOIN {$wpdb->prefix}term_relationships AS term_rel "
			."ON events.id = term_rel.object_id "
			."INNER JOIN {$wpdb->prefix}term_taxonomy AS term_tax "
			."ON term_rel.term_taxonomy_id = term_tax.term_taxonomy_id "
			."INNER JOIN {$wpdb->prefix}terms AS terms "
			."ON term_tax.term_id = terms.term_id "
			."WHERE events.post_type='tribe_events'"
			."AND term_tax.taxonomy='tribe_events_cat'";
	$query .= ($filter) ? " AND terms.term_id IN ({$term_ids});" : ";";
	
	$results = $wpdb->get_results($query);	
	for ($i = 0; $i < count($results); $i++) {
		$results[$i] = amanzi_ignore_fields($results[$i], array(
			"post_author", "post_date", "post_date_gmt", "post_content",
			"post_status", "comment_status", "ping_status", "post_password",
			"to_ping", "pinged", "post_modified", "post_modified_gmt",
			"post_content_filtered", "post_parent", "guid", "menu_order", "post_mime_type"), true);		
	}
	
	foreach ( $results as $result) {			
		$result->postmeta = amanzi_get_event_postmeta_data($result->ID);
	}
	return $results;
}

function amanzi_all_events_callback() {
	return amanzi_events_callback();
}

function amanzi_event_callback( $data ) {
	global $wpdb;
	$id = (int)$data["id"];
	
	$query = "SELECT events.*, terms.name AS event_category, terms.slug AS event_slug FROM {$wpdb->prefix}posts AS events "
			."INNER JOIN {$wpdb->prefix}term_relationships AS term_rel "
			."ON events.id = term_rel.object_id "
			."INNER JOIN {$wpdb->prefix}term_taxonomy AS term_tax "
			."ON term_rel.term_taxonomy_id = term_tax.term_taxonomy_id "
			."INNER JOIN {$wpdb->prefix}terms AS terms "
			."ON term_tax.term_id = terms.term_id "
			."WHERE events.post_type='tribe_events' "
			."AND events.id IN ({$id});";
	
	$results = $wpdb->get_results($query);	
	$results[0] = amanzi_ignore_fields($results[0], array(
		"post_author", "post_date", "post_date_gmt", "post_content",
		"post_status", "comment_status", "ping_status", "post_password",
		"to_ping", "pinged", "post_modified", "post_modified_gmt",
		"post_content_filtered", "post_parent", "guid", "menu_order", "post_mime_type"), true);	
		
	foreach ( $results as $result) {			
		$result->postmeta = amanzi_get_event_postmeta_data($result->ID);
	}
	return $results[0];
}

function amanzi_news_callback() {
	$args = array(
		"post_type"	=>	"news"
	);
	$query = new WP_Query($args);
	$news = new stdClass();
	$news->news = array();
	
	if ($query->have_posts()) {		
		for($i = 0; $i < count($query->posts); $i++) {
			$query->posts[$i]->post_date = date("j F Y", strtotime($query->posts[$i]->post_date));
			$query->posts[$i]->post_modified = date("j F Y", strtotime($query->posts[$i]->post_modified));
		}
		$news->news = $query->posts;
	}
	wp_reset_postdata();
	return $news;
}

function amanzi_news_by_id_callback( $data ) {
	$args = array(
		"post_type"	=>	"news",
		"p" => $data["id"]
	);
	$query = new WP_Query($args);
	$news = new stdClass();
	$news->news = array();
	
	if ($query->have_posts()) {
		$query->post->post_date = date("j F Y", strtotime($query->post->post_date));
		$query->post->post_modified = date("j F Y", strtotime($query->post->post_modified));
		$query->post->post_content = str_replace( "\r\n", "<br />", $query->post->post_content );
		$query->post->post_content = str_replace("[/caption]", "</caption>", $query->post->post_content );
		$more_filter = '[caption id="" align="alignleft" width="525"]';
		$query->post->post_content = str_replace($more_filter, "<caption>", $query->post->post_content );
		$news->news = $query->post;
	}
	
	wp_reset_postdata();
	return $news;
}

function amanzi_get_notications_callback( $data ) {
	global $wpdb;
	$count = (int)$data["count"];
	$query = "SELECT id, heading, description, created_on FROM {$wpdb->prefix}amanzi_mobile_notifications ORDER BY id DESC;";
	$results = $wpdb->get_results($query);
	if (!$results) { return array(); }
	foreach ( $results as $result ) {
		$result->sent_day = date("j F Y", strtotime($result->created_on));
		$result->sent_time = date("G:i A", strtotime($result->created_on));
		$result->new_notifications_count = ($wpdb->num_rows > $count) ? $wpdb->num_rows - $count: $count;		
		$result->new_notification = ($wpdb->num_rows > $count) ? true : false;
	}	
	return $results;
}

function amanzi_get_notification_by_id_callback( $data ) {
	global $wpdb;
	$id = (int)$data["id"];
	$query = "SELECT heading, description, created_on FROM {$wpdb->prefix}amanzi_mobile_notifications WHERE id={$id};";
	$row = $wpdb->get_row($query);
	if (!$row) { return array(); }
	$row->sent_day = date("j F Y", strtotime($row->created_on));
	$row->sent_time = date("G:i A", strtotime($row->created_on));
	return $row;
}

function amanzi_get_event_postmeta_data($event_post_id) {
	$formated_event_postmeta = new stdClass();
	$event_postmeta = get_post_meta($event_post_id);
	
	if (isset($event_postmeta["_EventStartDate"])) {
		$formated_event_postmeta->event_start_date = date("j F Y, g a", strtotime($event_postmeta["_EventStartDate"][0]));
	}
	
	if (isset($event_postmeta["_EventEndDate"])) {
		$formated_event_postmeta->event_end_date = date("j F Y, g a", strtotime($event_postmeta["_EventEndDate"][0]));
	}
	
	if (isset($event_postmeta["_EventDuration"])) {
		$formated_event_postmeta->event_duration = (int)$event_postmeta["_EventDuration"][0] / 3600;
		$formated_event_postmeta->event_duration = round($formated_event_postmeta->event_duration);
	}
	
	if (isset($event_postmeta["_EventCurrencySymbol"])) {
		$formated_event_postmeta->event_currency_symbol = $event_postmeta["_EventCurrencySymbol"][0];
	}
	
	if (isset($event_postmeta["_EventCost"])) {
		$formated_event_postmeta->event_cost = $event_postmeta["_EventCost"][0];
	}
		
	if (isset($event_postmeta["_EventOrganizerID"])) {
		$formated_event_postmeta->event_organizer = trim(get_post_meta( $event_postmeta["_EventOrganizerID"][0])["_OrganizerOrganizer"][0]);
	}
	if (isset($event_postmeta["_EventVenueID"])) {
		$venue_postmeta = get_post_meta($event_postmeta["_EventVenueID"][0]);
		$formated_event_postmeta->venue_name = $venue_postmeta["_VenueVenue"][0];
		$formated_event_postmeta->venue_address = $venue_postmeta["_VenueAddress"][0];
		$formated_event_postmeta->venue_city = $venue_postmeta["_VenueCity"][0];
		$formated_event_postmeta->venue_country = $venue_postmeta["_VenueCountry"][0];
		$formated_event_postmeta->venue_province = $venue_postmeta["_VenueProvince"][0];
		$formated_event_postmeta->venue_state = $venue_postmeta["_VenueState"][0];
		$formated_event_postmeta->venue_zip = $venue_postmeta["_VenueZip"][0];
		$formated_event_postmeta->venue_phone = $venue_postmeta["_VenuePhone"][0];
		$formated_event_postmeta->venue_state_province = $venue_postmeta["_VenueStateProvince"][0];
	}	
	return $formated_event_postmeta;
}

function amanzi_remove_underscore($params) {
	foreach ( $params as $key => $value ) {
		$params[str_replace("_", "", $key)] = $value;
	}
	return $params;
}

function amanzi_ignore_fields($params, $ignore, $is_object) {
	$results = ($is_object) ? new stdClass() : array();
	foreach ($params as $key => $value) {
		if (  in_array( $key, $ignore )) continue;
		if ($is_object) {
			$results->$key = $value;
		} else {
			$results[$key] = $value;
		}
	}
	return $results;
}

function amanzi_get_member_by_id( $id ) {
	global $wpdb;
	$query = "SELECT phone, event_categories, event_organizers, read_notifications FROM " . $wpdb->prefix . "amanzi_members WHERE user_id=" . $id . " LIMIT 1;";
	$results = $wpdb->get_results( $query );
	$results[0]->event_categories = json_decode($results[0]->event_categories);
	$results[0]->event_organizers = json_decode($results[0]->event_organizers);
	return (!$results) ? null : $results[0];
}

// Add more fields
function amanzi_register_user_fields() {
	// Add author name
	register_api_field( "page", "author_name", 
		array(
			"get_callback" => "amanzi_get_author_name",
			"update_callback" => null,
			"schema" => null
		)
	);

	// Add featured image
	register_api_field( "page", "featured_image_src", 
		array(
			"get_callback" => "amanzi_get_image_src",
			"update_callback" => null,
			"schema" => null
		)
	);
}

function amanzi_get_author_name( $object, $field_name, $request ) {
	return get_the_author_meta( "display" );
}

function amanzi_get_image_src( $object, $field_name, $request ) {
	$featured_image_array = wp_get_attachment_image_src( $object["featured_image"], "thumbnail", true );
	return $featured_image_array[0];
}

function update_amanzi_member_has_mobile_app( $id ) {
	global $wpdb;
	$query = "SELECT has_mobile_app FROM {$wpdb->prefix}amanzi_members WHERE user_id={$id} LIMIT 1;";
	if (!($result = $wpdb->get_results($query))) return;
	if ($result[0]->has_mobile_app == 1) return;
	
	// We do the update
	$query = "UPDATE {$wpdb->prefix}amanzi_members SET has_mobile_app=1 WHERE user_id={$id} LIMIT 1;";
	$wpdb->query($query);
}

add_action( "rest_api_init", "amanzi_create_route" );
