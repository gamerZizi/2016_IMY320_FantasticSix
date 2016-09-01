<?php

/**
 * This is the mini-controller to route to the correct page. 
 */

add_shortcode("amanzimtoti-member-display", "amanzimtoti_member_display");

function amanzimtoti_member_display( $attribs ) {
	if (!is_user_logged_in() || is_admin()) {
		echo "You are not logged in, please login to access this page.";
		return;	
	}
	include_once AMANZIMTOTI_MEMBERS_PLUGIN_DIR . "/pages/amanzimtoti-member-shortcode-form.php";	
}

// Add Ajax if logged in
/*if (is_user_logged_in()) {
	add_action("wp_ajax_to_call_in_js", "amanzi_handle_ajax_requests");
} else {
	add_action("wp_ajax_nopriv_to_call_in_js", "amanzi_handle_ajax_requests");
}*/
add_action("wp_ajax_create_member_form", "amanzi_handle_ajax_requests");
add_action("wp_ajax_create_country_list", "amanzi_create_country_list");
add_action("wp_ajax_create_province_list", "amanzi_create_province_list");
add_action("wp_ajax_create_city_list", "amanzi_create_city_list");
add_action("wp_ajax_create_postcode_input", "amanzi_create_postcode_input");

function amanzi_handle_ajax_requests() {
	echo create_common_inputs();
	if ($_POST["type"] === "benefactor") {
		echo create_company_inputs();
	}
	//print_r(  get_user_meta( wp_get_current_user()->ID));
	exit();
}

function amanzi_create_country_list() {
	echo create_countries_list(htmlspecialchars($_POST["continent"]));
	exit();
}

function amanzi_create_province_list() {
	echo create_province_list(htmlspecialchars($_POST["country_id"]));
	exit();
}

function amanzi_create_city_list() {
	echo create_city_list(htmlspecialchars($_POST["province_id"]));
	exit();
}

function amanzi_create_postcode_input() {
	echo create_postcode_input();
	exit();
}

function create_common_inputs() {
	$user = get_user_meta(wp_get_current_user()->ID);
	$full_name = $user["first_name"][0] . " " . $user["last_name"][0];
	$html = "<fieldset class=''>
				<legend class=''>Personal Details</legend>
				<div class='amanzi-row'>
				  <label for='fullname'>Full Name: </label>
				  <input type='text' disabled='disabled' value='{$full_name}' />
				</div>
				<div class='amanzi-row'>
				  <label for='gender'>Gender: </label><span style='color: red;'>*</span><br />
				  <div class='input-div'>
					<input type='radio' name='gender' checked='checked' value='M' /><span class='gender-span'>Male</span>
					<input type='radio' name='gender' value='F' /><span class='gender-span'>Female</span>
				  </div>
				</div>
				<div class='amanzi-row'>
				  <label for='dateOfBirth'>Date of Birth: </label><span style='color: red;'>*</span>
				  <input class='text-input default_field_username' onchange='validateDOB();' type='text' id='dateOfBirth' name='dateOfBirth' value='' required='required' />
				  <span style='margin-left: 5px; font-size: 11px; font-style: italic;' class=''>(YYYY-MM-DD)</span>
				</div>
				<div class='amanzi-row'>
				  <label for='phone'>Mobile Number: </label><span style='color: red;'>*</span><br />
				  <input type='text' disabled='disabled' value='+27' size='5' style='width: 60px;' />
				  <input class='text-input default_field_username' type='text' id='phone' name='phone' value='' maxlength='9' required='required' />
				</div>
				<fieldset>
				  <legend>Address:</legend>
				  <div class='amanzi-row'>
					<label class='' for='streenAddress'>Street Address: </label><span style='color: red;'>*</span>
					<input type='text' id='streenAddress' name='streenAddress' value='' required='required' />
				  </div>
				  <div class='amanzi-row'>
					". create_continent_list() ."
				  </div>
				  <div class='amanzi-row' id='countryDiv'></div>
				  <div class='amanzi-row' id='provinceDiv'></div>
				  <div class='amanzi-row' id='cityDiv'></div>
				  <div class='amanzi-row' id='postCodeDiv'></div>				  
				</fieldset>
				<div class='amanzi-row'>
					<label for='occupation'>Occupation: </label><span style='color: red;'>*</span>
					<input type='text' id='occupation' name='occupation' value='' required='required' />
				  </div>
				<div class='amanzi-row'>
				  <label style='vertical-align: top;' for='prevProjects'>Previous Projects: </label>
				  <textarea name='prevProjects' id='prevProjects' rows='6'></textarea>
				  <span style='margin-left: 5px; font-size: 11px; font-style: italic;' class=''>(Optional)</span>
				</div>
				<div class='amanzi-row'>
					<label style='vertical-align: top;' for='refs'>References: </label>
					<textarea name='refs' id='refs' rows='6'></textarea>
					<span style='margin-left: 5px; font-size: 11px; font-style: italic;' class=''>(Optional) - <b>Format:</b> Name, Contact Number, Position</span>          
				</div>
			</fieldset>";
	return $html;
}

