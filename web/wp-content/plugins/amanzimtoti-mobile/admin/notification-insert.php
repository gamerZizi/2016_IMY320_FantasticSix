<div class="wrap">
	<h1>Add New Notification</h1>
	<form action="" method="post" class="validate">
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="heading">Heading <span class="description">(required)</span></label>
					</th>
					<td>
						<input style="width: 25em;" name="heading" id="heading" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" type="text">
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="description">Description <span class="description">(required)</span></label>
					</th>
					<td>
						<textarea style="width: 25em;" rows="10" name="description" id="description"></textarea>
					</td>
				</tr>				
			</tbody>
		</table>
		<p class="submit">
			<button type="submit" name="notificationsaction" value="handleinsert" class="button button-primary">Send Notification</button>
		</p>		
	</form>
</div>