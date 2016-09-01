<?php

/**
 * This will display user extra information (Volunteers and Benefactors)
 * If not filled in yet will display a form for them to fill in information.
 * 
 * This uses database. 
 */

global $wpdb;

$query = "SELECT * FROM " . $wpdb->prefix . "amanzi_members WHERE user_id=" . get_current_user_id() . " LIMIT 1;";
$results = $wpdb->get_results($query);

if (!$results): // display form ?>
	<div class="amanzi-parent content">
		<form action="" method="post">
			<input type="hidden" name="listaction" value="insert">
			<fieldset>
				<legend>Member Type</legend>
				<select required="required" name="role" id="role">
					<option class="opt-title" selected="selected" value="">-- Please Select Member Type --</option>
					<option value="volunteer">Volunteer</option>
					<option value="benefactor">Benefactor</option>
				</select>
			</fieldset>
			<div style="margin-bottom: 20px;"></div>
			<div id="extra_fields" class="container"></div>
			<input type="submit" value="Update Details" class="submit" id="submit" name="submit" />
		</form>
	</div>
<?php
else:
	// display information
	echo amanzi_display_user_information($results[0]);
endif;


