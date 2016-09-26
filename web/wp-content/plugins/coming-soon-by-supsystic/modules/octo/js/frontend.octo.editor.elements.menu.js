function scsElementMenu(menuOriginalId, element, btnsClb, params) {
	params = params || {};
	this._$ = null;;
	this._animationSpeed = g_scsAnimationSpeed;
	this._menuOriginalId = menuOriginalId;
	this._element = element;
	this._btnsClb = btnsClb;
	this._visible = false;
	this._isMovable = false;
	this._changeable = params.changeable ? params.changeable : false;
	this._inAnimation = false;
	this._id = 'scsElMenu_'+ mtRand(1, 99999);
	this.init();
}
scsElementMenu.prototype.getId = function() {
	return this._id;
};
scsElementMenu.prototype.setMovable = function(state) {
	this._isMovable = state;
};
scsElementMenu.prototype.setChangeable = function(state) {
	this._changeable = state;
};
scsElementMenu.prototype._afterAppendToElement = function() {
	if(this._changeable) {
		this._updateType();
	}
};
scsElementMenu.prototype._updateType = function(refreshCheck) {
	if(this._changeable) {
		var type = this._element.get('type');
		this._$
			.find('[name=type]').removeAttr('checked')
			.filter('[value='+ type+ ']').attr('checked', 'checked');
	}
};
scsElementMenu.prototype.$ = function() {
	return this._$;
};
scsElementMenu.prototype.init = function() {
	var self = this
	,	$original = jQuery('#'+ this._menuOriginalId);
	if(!$original.data('icheck-cleared')) {
		$original.find('input').iCheck('destroy');
		$original.data('icheck-cleared', 1);
	}
	this._$ = $original
		.clone()
		.attr('id', this._id)
		.appendTo('body');
	this._afterAppendToElement();
	
	scsInitCustomCheckRadio( this._$ );
	this._fixClickOnRadio();
	this.reposite();
	if(this._btnsClb) {
		for(var selector in this._btnsClb) {
			if(this._$.find( selector ).size()) {
				this._$.find( selector ).click(function(){
					self._btnsClb[ jQuery(this).data('click-clb-selector') ]();
					return false;
				}).data('click-clb-selector', selector);
			}
		}
	}
	
	this._initSubMenus();
};
scsElementMenu.prototype._fixClickOnRadio = function() {
	this._$.find('.scsElMenuBtn').each(function(){
		if(jQuery(this).find('[type=radio]').size()) {
			jQuery(this).find('[type=radio]').click(function(){
				jQuery(this).parents('.scsElMenuBtn:first').click();
			});
		}
	});
};
scsElementMenu.prototype._hideSubMenus = function() {
	if(!this._$) return;	// If menu was already destroyed, with destroy element for example
	var menuAtBottom = this._$.hasClass('scsElMenuBottom')
	,	menuOpenBottom = this._$.hasClass('scsMenuOpenBottom')
	,	self = this;
	this._inAnimation = true;
	this._$.find('.scsElMenuSubPanel[data-sub-panel]:visible').each(function(){
		jQuery(this).slideUp(self._animationSpeed);
	});
	this._$.removeClass('scsMenuSubOpened');
	if(!menuAtBottom && !menuOpenBottom) {
		this._$.data('animation-in-process', 1).animate({
			'top': this._$.data('prev-top')
		}, this._animationSpeed, function(){
			self._$.data('animation-in-process', 0);
			self._inAnimation = false;
		});
	} else if(menuOpenBottom) {
		this._$.removeClass('scsMenuOpenBottom');
		this._inAnimation = false;
	} else {
		this._inAnimation = false;
	}
};
scsElementMenu.prototype._initSubMenus = function() {
	var self = this;
	if(this._$.find('.scsElMenuBtn[data-sub-panel-show]').size()) {
		this._$.find('.scsElMenuBtn').click(function(){
			self._hideSubMenus();
		});
		this._$.find('.scsElMenuBtn[data-sub-panel-show]').click(function(){
			var subPanelShow = jQuery(this).data('sub-panel-show')
			,	subPanel = self._$.find('.scsElMenuSubPanel[data-sub-panel="'+ subPanelShow+ '"]')
			,	menuPos = self._$.position()
			,	menuAtBottom = self._$.hasClass('scsElMenuBottom')
			,	menuTop = self._$.data('animation-in-process') ? self._$.data('prev-top') : menuPos.top;

			if(!subPanel.is(':visible')) {
				self._inAnimation = true;
				subPanel.slideDown(self._animationSpeed, function(){
					if(!menuAtBottom) {
						var subPanelHeight = subPanel.outerHeight();
						// If menu is too hight to move top - don't do this
						if(menuTop - subPanelHeight < g_scsTopBarH) {
							self._$.addClass('scsMenuOpenBottom');
							self._inAnimation = false;
						} else {
							self._$.data('prev-top', menuTop).animate({
								'top': menuTop - subPanelHeight
							}, self._animationSpeed, function(){
								self._inAnimation = false;
							});
						}
					}
				});
				self._$.addClass('scsMenuSubOpened')
			}
			return false;
		});
	}
};
scsElementMenu.prototype.reposite = function() {
	var elOffset = this._element.$().offset()
	,	elWidth = this._element.$().width()
	//,	elHeight = this._element.$().height()
	,	width = this._$.width()
	,	height = this._$.height()
	,	left = elOffset.left - (width - elWidth) / 2
	,	top = elOffset.top - height;
	if(this._element.$().hasClass('hover')) {
		top -= g_scsHoverMargin;
	}
	if(left < 0)
		left = 0;
	
	var elementOffset = this._element.$().offset();
	this._menuOnBottom = (elementOffset.top - height) <= g_scsTopBarH || this._element.$().data('menu-to-bottom');
	if(this._menuOnBottom) {	// If menu is too hight - move it under it's element
		var elHeight = this._element.$().outerHeight();
		top += elHeight + height;
	}
	this._$.css({
		'left': (left)+ 'px'
	,	'top': (top)+ 'px'
	});
	if(this._menuOnBottom) {
		this._$.addClass('scsElMenuBottom');
	}
	if(this._isMovable) {
		this._$.trigger('scsElMenuReposite', [this, top, left]);
	}
};
scsElementMenu.prototype.destroy = function() {
	if(this._$) {
		this._$.remove();
		this._$ = null;
	}
};
scsElementMenu.prototype.show = function() {
	if(!this._$) return;	// If menu was already destroyed, with destroy element for example
	if(!this._visible && !_scsSortInProgress()) {
		// Let's hide all other element menus in current block before show this one
		var blockElements = this.getElement().getBlock().getElements();
		for(var i = 0; i < blockElements.length; i++) {
			if(blockElements[ i ].menuInAnimation()) return;	// Menu is in animation - so we don't need to hide it
			blockElements[ i ].hideMenu();
		}
		this.reposite();
		// And now - show current menu
		this._$.addClass('active');
		this._visible = true;
	}
};
scsElementMenu.prototype.inAnimation = function() {
	return this._inAnimation;
};
scsElementMenu.prototype.hide = function() {
	if(!this._$) return;	// If menu was already destroyed, with destroy element for example
	if(this._visible) {
		this._hideSubMenus();
		this._$.removeClass('active');
		this._visible = false;
		if(this._isMovable) {
			this._$.trigger('scsElMenuHide', this);
		}
	}
};
scsElementMenu.prototype.getElement = function() {
	return this._element;
};
scsElementMenu.prototype._initColorpicker = function(params) {
	params = params || {};
	var self = this
	,	color = params.color ? params.color : this._element.get('color');

	var $input = params.colorInp ? params.colorInp : this._$.find('.scsColorBtn .scsColorpickerInput'),
		options = jQuery.extend({
    		convertCallback: function (colors) {
	    		var rgbaString = 'rgba(' + colors.webSmart.r + ',' + colors.webSmart.g + ',' + colors.webSmart.b + ',' + colors.alpha + ')';
	    		colors.tiny = new tinycolor( '#' + colors.HEX );
	    		colors.tiny.toRgbString = function () {
	    			return rgbaString;
	    		};

	    		self._element._setColor(rgbaString);

	    		$input.attr('value', rgbaString);
	    	}
    	},
    	g_scsColorPickerOptions
    );
    
    $input.css('background-color', color);
    $input.attr('value', color);
    $input.colorPicker(options);
};
scsElementMenu.prototype.isVisible = function() {
	return this._visible;
};
/**
 * Try to find color picker in menu, if find - init it
 * TODO: Make this work for all menus, that using colopickers
 */
