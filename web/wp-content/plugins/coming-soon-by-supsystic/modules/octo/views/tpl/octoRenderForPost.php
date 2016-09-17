<?php
	$mainCanvasStylesArr = array();
	$isMobile = $this->mobileDetect->isMobile();

	if(isset($this->octo['params']['bg_color']) && !empty($this->octo['params']['bg_color'])) {
		$mainCanvasStylesArr['background-color'] = $this->octo['params']['bg_color'];
	}
	if(isset($this->octo['params']['bg_img']) && !empty($this->octo['params']['bg_img']) && !$isMobile) {
		$mainCanvasStylesArr['background-image'] = 'url("'. $this->octo['params']['bg_img']. '")';

		if(isset($this->octo['params']['bg_img_pos']) && !empty($this->octo['params']['bg_img_pos'])) {
			switch( $this->octo['params']['bg_img_pos'] ) {
				case 'stretch':
					$mainCanvasStylesArr['background-position'] = 'center center';
					$mainCanvasStylesArr['background-repeat'] = 'no-repeat';
					$mainCanvasStylesArr['background-attachment'] = 'fixed';
					$mainCanvasStylesArr['-webkit-background-size'] = 'cover';
					$mainCanvasStylesArr['-moz-background-size'] = 'cover';
					$mainCanvasStylesArr['-o-background-size'] = 'cover';
					$mainCanvasStylesArr['background-size'] = 'cover';
					break;
				case 'center':
					$mainCanvasStylesArr['background-position'] = 'center center';
					$mainCanvasStylesArr['background-repeat'] = 'no-repeat';
					$mainCanvasStylesArr['background-attachment'] = 'scroll';
					$mainCanvasStylesArr['-webkit-background-size'] = 'auto';
					$mainCanvasStylesArr['-moz-background-size'] = 'auto';
					$mainCanvasStylesArr['-o-background-size'] = 'auto';
					$mainCanvasStylesArr['background-size'] = 'auto';
					break;
				case 'tile':
					$mainCanvasStylesArr['background-position'] = 'left top';
					$mainCanvasStylesArr['background-repeat'] = 'repeat';
					$mainCanvasStylesArr['background-attachment'] = 'scroll';
					$mainCanvasStylesArr['-webkit-background-size'] = 'auto';
					$mainCanvasStylesArr['-moz-background-size'] = 'auto';
					$mainCanvasStylesArr['-o-background-size'] = 'auto';
					$mainCanvasStylesArr['background-size'] = 'auto';
					break;
			}
		}
	}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<title><?php echo $this->octo['label'];?></title>
	<?php if (isset($this->octo['params']['font_family'])): ?>
	<link id="scsDefaultFont" rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=<?php echo urlencode($this->octo['params']['font_family']); ?>"/>
	<?php endif; ?>
	<?php wp_enqueue_scripts(); ?>
	<?php wp_print_styles(); ?>
	<?php echo $this->stylesScriptsHtml;?>
	<style type="text/css">
		#scsCanvas {
			<?php if(!empty($mainCanvasStylesArr)) {?>
			<?php echo utilsScs::arrToCss($mainCanvasStylesArr)?>
			<?php }?>
		}
	</style>
	<?php if(isset($this->octo['params']['fav_img']) && !empty($this->octo['params']['fav_img'])) { ?>
		<link rel="shortcut icon" href="<?php echo $this->octo['params']['fav_img'];?>" type="image/x-icon">
	<?php }?>
	<?php if(isset($this->octo['params']['keywords']) && !empty($this->octo['params']['keywords'])) { ?>
		<meta name="keywords" content="<?php echo htmlspecialchars($this->octo['params']['keywords']);?>">
	<?php }?>
	<?php if(isset($this->octo['params']['description']) && !empty($this->octo['params']['description'])) { ?>
		<meta name="description" content="<?php echo htmlspecialchars($this->octo['params']['description']);?>">
	<?php }?>
