<form id="scsMailTestForm">
	<label>
		<?php _e('Send test email to')?>
		<?php echo htmlScs::text('test_email', array('value' => $this->testEmail))?>
	</label>
	<?php echo htmlScs::hidden('mod', array('value' => 'mail'))?>
	<?php echo htmlScs::hidden('action', array('value' => 'testEmail'))?>
	<button class="button button-primary">
		<i class="fa fa-paper-plane"></i>
		<?php _e('Send test', SCS_LANG_CODE)?>
	</button><br />
	<i><?php _e('This option allow you to check your server mail functionality', SCS_LANG_CODE)?></i>
</form>
<div id="scsMailTestResShell" style="display: none;">
	<?php _e('Did you received test email?', SCS_LANG_CODE)?><br />
	<button class="scsMailTestResBtn button button-primary" data-res="1">
		<i class="fa fa-check-square-o"></i>
		<?php _e('Yes! It work!', SCS_LANG_CODE)?>
	</button>
	<button class="scsMailTestResBtn button button-primary" data-res="0">
		<i class="fa fa-exclamation-triangle"></i>
		<?php _e('No, I need to contact my hosting provider with mail function issue.', SCS_LANG_CODE)?>
	</button>
</div>
<div id="scsMailTestResSuccess" style="display: none;">
	<?php _e('Great! Mail function was tested and working fine.', SCS_LANG_CODE)?>
</div>
<div id="scsMailTestResFail" style="display: none;">
	<?php _e('Bad, please contact your hosting provider and ask them to setup mail functionality on your server.', SCS_LANG_CODE)?>
</div>
<div style="clear: both;"></div>
<form id="scsMailSettingsForm">
	<table class="form-table" style="max-width: 450px;">
		<?php foreach($this->options as $optKey => $opt) { ?>
			<?php
				$htmlType = isset($opt['html']) ? $opt['html'] : false;
				if(empty($htmlType)) continue;
			?>
			<tr>
				<th scope="row" class="col-w-30perc">
					<?php echo $opt['label']?>
					<?php if(!empty($opt['changed_on'])) {?>
						<br />
						<span class="description">
							<?php 
							$opt['value'] 
								? printf(__('Turned On %s', SCS_LANG_CODE), dateScs::_($opt['changed_on']))
								: printf(__('Turned Off %s', SCS_LANG_CODE), dateScs::_($opt['changed_on']))
							?>
						</span>
					<?php }?>
				</th>
				<td class="col-w-10perc">
					<i class="fa fa-question supsystic-tooltip" title="<?php echo $opt['desc']?>"></i>
				</td>
				<td class="col-w-1perc">
					<?php echo htmlScs::$htmlType('opt_values['. $optKey. ']', array('value' => $opt['value'], 'attrs' => 'data-optkey="'. $optKey. '"'))?>
				</td>
				<td class="col-w-50perc">
					<div id="scsFormOptDetails_<?php echo $optKey?>" class="scsOptDetailsShell"></div>
				</td>
			</tr>
		<?php }?>
	</table>
	<?php echo htmlScs::hidden('mod', array('value' => 'mail'))?>
	<?php echo htmlScs::hidden('action', array('value' => 'saveOptions'))?>
	<button class="button button-primary">
		<i class="fa fa-fw fa-save"></i>
		<?php _e('Save', SCS_LANG_CODE)?>
	</button>
</form>