/*scsElementMenu.prototype._initColorPicker = function(){
	
};*/
function scsElementMenu_btn(menuOriginalId, element, btnsClb) {
	scsElementMenu_btn.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_btn, scsElementMenu);
scsElementMenu_btn.prototype._afterAppendToElement = function() {
	scsElementMenu_btn.superclass._afterAppendToElement.apply(this, arguments);

	this.$().find('.scsPostLinkDisabled')
		.removeClass('scsPostLinkDisabled')
		.addClass('scsPostLinkList');

	// Link settings
	var self = this
	,	btnLink = this._element._getEditArea()
	,	linkInp = this._$.find('[name=btn_item_link]')
	,	titleInp = this._$.find('[name=btn_item_title]')
	,	newWndInp = this._$.find('[name=btn_item_link_new_wnd]');

	linkInp.val( btnLink.attr('href') ).change(function(){
		btnLink.attr('href', jQuery(this).val());
	});
	titleInp.val( btnLink.attr('title') ).change(function(){
		btnLink.attr('title', jQuery(this).val());
	});
	btnLink.attr('target') == '_blank' ? newWndInp.attr('checked', 'checked') : newWndInp.removeAttr('checked');
	newWndInp.change(function(){
		jQuery(this).attr('checked') ? btnLink.attr('target', '_blank') : btnLink.removeAttr('target');
	});
	// Color settings
	this._initColorpicker({
		color: this._element.get('bgcolor')
	});
};
function scsElementMenu_icon(menuOriginalId, element, btnsClb) {
	scsElementMenu_icon.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_icon, scsElementMenu);