</head>
<body>
	<?php if($this->isEditMode) { ?>
		<div id="scsMainLoder"></div>
		<div class="scsMainBarHandle">
			<i class="octo-icon icon-blus-b"></i>
		</div>
		<form id="scsMainOctoForm">
			<div id="scsMainTopBar" class="scsMainTopBar supsystic-plugin">
				<div class="scsMainTopBarLeft">
					<a id="scsBackToAdminBtn" href="<?php echo $this->allPagesUrl?>" class="scsMainTopBarBtn">
						<i class="octo-icon icon-back"></i>
						<?php _e('WP Admin', SCS_LANG_CODE)?>
					</a>
					<span class="scsMainTopBarDelimiter">|</span>
				</div>
				<div class="scsMainTopBarCenter">
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Your Coming Soon page title.', SCS_LANG_CODE)?>"></i>
							<?php _e('Page Title')?>
							<?php echo htmlScs::text('label', array('value' => $this->octo['label']))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Font family for your page. You can always change font for any text element using text editor tool.', SCS_LANG_CODE)?>"></i>
							<?php _e('Font', SCS_LANG_CODE)?>
							<?php echo htmlScs::fontsList('params[font_family]', array(
								'value' => @$this->octo['params']['font_family'],
								'attrs' => 'id="scsFontFamilySelect" class="chosen"',
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Background color of your page. You can also set background for any block on page.', SCS_LANG_CODE)?>"></i>
							<?php _e('Background Color', SCS_LANG_CODE)?>
							<div class="scsColorpickerInputShell scsOctoBgColor">
								<?php echo htmlScs::text('params[bg_color]', array(
									'attrs' => 'class="scsColorpickerInput"',
									'value' => isset($this->octo['params']['bg_color']) ? $this->octo['params']['bg_color'] : '#fff',
								));?>
							</div>
						</label>
					</div>
					<div class="scsMainOctoOpt scsMainBgImgOptShell">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Set image as background. If it is set - it can overlap your Background Color option. Just click on the image from the right to change Background Image source.', SCS_LANG_CODE)?>"></i>
							<?php _e('Background Image', SCS_LANG_CODE)?>
							<?php
								$bgImgUrl = isset($this->octo['params']['bg_img']) ? $this->octo['params']['bg_img'] : '';
							?>
							<a class="scsOctoBgImgBtn" href="#">
								<img class="scsOctoBgImg" data-noimg-url="<?php echo $this->noImgUrl;?> "src="<?php echo $bgImgUrl ? $bgImgUrl : $this->noImgUrl;?>" />
							</a>
							<a
								href="#"
								class="scsOctoBgImgRemove scsMainTopBarBtn"
								<?php if(!$bgImgUrl) { ?>
									style="display: none;"
								<?php }?>
							>
								<i class="fa fa-times"></i>
							</a>
							<?php echo htmlScs::hidden('params[bg_img]', array(
								'value' => $bgImgUrl,
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Background Image Position will define how we should show image on your background. Will work only if you will select Background Image.', SCS_LANG_CODE)?>"></i>
							<?php _e('Background Image Position', SCS_LANG_CODE)?>
							<?php echo htmlScs::selectbox('params[bg_img_pos]', array(
								'options' => array('stretch' => __('Stretch', SCS_LANG_CODE), 'center' => __('Center', SCS_LANG_CODE), 'tile' => __('Tile', SCS_LANG_CODE)),
								'value' => isset($this->octo['params']['bg_img_pos']) ? $this->octo['params']['bg_img_pos'] : '',
								'attrs' => 'class="chosen" style="width: 100px;"',
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<span class="scsTxtTop">
								<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Keywords meta tag for your page.', SCS_LANG_CODE)?>"></i>
								<?php _e('Page Keywords')?>
							</span>
							<?php echo htmlScs::textarea('params[keywords]', array(
								'value' => isset($this->octo['params']['keywords']) ? $this->octo['params']['keywords'] : '',
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<span class="scsTxtTop">
								<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Description meta tag for your page.', SCS_LANG_CODE)?>"></i>
								<?php _e('Page Description')?>
							</span>
							<?php echo htmlScs::textarea('params[description]', array(
								'value' => isset($this->octo['params']['description']) ? $this->octo['params']['description'] : '',
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Maintenance date start - day when you decided to enable Coming Soon page. Required for timers and progress bars on your page.', SCS_LANG_CODE)?>"></i>
							<?php _e('Date Start', SCS_LANG_CODE)?>
							<?php echo htmlScs::datetimepicker('params[maint_start]', array(
								'value' => @$this->octo['params']['maint_start'],
								'attrs' => 'style="width: 130px;"',
								'format' => 'm/d/Y H:i'
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Maintenance date end - day when you will finish your site development and disable Coming Soon mode. Required for timers and progress bars on your page.', SCS_LANG_CODE)?>"></i>
							<?php _e('Date End', SCS_LANG_CODE)?>
							<?php echo htmlScs::datetimepicker('params[maint_end]', array(
								'value' => @$this->octo['params']['maint_end'],
								'attrs' => 'style="width: 130px;"',
								'format' => 'm/d/Y H:i'
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Disable turning off of Coming soon mode after time expired.', SCS_LANG_CODE)?>"></i>
							<?php _e('Disable after time ends', SCS_LANG_CODE)?>
							<?php echo htmlScs::checkbox('params[maint_end_disable_site]', array(
								'checked' => isset($this->octo['params']['maint_end_disable_site']) && $this->octo['params']['maint_end_disable_site'] ? $this->octo['params']['maint_end_disable_site'] : '',
							))?>
						</label>
					</div>

					<div class="scsMainOctoOpt scsMainFavImgOptShell">
						<label>
							<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Set image as favicon for your Coming Soon page. Just click on the image from the right to change Favicon Image source.', SCS_LANG_CODE)?>"></i>
							<?php _e('Favicon Image', SCS_LANG_CODE)?>
							<?php
								$favImgUrl = isset($this->octo['params']['fav_img']) ? $this->octo['params']['fav_img'] : '';
							?>
							<a class="scsOctoFavImgBtn" href="#">
								<img class="scsOctoFavImg" data-noimg-url="<?php echo $this->noImgUrl;?> "src="<?php echo $favImgUrl ? $favImgUrl : $this->noImgUrl;?>" />
							</a>
							<a
								href="#"
								class="scsOctoFavImgRemove scsMainTopBarBtn"
								<?php if(!$favImgUrl) { ?>
									style="display: none;"
								<?php }?>
							>
								<i class="fa fa-times"></i>
							</a>
							<?php echo htmlScs::hidden('params[fav_img]', array(
								'value' => $favImgUrl,
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<label>
							<span>
								<i class="fa fa-question supsystic-tooltip" title="<?php echo _e('Connecting Google Analytics on page', SCS_LANG_CODE)?>"></i>
								<?php _e('Google Analytics Tracking ID')?>
							</span>
							<?php echo htmlScs::text('params[ga_tracking_id]', array(
								'value' => isset($this->octo['params']['ga_tracking_id']) ? $this->octo['params']['ga_tracking_id'] : '',
								'placeholder' => 'XX-XXXXX-X'
							))?>
						</label>
					</div>
					<div class="scsMainOctoOpt">
						<a href="#" class="scsResetTplBtn button button-primary">
							<i class="fa fa-retweet"></i>
							<?php _e('Reset Template', SCS_LANG_CODE)?>
						</a>
					</div>
					<div id="scsMainOctoOptMore" class="scsMainOctoOpt">
						<a href="#" id="scsMainOctoOptMoreBtn" class="scsMainTopBarBtn">
							<?php _e('More', SCS_LANG_CODE)?><br />
							<i class="fa fa-caret-down"></i>
						</a>
					</div>
				</div>
				<div class="scsMainTopBarRight">
					<a id="scsPreviewTplBtn" href="<?php echo uriScs::_(array('baseUrl' => SCS_SITE_URL, 'tpl_preview' => 1));?>" target="_blank" class="scsMainTopBarBtn scsPreviewTplBtn"><?php _e('PREVIEW', SCS_LANG_CODE)?></a>
					<button class="button-primary scsMainSaveBtn" data-txt="<?php _e('Save', SCS_LANG_CODE)?>">
						<div class="octo-icon octo-icon-2x icon-save-progress glyphicon-spin scsMainSaveBtnLoader"></div>
						<span class="scsMainSaveBtnTxt"><?php _e('Save', SCS_LANG_CODE)?></span>
					</button>
				</div>
			</div>
			<div id="scsMainTopSubBar" class="scsMainTopSubBar supsystic-plugin"></div>
		</form>
		<?php foreach($this->originalBlocksByCategories as $cat) { ?>
		<div class="navmenu navmenu-default navmenu-fixed-left offcanvas in canvas-slid scsBlocksBar" data-cid="<?php echo $cat['id']?>">
			<ul class="nav navmenu-nav scsBlocksList">
				<?php foreach($cat['blocks'] as $block) { ?>
					<li class="scsBlockElement" data-id="<?php echo $block['id']?>">
						<img src="<?php echo $block['img_url']?>" class="scsBlockElementImg" />
					</li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
		<div class="navmenu navmenu-default navmenu-fixed-left offcanvas in canvas-slid scsMainBar">
			<a target="_blank" href="https://supsystic.com/">
				<i class="fa fa-gear fa-4x scsMainIcon"></i>
			</a>
			<ul class="nav navmenu-nav">
				<?php foreach($this->originalBlocksByCategories as $cat) { ?>
					<li class="scsCatElement" data-id="<?php echo $cat['id']?>">
						<a href="#">
							<?php /*?><div class="scsCatElementIcon" style="background-image: url(<?php echo $cat['icon_url']?>)"></div><?php */?>
							<?php echo $cat['label']?>
						</a>
					</li>
				<?php }?>
			</ul>
		</div>
		<script type="text/javascript">
			var g_scsBlocksById = {};
			<?php foreach($this->originalBlocksByCategories as $cat) { ?>
				<?php foreach($cat['blocks'] as $block) { ?>
					g_scsBlocksById[ <?php echo $block['id']?> ] = <?php echo utilsScs::jsonEncode($block)?>;
				<?php }?>
			<?php }?>
		</script>
	<?php }?>
	<div id="scsCanvas">
		<?php if ($isMobile && isset($this->octo['params']['bg_img']) && !empty($this->octo['params']['bg_img'])): ?>
			<img src="<?php echo $this->octo['params']['bg_img']; ?>" class="bg" id="scsCanvasBG">
		<?php endif; ?>

		<?php if(!empty($this->octo['blocks'])) {?>
			<?php foreach($this->octo['blocks'] as $block) { ?>
				<?php echo $block['rendered_html']; ?>
			<?php }?>
		<?php }?>
		<?php dispatcherScs::doAction('templateEnd', $this->isEditMode);?>
	</div>
	<?php echo $this->commonFooter;?>
	<?php if($this->isEditMode) {
		echo $this->editorFooter;
	} else {
		echo $this->footer;
	}?>
</body>
</html>