function create_company_inputs() {
	$html = "<fieldset>
				<legend>Company Details</legend>
				<div class='amanzi-row'>
				  <label for='companyName'>Company Name: </label><span style='color: red;'>*</span>
				  <input type='text' required='required' name='companyName' id='companyName' value='' />
				</div>
				<div class='amanzi-row'>
				  <label style='vertical-align: top;' for='companyAddress'>Company Address: </label><span style='color: red;'>*</span>
				  <textarea name='companyAddress' id='companyAddress' required='required' rows='6'></textarea>
				</div>
			</fieldset>";
	return $html;
}

function create_continent_list() {
	global $wpdb;
	$query = "SELECT DISTINCT `region` FROM " . $wpdb->prefix . "amanzi_countries ORDER BY `region` ASC;";
	$results = $wpdb->get_results($query);
	return create_select_list("Continent", "continent", "continent", $results, "region", "region", "showCountryList();");
}

function create_countries_list($continent) {
	global $wpdb;
	$query = "SELECT `id`, `name` FROM " . $wpdb->prefix . "amanzi_countries WHERE `region`='" . htmlspecialchars($continent) . "' ORDER BY `name` ASC;";
	$results = $wpdb->get_results($query);	
	return create_select_list("Country", "countryID", "country", $results, "id", "name", "showProvinceList();");
}

function create_province_list($country_id) {
	global $wpdb;
	$query = "SELECT `id`, `province` FROM " . $wpdb->prefix . "amanzi_provinces WHERE `country_id`='" . (int)$country_id . "' ORDER BY `province` ASC;";
	$results = $wpdb->get_results($query);	
	return create_select_list("Province", "provinceID", "province", $results, "id", "province", "showCityList();");
}

function create_city_list($province_id) {
	global $wpdb;
	$query = "SELECT `city` FROM " . $wpdb->prefix . "amanzi_cities WHERE `province_id`='" . (int)$province_id . "' ORDER BY `city` ASC;";
	$results = $wpdb->get_results($query);	
	return create_select_list("City", "cityID", "city", $results, "city", "city", "showPostCodeInput();");
}

function create_postcode_input() {
	return create_input("Post Code", "postCode", "postCode");
}

function get_country_name($country_id) {
	global $wpdb;
	$query = "SELECT `name` FROM " . $wpdb->prefix . "amanzi_countries WHERE `id`='%d' LIMIT 1;";
	$results = $wpdb->get_results($wpdb->prepare($query, (int)$country_id));
	return $results[0]->name;
}

function get_province_name($province_id) {
	global $wpdb;
	$query = "SELECT `province` FROM " . $wpdb->prefix . "amanzi_provinces WHERE `id`='%d' LIMIT 1;";
	$results = $wpdb->get_results($wpdb->prepare($query, (int)$province_id));
	return $results[0]->province;
}

function amanzi_user_exist( $user_id ) {
	global $wpdb;
	$query = "SELECT COUNT(*) AS numResults FROM " . $wpdb->prefix . "amanzi_members WHERE `user_id`='%d' LIMIT 1;";
	$result = $wpdb->get_results($wpdb->prepare($query, (int)$user_id));
	return $result[0]->numResults > 0;
}

function amanzi_insert_additional_details() {
	global $wpdb;
	$user = wp_get_current_user();
	$table = $wpdb->prefix . "amanzi_members";
	
	// if already exist
	if (amanzi_user_exist($user->ID)) {
		return;
	}
	
	$post = amanzi_clean_array($_POST);
	extract($post);
	if (!isset($role) || empty($role)) return;
	if (!amanzi_format_dob($dateOfBirth)) return;
	
	$details = array();
	$format = array();
	
	if ($role === "benefactor") {
		$details["company_name"] = $companyName;					$format[] = '%s';
		$details["company_address"] = $companyAddress;				$format[] = '%s';
	}
	
	$details["active"] = 1;											$format[] = '%d';
	$details["user_id"] = (int)$user->ID;							$format[] = '%d';
	$details["date_of_birth"] = $dateOfBirth;						$format[] = '%s';
	$details["occupation"] = $occupation;							$format[] = '%s';
	$details["phone"] = amanzi_format_phone($phone);				$format[] = '%s';
	$details["address"] = amanzi_format_address($post);				$format[] = '%s';
	$details["gender"] = $gender;									$format[] = '%s';
	
	if (!empty($prevProjects)) {
		$details["prev_projects"] = $prevProjects;					$format[] = '%s';
	}
	if (!empty($refs)) {
		$details["references"] = $refs;								$format[] = '%s';
	}
	
	$wp_userdetails = array( "ID" => $user->ID, "role" => $role, "show_admin_bar_front" => "false" );
	$user_id = wp_update_user($wp_userdetails);
	if (is_wp_error($user_id)) {
		return;
	}
	
	$wpdb->insert(
		$table,
		$details,
		$format
	);
}

