<?php
$query = "SELECT * FROM {$wpdb->prefix}amanzi_mobile_notifications ORDER BY id DESC;";
$data = $wpdb->get_results($query);
$has_notifations = true;

if (!$data) {
	$has_notifations = false;
}
?>
<div class="wrap">
	<h1>
		Notifications
		<form action="" method="post" style="display: inline-block">
			<input type="hidden" name="notificationsaction" value="insert" />
			<button type="submit" class="button">Add New Notification</button>
		</form>
	</h1>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<td>#</td>
				<th>Heading</th>
				<th>Added On</th>
				<th>Added By</th>
				<th>Updated On</th>
				<th>Updated By</th>
				<td>&nbsp;</td>
			</tr>
		</thead>	
		<?php if (!$has_notifations): ?>

		<tr>
			<tbody>
				<td colspan="7">
					<h2 style="text-align: center;">There are no Notifications at the moment.</h2>
				</td>
			</tbody>		
		</tr>
		<?php else: ?>
		<tbody>
			<?php
				foreach ( $data as $key => $value ) :
					$creater = new WP_User( $value->created_by );
					$updater = new WP_User( $value->last_updated_by );
			?>
			<tr>
				<form action="" method="post">
					<input type="hidden" name="notificationsaction" value="edit" />
					<input type="hidden" name="notificationid" value="<?php echo $value->id; ?>" />
					<td><?php echo $value->id; ?></td>
					<td><?php echo $value->heading; ?></td>
					<td><a href="user-edit.php?user_id=<?php echo $creater->ID; ?>"><?php echo $creater->display_name; ?></a></td>
					<td><?php echo $value->created_on; ?></td>
					<td><a href="user-edit.php?user_id=<?php echo $updater->ID; ?>"><?php echo $updater->display_name; ?></a></td>
					<td><?php echo $value->updated_on; ?></td>
					<td>
						<button type="submit" class="">View</button>
					</td>
				</form>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<?php endif; ?>
	</table>
</div>