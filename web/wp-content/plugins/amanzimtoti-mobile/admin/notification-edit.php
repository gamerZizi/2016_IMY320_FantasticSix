<?php

$query = "SELECT * FROM {$wpdb->prefix}amanzi_mobile_notifications WHERE id={$id} LIMIT 1;";
$row = $wpdb->get_row($query);
?>
<div class="wrap">
	<h1>Notification Edit</h1>
	<form action="" method="post" class="validate">
		<input type="hidden" name="notificationid" value="<?php echo $row->id ?>" />
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="heading">Heading <span class="description">(required)</span></label>
					</th>
					<td>
						<input style="width: 25em;" name="heading" id="heading" value="<?php echo $row->heading; ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" type="text">
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="description">Description <span class="description">(required)</span></label>
					</th>
					<td>
						<textarea style="width: 25em;" rows="10" name="description" id="description"><?php echo $row->description; ?></textarea>
					</td>
				</tr>				
			</tbody>
		</table>
		<p class="submit">			
			<button type="submit" name="notificationsaction" value="list" class="button button-primary">Cancel</button>
			<button type="submit" name="notificationsaction" value="handleupdate" class="button button-primary">Update</button>
			<button type="submit" name="notificationsaction" value="handledelete" class="button button-primary">Delete</button>
		</p>		
	</form>
</div>