function amanzi_clean_array($params) {
	foreach ( $params as $key => $value ) {
		$params[$key] = htmlspecialchars(trim($value));
	}
	return $params;
}

function amanzi_format_dob($dob) {
	return preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $dob);
}

function amanzi_format_phone($phone) {
	return "+27".$phone;
}

function amanzi_format_address($post){
	extract($post);
	$address = array();
	$address["street_address"] = $streenAddress;
	$address["city"] = $city;
	$address["post_code"] = $postCode;
	$address["country"] = get_country_name($country);
	$address["province"] = get_province_name($province);
	
	return json_encode($address);			
}

function amanzi_format_address_html($json_encoded) {
	$address = json_decode($json_encoded);
	$address_html = $address->street_address . "<br />";	
	$address_html .= $address->country . "<br />";
	$address_html .= $address->province . "<br />";
	$address_html .= $address->city . "<br />";
	$address_html .= $address->post_code . "<br />";
	return $address_html;
}

function amanzi_format_textarea_html($contant, $ignore = array()) {
	$filter_search = array("\n", ";", ",");
	$formatted_content = $contant;
	foreach ( $filter_search as $value ) {
		if ( in_array( $value, $ignore) ) continue;
		$formatted_content = str_replace($value, "<br />", $formatted_content);
	}
	return $formatted_content;
}

function create_select_list($label, $id, $name, $results, $value, $display, $jsciptFunc) {
	$select = "<label for='{$id}'>" . $label . ": </label>";
	$select .= "<select onchange='{$jsciptFunc}' id='{$id}' name='{$name}'>";	
	$select .= "<option class='opt-title'>-- Please Select ". ucfirst($name) ." --</option>";
	if (!$results) {
		return $select . "</select>";
	}	
	foreach ( $results as $result) {
		if (empty($result->$display)) continue;
		$select .= "<option value='{$result->$value}'>{$result->$display}</option>";
	}	
	$select .= "</select>";
	return $select;
}

function create_input($label, $id, $name, $required = true, $value = "") {
	$input = "<label for='{$id}'>" . $label . ": </label>";
	$requiredAttr = "";
	if ($required) {
		$input .= "<span style='color: red;'>*</span>";
		$requiredAttr = "required='required'";
	}
	$input .= "<input {$requiredAttr} type='text' id='{$id}' name='{$name}' value='{$value}' />";
	return $input;
}

function amanzi_display_user_information( $stdClass_object) {
	$user = wp_get_current_user();
	$user_obj = new WP_User($user->ID);
	$user_meta = get_user_meta($user->ID);
	$extra_user_data = get_userdata($user->ID);
	$full_name = $user_meta["first_name"][0] . " " . $user_meta["last_name"][0];
	$table =  "<table>";
	$table .=		"<tr><th style='vertical-align: top;'>Full Name:</th><td>" . $full_name . "</td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Member Type:</th><td>" . ucfirst($user_obj->roles[0]) . "</td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Email Address:</th><td><a href='mailto:" . $user_obj->data->user_email. "'>" . $user_obj->data->user_email . "</a></td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Gender:</th><td>" . (($stdClass_object->gender === 'M') ? "Male" : "Female") . "</td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Date of Birth:</th><td>" . $stdClass_object->date_of_birth . "</td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Contact Number:</th><td>" . $stdClass_object->phone . "</td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Address:</th><td>" . amanzi_format_address_html($stdClass_object->address) . "</td></tr>";
	$table .=		"<tr><th style='vertical-align: top;'>Occupation:</th><td>" . $stdClass_object->occupation . "</td></tr>";
	if (!empty($stdClass_object->prev_projects)) {
		$table .=	"<tr><th style='vertical-align: top;'>Occupation:</th><td>" . amanzi_format_textarea_html($stdClass_object->prev_projects) . "</td></tr>";
	}	
	if (!empty($stdClass_object->references)) {
		$table .=	"<tr><th style='vertical-align: top;'>Reference:</th><td>" . amanzi_format_textarea_html($stdClass_object->references, array(";", ",")) . "</td></tr>";
	}
	if (!empty($stdClass_object->company_name)) {
		$table .=	"<tr><th style='vertical-align: top;'>Company Name:</th><td>" . $stdClass_object->company_name . "</td></tr>";
	}
	if (!empty($stdClass_object->company_address)) {
		$table .=	"<tr><th style='vertical-align: top;'>Company Address:</th><td>" . amanzi_format_textarea_html($stdClass_object->company_address) . "</td></tr>";
	}	
	$table .= "</table>";
	return $table;
}