scsElementMenu_icon.prototype._afterAppendToElement = function() {
	scsElementMenu_icon.superclass._afterAppendToElement.apply(this, arguments);

	this.$().find('.scsPostLinkDisabled')
		.removeClass('scsPostLinkDisabled')
		.addClass('scsPostLinkList');

	var self = this
	,	iconSizeID = ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x']
	,	iconSize = {
		'fa-lg': '1.33333333em'
	,	'fa-2x': '2em'
	,	'fa-3x': '3em'
	,	'fa-4x': '4em'
	,	'fa-5x': '5em'
	}
	,	$icon = this._element._$.find('.fa').first();

	if ($icon.size()) {
		var	iconClasses = $icon.attr("class").split(' ').reverse()
		,	currentIconSize = undefined;
		
		for (var i in iconClasses) {
			if (iconSizeID.indexOf(iconClasses[i]) != -1) {
				currentIconSize = iconClasses[i];
				break;
			}
		}

		if (currentIconSize)
			this._$.find('[data-size="' + currentIconSize + '"]').addClass('active');
	}

	this._$.on('click', '[data-size]', function () {
		var classSize = jQuery(this).attr('data-size')
		,	$icon = self._element._$.find('.fa').first();

		if (! $icon.size() || ! classSize) return;

		$icon.removeClass(iconSizeID.join(' '));
		$icon.addClass(classSize);
		$icon.css('font-size', iconSize[classSize]);
		self._$.find('[data-size].active').removeClass('active');
		self._$.find('[data-size="' + classSize + '"]').addClass('active');
	});
	
	// Open links library
	this._$.find('.scsIconLibBtn').click(function(){
		scsUtils.showIconsLibWnd( self._element );
		return false;
	});
	// Color settings
	this._initColorpicker();
	// Link settings
	var btnLink = this._element._getLink()
	,	linkInp = this._$.find('[name=icon_item_link]')
	,	titleInp = this._$.find('[name=icon_item_title]')
	,	newWndInp = this._$.find('[name=icon_item_link_new_wnd]');

	if(btnLink) {
		linkInp.val( btnLink.attr('href') );
		titleInp.val( btnLink.attr('title') );
		btnLink.attr('target') == '_blank' ? newWndInp.attr('checked', 'checked') : newWndInp.removeAttr('checked');
		btnLink.click(function(e){
			e.preventDefault();
		});
	}
	linkInp.change(function(){
		self._element._setLinkAttr('href', jQuery(this).val());
	});
	titleInp.change(function(){
		self._element._setLinkAttr('title', jQuery(this).val());
	});
	newWndInp.change(function(){
		self._element._setLinkAttr('target', jQuery(this).prop('checked') ? true : false);
	});
};
function scsElementMenu_img(menuOriginalId, element, btnsClb) {
	scsElementMenu_img.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_img, scsElementMenu);
