<div class="temlplate-list">
	<?php foreach($this->presetTemplates as $tpl) { ?>
		<?php $isPromo = isset($tpl['promo']) && $tpl['promo'];?>
		<?php $promoClass = $isPromo ? 'sup-promo' : '';?>
		<div class="temlplate-list-item preset <?php echo $promoClass;?>" data-id="<?php echo $isPromo ? 0 : $tpl['id']?>">
			<a href="<?php echo ($isPromo ? $tpl['promo_link'] : '#')?>" <?php echo ($isPromo ? 'target="_blank"' : '')?> class="button template-list-main-select">
				<?php $isPromo ? _e('Available in PRO', SCS_LANG_CODE) : _e('Select', SCS_LANG_CODE);?>
			</a>
			<img src="<?php echo $tpl['img_preview_url']?>" class="ppsTplPrevImg" />
			<div class="preset-overlay">
				<h3>
					<span class="ppsTplLabel"><?php echo $tpl['label']?></span>
				</h3>
				<h4 style="margin-top: 60px;">
					<?php if($isPromo) { ?>
						<a href="<?php echo $tpl['promo_link']?>" target="_blank" class="button button-primary preset-select-btn <?php echo $promoClass;?>"><?php _e('Get in PRO', SCS_LANG_CODE)?></a>
					<?php } else { ?>
						<a href="<?php echo frameScs::_()->getModule('octo')->getEditLink( $tpl['id'] );?>" target="_blank" class="button button-primary preset-select-btn"><?php _e('Edit Template', SCS_LANG_CODE)?></a>
					<?php }?>
				</h4>
			</div>
		</div>
	<?php }?>
	<div style="clear: both;"></div>
</div>