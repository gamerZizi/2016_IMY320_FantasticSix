<?php
	$genOpts = $this->options['general']['opts'];
?>
<section class="supsystic-bar">
	<ul class="supsystic-bar-controls">
		<li title="<?php _e('Save all options')?>">
			<button class="button button-primary" id="scsSettingsSaveBtn" data-toolbar-button>
				<i class="fa fa-fw fa-save"></i>
				<?php _e('Save', SCS_LANG_CODE)?>
			</button>
		</li>
	</ul>
	<div style="clear: both;"></div>
	<hr />
</section>
<section>
	<form id="scsSettingsForm" class="scsInputsWithDescrForm">
		<div class="supsystic-item supsystic-panel">
			<div id="containerWrapper">
				<table class="form-table">
					<tr>
						<th scope="row" class="col-w-20perc">
							<h3><?php _e('Plugin Mode', SCS_LANG_CODE)?>:</h3>
						</th>
						<td class="col-w-1perc">
							<i class="fa fa-question supsystic-tooltip" title="<?php echo $genOpts['cs_mode']['desc']?>"></i>
						</td>
						<td class="col-w-80perc">
							<?php foreach($genOpts['cs_mode']['options'] as $modeOptKey => $modeOptLabel) { ?>
								<label style="margin-right: 5px;">
									<?php echo htmlScs::radiobutton('opt_values[cs_mode]', array(
										'value' => $modeOptKey,
										'checked' => ($genOpts['cs_mode']['value'] == $modeOptKey),
									))?>
									<?php echo $modeOptLabel;?>
								</label>
							<?php }?>
						</td>
					</tr>
					<?php foreach($this->options as $optCatKey => $optCatData) { ?>
						<?php /*if($optCatKey == 'system') continue;*/ /*It will be hidden for now*/?>
						<?php
							$catClass = 'scsOptCat_'. $optCatKey;
						?>
						<?php if(!isset($optCatData['hide_cat_label']) || !$optCatData['hide_cat_label']) {?>
							<tr class="<?php echo $catClass;?>">
								<th colspan="4">
									<h3><?php echo $optCatData['label'];?></h3>
								</th>
							</tr>
						<?php }?>
						<?php if(isset($optCatData['opts']) && !empty($optCatData['opts'])) { ?>
							<?php foreach($optCatData['opts'] as $optKey => $opt) { ?>
								<?php
									$htmlType = isset($opt['html']) ? $opt['html'] : false;
									if(empty($htmlType)) continue;
									if(in_array($optKey, array('cs_mode'))) continue;	// Custom options
									$htmlOpts = array('value' => $opt['value'], 'attrs' => 'data-optkey="'. $optKey. '"');
									$classes = array();
									if(in_array($htmlType, array('selectbox', 'selectlist')) && isset($opt['options'])) {
										if(is_callable($opt['options'])) {
											$htmlOpts['options'] = call_user_func( $opt['options'] );
										} elseif(is_array($opt['options'])) {
											$htmlOpts['options'] = $opt['options'];
										}
									}
									if(isset($opt['pro']) && !empty($opt['pro'])) {
										$classes[] = 'scsProOpt';
									}
									if(isset($opt['attrs']) && !empty($opt['attrs'])) {
										$htmlOpts['attrs'] .= ' '. $opt['attrs'];
									}
									if(isset($opt['classes']) && !empty($opt['classes'])) {
										if(is_array($opt['classes'])) {
											$classes = array_merge($classes, $opt['classes']);
										} else {
											$classes[] = $opt['classes'];
										}
									}
									if(!empty($classes)) {
										$htmlOpts['attrs'] .= ' class="'. implode(' ', $classes). '"';
									}
									if ($htmlType == 'button') {
										$htmlInput = htmlScs::button($opt['options']);
									} else {
										$htmlInput = htmlScs::$htmlType('opt_values['. $optKey. ']', $htmlOpts);
									}
									if(in_array($htmlType, array('hidden'))) {
										echo $htmlInput;	// Just show hidden field, without any row at all
										continue;
									}
								?>
								<tr class="<?php echo $catClass;?>">
									<th scope="row" class="col-w-20perc">
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
										<?php if(isset($opt['pro']) && !empty($opt['pro'])) { ?>
											<span class="scsProOptMiniLabel">
												<a href="<?php echo $opt['pro']?>" target="_blank">
													<?php _e('PRO option', SCS_LANG_CODE)?>
												</a>
											</span>
										<?php }?>
									</th>
									<td class="col-w-1perc">
										<i class="fa fa-question supsystic-tooltip" title="<?php echo $opt['desc']?>"></i>
									</td>
									<td class="col-w-8perc">
										<?php echo $htmlInput;?>
									</td>
								</tr>
							<?php }?>
						<?php } ?>
						<?php if(isset($optCatData['opts_html'])) { ?>
							<tr class="<?php echo $catClass;?>">
								<td colspan="3">
									<?php
										if(is_callable($optCatData['opts_html'])) {
											echo call_user_func( $optCatData['opts_html'] );
										} elseif(is_string($opt['options'])) {
											echo $optCatData['opts_html'];
										}
									?>
								</td>
							</tr>
						<?php }?>
					<?php }?>
				</table>
				<div style="clear: both;"></div>
			</div>
		</div>
		<?php echo htmlScs::hidden('mod', array('value' => 'options'))?>
		<?php echo htmlScs::hidden('action', array('value' => 'saveGroup'))?>
	</form>
</section>
<div id="scsHideForIpWnd" style="display: none;" title="<?php _e('IPs List', SCS_LANG_CODE)?>">
	<label>
		<?php _e('Type here IPs that will not see Coming Soon, each IP - from new line', SCS_LANG_CODE)?>:<br />
		<?php echo htmlScs::textarea('hide_for_ips', array(
			'attrs' => 'id="scsIpTxt" style="width: 100%; height: 300px;"'
		))?>
	</label>
</div>