scsElementMenu_img.prototype._afterAppendToElement = function() {
	scsElementMenu_img.superclass._afterAppendToElement.apply(this, arguments);

	this.$().find('.scsPostLinkDisabled')
		.removeClass('scsPostLinkDisabled')
		.addClass('scsPostLinkList');
	
	this.getElement().get('type') === 'video'
		? this.$().find('[name=type][value=video]').attr('checked', 'checked')
		: this.$().find('[name=type][value=img]').attr('checked', 'checked');

	var self = this;
	var btnLink = this._element._getLink()
		,	linkInp = this._$.find('[name=icon_item_link]')
		,	titleInp = this._$.find('[name=icon_item_title]')
		,	newWndInp = this._$.find('[name=icon_item_link_new_wnd]');

	if(btnLink) {
		linkInp.val( btnLink.attr('href') );
		titleInp.val( btnLink.attr('title') );
		btnLink.attr('target') == '_blank' ? newWndInp.attr('checked', 'checked') : newWndInp.removeAttr('checked');
		btnLink.click(function(e){
			e.preventDefault();
		});
	}

	linkInp.change(function(){
		self._element._setLinkAttr('href', jQuery(this).val());
	});

	titleInp.change(function(){
		self._element._setLinkAttr('title', jQuery(this).val());
	});

	newWndInp.change(function(){
		self._element._setLinkAttr('target', jQuery(this).prop('checked') ? true : false);
	});
};
function scsElementMenu_table_cell(menuOriginalId, element, btnsClb) {
	scsElementMenu_table_cell.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_table_cell, scsElementMenu);
scsElementMenu_table_cell.prototype._afterAppendToElement = function() {
	scsElementMenu_table_cell.superclass._afterAppendToElement.apply(this, arguments);
	var type = this.getElement().get('type');
	if(!type)
		type = 'txt';
	this._$.find('[name=type][value='+ type+ ']').attr('checked', 'checked');
};
/**
 * Table col menu
 */
function scsElementMenu_table_col(menuOriginalId, element, btnsClb) {
	scsElementMenu_table_col.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_table_col, scsElementMenu);
scsElementMenu_table_col.prototype._afterAppendToElement = function() {
	scsElementMenu_table_col.superclass._afterAppendToElement.apply(this, arguments);
	var self = this;
	// Enb/Dslb fill color
	var $enbFillColorCheck = this._$.find('[name=enb_fill_color]');
	$enbFillColorCheck.change(function(){
		self.getElement().set('enb-color', jQuery(this).attr('checked') ? 1 : 0);
		self.getElement()._setColor();	// Just update it from existing color
		return false;
	});
	parseInt(this.getElement().get('enb-color'))
		? $enbFillColorCheck.attr('checked', 'checked')
		: $enbFillColorCheck.removeAttr('checked');
	// Color settings
	this._initColorpicker();
	// Enb/Dslb badge
	var $enbBadgeCheck = this._$.find('[name=enb_badge_col]');
	$enbBadgeCheck.change(function(){
		//self.getElement().set('enb-badge', jQuery(this).attr('checked') ? 1 : 0);
		if(jQuery(this).attr('checked')) {
			self.getElement()._setBadge();	// Just update it from existing color
		} else {
			self.getElement()._disableBadge();
		}
		return false;
	});
	parseInt(this.getElement().get('enb-badge'))
		? $enbBadgeCheck.attr('checked', 'checked')
		: $enbBadgeCheck.removeAttr('checked');
	// Badge click
	this._btnsClb['.scsColBadgeBtn'] = function() {
		
		self.getElement()._showSelectBadgeWnd();
	};
};
function scsElementMenu_table_cell_icon(menuOriginalId, element, btnsClb) {
	scsElementMenu_table_cell_icon.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_table_cell_icon, scsElementMenu_icon);
