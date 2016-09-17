<?php
	//do_action( 'admin_footer' );
	//do_action( 'wp_footer');
	do_action( 'customize_controls_print_footer_scripts' );
?>
<style type="text/css">
	/*to leave some place at the top for admin bar*/
	#scsCanvas {
		margin-top: 93px;
	}
	.scsMainBar, .scsBlocksBar {
		top: 93px;
	}
</style>
<!--Images selection button example-->
<div id="scsChangeImgBtnExl" class="scsChangeImgBtn">
	<div class="scsChangeImgBtnTxt" style="">
		<?php _e('Select Image', SCS_LANG_CODE)?>
	</div>
	<i class="octo-icon octo-icon-lg icon-image scsChangeImgBtnIcon"></i>
</div>
<!--Block menus example-->
<div id="scsBlockMenuExl" class="scsBlockMenu">
	<div class="scsBlockMenuEl" data-menu="align">
		<div class="scsBlockMenuElTitle scsBlockMenuElAlignTitle">
			<?php _e('Content align', SCS_LANG_CODE)?>
		</div>
		<div class="scsBlockMenuElAlignContent row">
			<div class="col-sm-4 scsBlockMenuElElignBtn" data-align="left">
				<i class="octo-icon octo-icon-2x icon-aligne-left"></i>
			</div>
			<div class="col-sm-4 scsBlockMenuElElignBtn" data-align="center">
				<i class="octo-icon octo-icon-2x icon-aligne-center"></i>
			</div>
			<div class="col-sm-4 scsBlockMenuElElignBtn" data-align="right">
				<i class="octo-icon octo-icon-2x icon-aligne-right"></i>
			</div>
		</div>
		<?php echo htmlScs::hidden('params[align]')?>
	</div>
	<div class="scsBlockMenuEl" data-menu="add_slide">
		<div class="scsBlockMenuElAct">
			<i class="octo-icon octo-icon-lg icon-image scsChangeImgBtnIcon"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Add Slide', SCS_LANG_CODE)?>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="add_gal_item">
		<div class="scsBlockMenuElAct">
			<i class="octo-icon octo-icon-lg icon-image scsChangeImgBtnIcon"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Add Image', SCS_LANG_CODE)?>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="add_menu_item">
		<div class="scsBlockMenuElAct">
			<i class="octo-icon octo-icon-lg icon-plus-s"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Add Menu Item', SCS_LANG_CODE)?>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="edit_slides">
		<div class="scsBlockMenuElAct">
			<i class="octo-icon octo-icon-lg icon-manage scsChangeImgBtnIcon"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Manage Slides', SCS_LANG_CODE)?>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="fill_color">
		<div class="scsBlockMenuElAct">
			<?php echo htmlScs::checkbox('params[fill_color_enb]')?>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Fill Color', SCS_LANG_CODE)?>
		</div>
		<div class="scsBlockMenuElRightAct">
			<div class="scsColorpickerInputShell">
				<?php echo htmlScs::text('params[fill_color]', array(
					'attrs' => 'class="scsColorpickerInput"'
				));?>
			</div>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="bg_img">
		<div class="scsBlockMenuElAct">
			<?php echo htmlScs::checkbox('params[bg_img_enb]')?>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Background Image...', SCS_LANG_CODE)?>
		</div>
		<div class="scsBlockMenuElRightAct">
			<i class="octo-icon octo-icon-lg icon-image"></i>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="add_field">
		<div class="scsBlockMenuElAct">
			<i class="octo-icon octo-icon-lg icon-plus-s"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Add Field', SCS_LANG_CODE)?>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="sub_settings">
		<div class="scsBlockMenuElAct">
			<i class="glyphicon glyphicon-send"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Subscribe Settings', SCS_LANG_CODE)?>
		</div>
	</div>
	<div class="scsBlockMenuEl" data-menu="add_grid_item">
		<div class="scsBlockMenuElAct">
			<i class="octo-icon octo-icon-lg icon-image"></i>
		</div>
		<div class="scsBlockMenuElTitle">
			<?php _e('Add Column', SCS_LANG_CODE)?>
		</div>
	</div>
