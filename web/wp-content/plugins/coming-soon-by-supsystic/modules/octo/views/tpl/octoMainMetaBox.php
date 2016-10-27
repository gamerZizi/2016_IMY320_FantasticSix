<div class="scsActivateForPostShell" <?php if($this->isPostConverted) {?>style="display: none;"<?php }?>>
	<div class="misc-pub-section">
		<a href="#" class="button button-primary scsActivatePostBtn" data-pid="<?php echo $this->post->ID?>">
			<?php printf(__('Activate %s', SCS_LANG_CODE), SCS_OUR_NAME)?>
		</a>
	</div>
</div>
<div class="scsPostSettingsShell" <?php if(!$this->isPostConverted) {?>style="display: none;"<?php }?>>
	<div class="scsPostSettingsContent">
		<div class="misc-pub-section dashicons-screenoptions dashicons-before">
			<?php _e('Blocks usage')?>: <?php echo (string) $this->usedBlocksNumber;?>
		</div>
	</div>
	<div class="scsPostSettingsFooter">
		<a href="#" class="scsReturnPostFromScso" data-pid="<?php echo $this->post->ID?>">
			<?php printf(__('Deactivate %s', SCS_LANG_CODE), SCS_OUR_NAME)?>
		</a>
		<a href="#" target="_blank" class="button button-primary scsEditTplBtn" data-pid="<?php echo $this->post->ID?>">
			<?php _e('Build Page', SCS_LANG_CODE)?>
		</a>
		<div style="clear: both;"></div>
	</div>
</div>