/**
 * Grid column menu
 */
function scsElementMenu_grid_col(menuOriginalId, element, btnsClb) {
	scsElementMenu_grid_col.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_grid_col, scsElementMenu);
scsElementMenu_grid_col.prototype._afterAppendToElement = function() {
	scsElementMenu_grid_col.superclass._afterAppendToElement.apply(this, arguments);
	var self = this;
	// Enb/Dslb fill color
	var enbFillColorCheck = this._$.find('[name=enb_fill_color]');
	enbFillColorCheck.change(function(){
		self.getElement().set('enb-color', jQuery(this).attr('checked') ? 1 : 0);
		self.getElement()._setColor();	// Just update it from existing color
		return false;
	});
	parseInt(this.getElement().get('enb-color'))
		? enbFillColorCheck.attr('checked', 'checked')
		: enbFillColorCheck.removeAttr('checked');
	// Color settings
	this._initColorpicker();
	// Enb/Dslb bg img
	var enbBgImgCheck = this._$.find('[name=enb_bg_img]');
	enbBgImgCheck.change(function(){
		self.getElement().set('enb-bg-img', jQuery(this).attr('checked') ? 1 : 0);
		self.getElement()._setImg();	// Just update it from existing image
		return false;
	});
	parseInt(this.getElement().get('enb-bg-img'))
		? enbBgImgCheck.attr('checked', 'checked')
		: enbBgImgCheck.removeAttr('checked');
};
/**
 * Delimiter menu
 */
function scsElementMenu_delimiter(menuOriginalId, element, btnsClb) {
	scsElementMenu_delimiter.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_delimiter, scsElementMenu);
scsElementMenu_delimiter.prototype._afterAppendToElement = function() {
	scsElementMenu_delimiter.superclass._afterAppendToElement.apply(this, arguments);
	// Color settings
	this._initColorpicker();
};
/**
 * Timer menu
 */
function scsElementMenu_timer(menuOriginalId, element, btnsClb) {
	scsElementMenu_timer.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_timer, scsElementMenu);
scsElementMenu_timer.prototype._afterAppendToElement = function() {
	scsElementMenu_timer.superclass._afterAppendToElement.apply(this, arguments);
	// Color settings
	this._initColorpicker();

	var $el = this._element._$,
		self = this,
		startDateFormat = $el.attr('data-dateformat');
	
	if (typeof startDateFormat === 'string')
		jQuery('#' + this._id).find('.scsElMenuForm .dateFormat')
							  .val(startDateFormat);

	// Date Format
	jQuery('#' + this._id).find('.scsElMenuForm .dateFormat').bind('input', function(){
  		var $this = jQuery(this);

  		$el.attr('data-dateformat', $this.val());

		self._element.initFormat($el);
	});
};
/**
 * Socail Icons menu
 */
/*function scsElementMenu_social_icons(menuOriginalId, element, btnsClb) {
	scsElementMenu_social_icons.superclass.constructor.apply(this, arguments);
}
extendScs(scsElementMenu_social_icons, scsElementMenu);
scsElementMenu_social_icons.prototype._afterAppendToElement = function() {
	scsElementMenu_social_icons.superclass._afterAppendToElement.apply(this, arguments);
	// Color settings
	this._initColorpicker();
};*/