</div>
<!--Block toolbar example-->
<div id="scsBlockToolbarEx" class="scsBlockToolbar scsToolbar">
	<div class="scsToolItem scsBlockSettings octo-icon icon-options"></div>
	<div class="scsToolItem scsBlockMove octo-icon icon-up-down"></div>
	<div class="scsToolItem scsBlockRemove octo-icon icon-trash"></div>
</div>
<!--Manage slides wnd-->
<div class="modal fade" id="scsManageSlidesWnd" tabindex="-1" role="dialog" aria-labelledby="scsManageSlidesWndLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="button close" data-dismiss="modal" aria-label="Close">
					<i class="octo-icon octo-icon-2x icon-close-s" aria-hidden="true"></i>
				</button>
				<h4 class="modal-title"><?php _e('DRAG AND DROP SLIDES TO ORDER', SCS_LANG_CODE)?></h4>
			</div>
			<div class="modal-body">
				<div class="scsSlidesListPrev">
					
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="button scsSlideManageAddBtn" style="float: left;">
					<i class="octo-icon octo-icon-lg icon-plus-s"></i>
					<?php _e('Add Slide', SCS_LANG_CODE)?>
				</a>
				<button type="button" class="button-primary scsManageSlidesSaveBtn"><?php _e('Save', SCS_LANG_CODE)?></button>
			</div>
		</div>
	</div>
</div>
<!--Manage slides - slide example-->
<div id="scsSlideManageItemExl" class="scsSlideManageItem">
	<div class="scsSlideManageItemToolbar scsToolbar">
		<div class="scsToolItem scsSlideManageItemRemove octo-icon icon-trash"></div>
	</div>
	<img src="" />
