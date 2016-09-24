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
					<?php foreach($this->options as $optCatKey => $optCatData) { ?>
						<?php if($optCatKey == 'system') continue; /*It will be hidden for now*/?>
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
									$htmlOscs = array('value' => $opt['value'], 'attrs' => 'data-optkey="'. $optKey. '"');
									if(in_array($htmlType, array('selectbox', 'selectlist')) && isset($opt['options'])) {
										if(is_callable($opt['options'])) {
											$htmlOscs['options'] = call_user_func( $opt['options'] );
										} elseif(is_array($opt['options'])) {
											$htmlOscs['options'] = $opt['options'];
										}
									}
									if(isset($opt['pro']) && !empty($opt['pro'])) {
										$htmlOscs['attrs'] .= ' class="scsProOpt"';
									}
									$htmlInput = htmlScs::$htmlType('opt_values['. $optKey. ']', $htmlOscs);
									if(in_array($htmlType, array('hidden'))) {
										echo $htmlInput;	// Just show hidden field, without any row at all
										continue;
									}
								?>
								<tr class="<?php echo $catClass;?>">
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
									<td class="col-w-1perc">
										<?php echo $htmlInput;?>
									</td>
									<td class="col-w-60perc">
										<div id="scsFormOptDetails_<?php echo $optKey?>" class="scsOptDetailsShell">
										<?php switch($optKey) {

										}?>
										<?php
											if(isset($opt['add_sub_oscs']) && !empty($opt['add_sub_oscs'])) {
												if(is_string($opt['add_sub_oscs'])) {
													echo $opt['add_sub_oscs'];
												} elseif(is_callable($opt['add_sub_oscs'])) {
													echo call_user_func_array($opt['add_sub_oscs'], array($this->options));
												}
											}
										?>
										</div>
									</td>
								</tr>
							<?php }?>
						<?php } ?>
						<?php if(isset($optCatData['opts_html'])) { ?>
							<tr class="<?php echo $catClass;?>">
								<td colspan="4">
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