</div>
<!--Manage gallery item menu-->
<div id="scsElMenuGalItemExl" class="scsElMenu" style="min-width: 140px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsImgChangeBtn">
				<i class="glyphicon glyphicon-picture"></i>
				<?php _e('Select image', SCS_LANG_CODE)?>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsImgLinkBtn" data-sub-panel-show="link">
				<i class="glyphicon glyphicon-link"></i>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsImgMoveBtn">
				<i class="glyphicon glyphicon-move"></i>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="link">
			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('link', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('gal_item_link')?>
			</label>
			<label class="scsElMenuSubPanelRow">
				<?php echo htmlScs::checkbox('gal_item_link_new_wnd')?>
				<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('open link in a new window', SCS_LANG_CODE)?></span>
			</label>
		</div>
	</div>
</div>
<!--Image menu-->
<div id="scsElMenuImgExl" class="scsElMenu" style="min-width: 260px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsImgChangeBtn">
				<label>
					<?php echo htmlScs::radiobutton('type', array('value' => 'img'))?>
					<?php _e('Select image', SCS_LANG_CODE)?>
					<i class="glyphicon glyphicon-picture"></i>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsImgVideoSetBtn" data-sub-panel-show="video">
				<label>
					<?php echo htmlScs::radiobutton('type', array('value' => 'video'))?>
					<?php _e('Video', SCS_LANG_CODE)?>
					<i class="fa fa-video-camera"></i>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsLinkBtn" data-sub-panel-show="link">
				<label>
					<i class="glyphicon glyphicon-link"></i>
					<?php _e('Link', SCS_LANG_CODE)?>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="video">
			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('link', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('video_link')?>
			</label>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="link">
			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('link', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('icon_item_link')?>
			</label>
			<div style="display: none;" class="scsPostLinkDisabled" data-postlink-to=":parent label [name='icon_item_link']"></div>

			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('title', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('icon_item_title')?>
			</label>
			<label class="scsElMenuSubPanelRow">
				<?php echo htmlScs::checkbox('icon_item_link_new_wnd')?>
				<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('open link in a new window', SCS_LANG_CODE)?></span>
			</label>
		</div>
	</div>
</div>
<!--Menu image menu-->
<div id="scsElMenuMenuItemImgExl" class="scsElMenu" style="min-width: 175px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsImgChangeBtn">
				<i class="glyphicon glyphicon-picture"></i>
				<?php _e('Select image', SCS_LANG_CODE)?>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
	</div>
</div>
<!--Add menu item wnd-->
<div class="modal fade" id="scsAddMenuItemWnd" tabindex="-1" role="dialog" aria-labelledby="scsAddMenuItemWndLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="button close" data-dismiss="modal" aria-label="Close">
					<i class="octo-icon octo-icon-2x icon-close-s" aria-hidden="true"></i>
				</button>
				<h4 class="modal-title"><?php _e('Menu Item Settings', SCS_LANG_CODE)?></h4>
			</div>
			<div class="modal-body scsElMenuSubPanel">
				<label class="scsElMenuSubPanelRow">
					<span class="mce-input-name-txt"><?php _e('text', SCS_LANG_CODE)?></span>
					<?php echo htmlScs::text('menu_item_text')?>
				</label>
				<label class="scsElMenuSubPanelRow">
					<span class="mce-input-name-txt"><?php _e('link', SCS_LANG_CODE)?></span>
					<?php echo htmlScs::text('menu_item_link')?>
				</label>
				<label class="scsElMenuSubPanelRow">
					<?php echo htmlScs::checkbox('menu_item_new_window')?>
					<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('open link in a new window', SCS_LANG_CODE)?></span>
				</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="button-primary scsAddMenuItemSaveBtn"><?php _e('Save', SCS_LANG_CODE)?></button>
			</div>
		</div>
	</div>
</div>
<!--Input menu-->
<div id="scsElMenuInputExl" class="scsElMenu" style="min-width: 175px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn">
				<label>
					<?php _e('Required', SCS_LANG_CODE)?>
					<?php echo htmlScs::checkbox('input_required')?>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuMoveHandlerPlace"></div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
	</div>
</div>
<!--Input Button menu-->
<div id="scsElMenuInputBtnExl" class="scsElMenu" style="min-width: 30px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsImgMoveBtn">
				<i class="glyphicon glyphicon-move"></i>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
	</div>
</div>
<!--Standart Button menu-->
<div id="scsElMenuBtnExl" class="scsElMenu" style="min-width: 250px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsLinkBtn" data-sub-panel-show="link">
				<label>
					<i class="glyphicon glyphicon-link"></i>
					<?php _e('Link', SCS_LANG_CODE)?>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsColorBtn" data-sub-panel-show="color">
				<label>
					<?php _e('Color', SCS_LANG_CODE)?>
					<div class="scsColorpickerInputShell">
						<?php echo htmlScs::text('color', array(
							'attrs' => 'class="scsColorpickerInput"'
						));?>
					</div>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="link">
			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('link', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('btn_item_link')?>
			</label>
			<div style="display: none;" class="scsPostLinkDisabled" data-postlink-to=":parent label [name='btn_item_link']"></div>

			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('title', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('btn_item_title')?>
			</label>
			<label class="scsElMenuSubPanelRow">
				<?php echo htmlScs::checkbox('btn_item_link_new_wnd')?>
				<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('open link in a new window', SCS_LANG_CODE)?></span>
			</label>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="color"></div>
	</div>
</div>
<!--Grid Column menu-->
<div id="scsElMenuGridColExl" class="scsElMenu" style="min-width: 370px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn" style="">
				<?php echo htmlScs::checkbox('enb_fill_color')?>
			</div>
			<div class="scsElMenuBtn scsColorBtn" data-sub-panel-show="color">
				<label>
					<?php _e('Fill Color', SCS_LANG_CODE)?>
					<div class="scsColorpickerInputShell">
						<?php echo htmlScs::text('color', array(
							'attrs' => 'class="scsColorpickerInput"'
						));?>
					</div>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn" style="">
				<?php echo htmlScs::checkbox('enb_bg_img')?>
			</div>
			<div class="scsElMenuBtn scsImgChangeBtn">
				<label>
					<?php _e('Background Image', SCS_LANG_CODE)?>
					<i class="glyphicon glyphicon-picture"></i>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="color"></div>
	</div>
</div>
<!--Menu Icon menu:)-->
<div id="scsElMenuIconExl" class="scsElMenu" style="min-width: 414px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsIconLibBtn">
				<i class="fa fa-lg fa-pencil"></i>
				<?php _e('Change Icon', SCS_LANG_CODE)?>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn"  data-sub-panel-show="size">
				<i class="glyphicon glyphicons-resize-small"></i>
				<?php _e('Icon Size', SCS_LANG_CODE)?>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsColorBtn" data-sub-panel-show="color">
				<?php _e('Color', SCS_LANG_CODE)?>
				<div class="scsColorpickerInputShell">
					<?php echo htmlScs::text('color', array(
						'attrs' => 'class="scsColorpickerInput"'
					));?>
				</div>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsLinkBtn" data-sub-panel-show="link">
				<label>
					<i class="glyphicon glyphicon-link"></i>
					<?php _e('Link', SCS_LANG_CODE)?>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel scsElMenuSubPanelIconSize" data-sub-panel="size">
			<span data-size="fa-lg">lg</span>
			<span data-size="fa-2x">2x</span>
			<span data-size="fa-3x">3x</span>
			<span data-size="fa-4x">4x</span>
			<span data-size="fa-5x">5x</span>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="color"></div>
		<div class="scsElMenuSubPanel" data-sub-panel="link">
			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('link', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('icon_item_link')?>
			</label>
			<div style="display: none;" class="scsPostLinkDisabled" data-postlink-to=":parent label [name='icon_item_link']"></div>
			
			<label class="scsElMenuSubPanelRow">
				<span class="mce-input-name-txt"><?php _e('title', SCS_LANG_CODE)?></span>
				<?php echo htmlScs::text('icon_item_title')?>
			</label>
			<label class="scsElMenuSubPanelRow">
				<?php echo htmlScs::checkbox('icon_item_link_new_wnd')?>
				<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('open link in a new window', SCS_LANG_CODE)?></span>
			</label>
		</div>
	</div>
</div>
<!--Delimiter menu-->
<div id="scsElMenuDelimiterExl" class="scsElMenu" style="min-width: 370px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsColorBtn" data-sub-panel-show="color">
				<label>
					<?php _e('Color', SCS_LANG_CODE)?>
					<div class="scsColorpickerInputShell">
						<?php echo htmlScs::text('color', array(
							'attrs' => 'class="scsColorpickerInput"'
						));?>
					</div>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="color"></div>
	</div>
</div>
<!--Timer menu-->
<div id="scsElMenuTimerExl" class="scsElMenu" style="min-width: 370px;">
	<div class="scsElMenuContent">
		<div class="scsElMenuMainPanel">
			<div class="scsElMenuBtn scsColorBtn" data-sub-panel-show="color">
				<label>
					<?php _e('Color', SCS_LANG_CODE)?>
					<div class="scsColorpickerInputShell">
						<?php echo htmlScs::text('color', array(
							'attrs' => 'class="scsColorpickerInput"'
						));?>
					</div>
				</label>
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuForm">
				<span>Date Format:</span>
				<input type="text" class="dateFormat" placeholder="dhms">
			</div>
			<div class="scsElMenuBtnDelimiter"></div>
			<div class="scsElMenuBtn scsRemoveElBtn">
				<i class="glyphicon glyphicon-trash"></i>
			</div>
		</div>
		<div class="scsElMenuSubPanel" data-sub-panel="color"></div>
	</div>
</div>
<!--Add field wnd-->
<div class="modal fade" id="scsAddFieldWnd" tabindex="-1" role="dialog" aria-labelledby="scsAddFieldWndLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="button close" data-dismiss="modal" aria-label="Close">
					<i class="octo-icon octo-icon-2x icon-close-s" aria-hidden="true"></i>
				</button>
				<h4 class="modal-title"><?php _e('Field Settings', SCS_LANG_CODE)?></h4>
			</div>
			<div class="modal-body scsElMenuSubPanel">
				<label class="scsElMenuSubPanelRow">
					<span class="mce-input-name-txt"><?php _e('name', SCS_LANG_CODE)?></span>
					<?php echo htmlScs::text('new_field_name')?>
				</label>
				<label class="scsElMenuSubPanelRow">
					<span class="mce-input-name-txt"><?php _e('label', SCS_LANG_CODE)?></span>
					<?php echo htmlScs::text('new_field_label')?>
				</label>
				<label class="scsElMenuSubPanelRow">
					<span class="mce-input-name-txt"><?php _e('type', SCS_LANG_CODE)?></span>
					<?php echo htmlScs::selectbox('new_field_html', array('options' => array(
						'text' => __('Text', SCS_LANG_CODE),
						'email' => __('Email', SCS_LANG_CODE),
					)))?>
				</label>
				<label class="scsElMenuSubPanelRow">
					<?php echo htmlScs::checkbox('new_field_reuired')?>
					<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('required', SCS_LANG_CODE)?></span>
				</label>
			</div>
			<div class="modal-footer">
				<button type="button" class="button-primary scsAddFieldSaveBtn"><?php _e('Save', SCS_LANG_CODE)?></button>
			</div>
		</div>
	</div>
</div>
<!--Subscribe settings wnd-->
<div class="modal fade" id="scsSubSettingsWnd" tabindex="-1" role="dialog" aria-labelledby="scsSubSettingsWndLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="button close" data-dismiss="modal" aria-label="Close">
					<i class="octo-icon octo-icon-2x icon-close-s" aria-hidden="true"></i>
				</button>
				<h4 class="modal-title"><?php _e('Subscribe Settings', SCS_LANG_CODE)?></h4>
			</div>
			<div class="modal-body scsElMenuSubPanel scsSettingFieldsShell">
				<div id="scsSubSettingsWndTabs">
					<h3 class="nav-tab-wrapper" style="margin-bottom: 0px; margin-top: 12px;">
						<a class="nav-tab nav-tab-active" href="#scsSubSetWndMainTab">
							<?php _e('Main Settings', SCS_LANG_CODE)?>
						</a>
						<a class="nav-tab" href="#scsSubSetWndConfirmTab">
							<?php _e('Confirmation', SCS_LANG_CODE)?>
						</a>
						<a class="nav-tab" href="#scsSubSetWndNewSubTab">
							<?php _e('New Subscriber', SCS_LANG_CODE)?>
						</a>
					</h3>
					<div id="scsSubSetWndMainTab" class="scsTabContent">
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('Subscribe to', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::selectbox('sub_dest', array(
								'options' => frameScs::_()->getModule('subscribe')->getDestListForSelect(),
							))?>
						</label>
						<label class="scsElMenuSubPanelRow scsSubDestRow scsSubDestRow_aweber">
							<span class="mce-input-name-txt"><?php _e('Aweber Unique List ID', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_aweber_listname', array(
								'attrs' => 'title="'. esc_html(__('You can find List ID under your Aweber account', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow scsSubDestRow scsSubDestRow_aweber">
							<span class="mce-input-name-txt"><?php _e('Aweber AD Tracking', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_aweber_adtracking', array(
								'attrs' => 'title="'. esc_html(__('You can easy track your subscribers using this feature. You can find AD Tracking under your Aweber account.', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow scsSubDestRow scsSubDestRow_mailchimp">
							<span class="mce-input-name-txt"><?php _e('MailChimp API key', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_mailchimp_api_key', array(
								'attrs' => 'title="'. esc_html(__('To find your MailChimp API Key login to your mailchimp account at http://mailchimp.com then from the left main menu, click on your Username, then select "Account" in the flyout menu. From the account page select "Extras", "API Keys". Your API Key will be listed in the table labeled "Your API Keys". Copy / Paste your API key into "MailChimp API key" field here.', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow scsSubDestRow scsSubDestRow_mailchimp">
							<span class="mce-input-name-txt"><?php _e('Lists for subscribe', SCS_LANG_CODE)?></span>
							<div id="scsMailchimpListsShell" style="display: none;">
								<?php echo htmlScs::selectlist('sub_mailchimp_lists', array(
									'attrs' => 'id="scsMailchimpLists" class="" data-placeholder="'. __('Choose Lists', SCS_LANG_CODE). '"',
								))?>
							</div>
							<span id="scsMailchimpNoApiKey"><?php _e('Enter API key - and your list will appear here', SCS_LANG_CODE)?></span>
							<span id="scsMailchimpMsg"></span>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('"confirmation sent" message', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_confirm_sent', array(
								'attrs' => 'data-default="'. esc_html(__('Confirmation link was sent to your email address. Check your email!', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('subscribe success message', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_success', array(
								'attrs' => 'data-default="'. esc_html(__('Thank you for subscribe!', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('email error message', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_invalid_email', array(
								'attrs' => 'data-default="'. esc_html(__('Empty or invalid email', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('redirect after subscription URL', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_redirect_url', array(
								'attrs' => 'data-default=""',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<?php echo htmlScs::checkbox('sub_ignore_confirm')?>
							<span class="mce-input-name-txt mce-input-name-not-first"><?php _e('create Subscriber without confirmation', SCS_LANG_CODE)?></span>
						</label>
					</div>
					<div id="scsSubSetWndConfirmTab" class="scsTabContent">
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('confirmation email subject', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_confirm_mail_subject', array(
								'attrs' => 'data-default="'. esc_html(__('Confirm subscription on [sitename]', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('confirmation email From field', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_confirm_mail_from', array(
								'attrs' => 'data-default="'. $this->adminEmail. '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('confirmation email text', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::textarea('sub_txt_confirm_mail_message', array(
								'attrs' => 'data-default="'. esc_html(__('You subscribed on site <a href="[siteurl]">[sitename]</a>. Follow <a href="[confirm_link]">this link</a> to complete your subscription. If you did not subscribe here - just ignore this message.', SCS_LANG_CODE)). '"',
							))?>
						</label>
					</div>
					<div id="scsSubSetWndNewSubTab" class="scsTabContent">
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('new Subscriber email subject', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_subscriber_mail_subject', array(
								'attrs' => 'data-default="'. esc_html(__('[sitename] Your username and password', SCS_LANG_CODE)). '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('new Subscriber email From field', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::text('sub_txt_subscriber_mail_from', array(
								'attrs' => 'data-default="'. $this->adminEmail. '"',
							))?>
						</label>
						<label class="scsElMenuSubPanelRow">
							<span class="mce-input-name-txt"><?php _e('new Subscriber email text', SCS_LANG_CODE)?></span>
							<?php echo htmlScs::textarea('sub_txt_subscriber_mail_message', array(
								'attrs' => 'data-default="'. esc_html(__('Username: [user_login]<br />Password: [password]<br />[login_url]', SCS_LANG_CODE)). '"',
							))?>
						</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="button-primary scsSubSettingsSaveBtn"><?php _e('Save', SCS_LANG_CODE)?></button>
			</div>
		</div>
	</div>
</div>
<!--Icons library wnd-->
<div class="modal fade" id="scsIconsLibWnd" tabindex="-1" role="dialog" aria-labelledby="scsIconsLibWndLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="button close" data-dismiss="modal" aria-label="Close">
					<i class="octo-icon octo-icon-2x icon-close-s" aria-hidden="true"></i>
				</button>
				<h4 class="modal-title"><?php _e('Icons Library', SCS_LANG_CODE)?></h4>
			</div>
			<div class="modal-body scsElMenuSubPanel">
				<div id="scsSubSettingsWndTabs">
					<?php echo htmlScs::text('icon_search', array(
						'attrs' => 'class="scsIconsLibSearchTxt" placeholder="'. esc_html(__('Search, for example - pencil, music, ...', SCS_LANG_CODE)). '"',
					))?>
					<div class="scsIconsLibList row"></div>
					<div class="scsIconsLibEmptySearch alert alert-info" style="display: none;"><?php _e('Nothing found for <span class="scsNothingFoundKeys"></span>, maybe try to search something else?', SCS_LANG_CODE)?></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="button-primary scsIconsLibSaveBtn"><?php _e('Close', SCS_LANG_CODE)?></button>
			</div>
		</div>
	</div>
</div>
<!--Movable handler-->
<div id="scsMoveHandlerExl" class="scsMoveHandler scsShowSmooth">
	<i class="fa fa-arrows scsOptIconBtn"></i>
</div>
<!--Remove row btn-->
<div id="scsRemoveRowBtnExl" class="scsRemoveRowBtn scsShowSmooth scsElMenuBtn">
	<i class="fa fa-trash-o scsOptIconBtn"></i>
</div>