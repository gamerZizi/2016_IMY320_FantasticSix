function scsMainMenu(element) {
	this._visible = false;
	this._$ = jQuery(element);
	this._animationSpeed = 300;
	this._mouseOver = false;
	this._catWidth = 120;
	this._blockWidth = 340;
	this._openBtnLeft = 30;
	this._init();
}
scsMainMenu.prototype._init = function() {
	this._$.mouseover(jQuery.proxy(function(){
		this._mouseOver = true;
	}, this)).mouseleave(jQuery.proxy(function(){
		this._mouseOver = false;
	}, this));
};
scsMainMenu.prototype.checkShow = function() {
	if(!this._visible) {
		this.show();
		return true;
	}
	return false;
};
scsMainMenu.prototype.show = function() {
	this._visible = true;
	this._$.stop();
};
scsMainMenu.prototype.checkHide = function() {
	if(this._visible) {
		this.hide();
	}
};
scsMainMenu.prototype.hide = function() {
	this._visible = false;
	this._$.stop();
};
scsMainMenu.prototype.isVisible = function() {
	return this._visible;
};
scsMainMenu.prototype.getRaw = function() {
	return this._$;
};
scsMainMenu.prototype.isMouseOver = function() {
	return this._mouseOver;
};
scsMainMenu.prototype._setOpenBtnPos = function(pos) {
	jQuery('.scsMainBarHandle').stop().animate({
		'left': pos+ 'px'
	}, this._animationSpeed);
	if(pos == this._openBtnLeft) {
		jQuery('.scsMainBarHandle').removeClass('active').find('.octo-icon').addClass('icon-pluss-b').removeClass('icon-close-b');
	} else {
		jQuery('.scsMainBarHandle').addClass('active').find('.octo-icon').addClass('icon-close-b').removeClass('icon-pluss-b');
	}
};
/**
 * Categories Menu Class (Main Menu)
 */
function scsCategoriesMainMenu(element) {
	scsCategoriesMainMenu.superclass.constructor.apply(this, arguments);
	this._subMenus = [];
	this._cidToSubId = {};
}
extendScs(scsCategoriesMainMenu, scsMainMenu);
scsCategoriesMainMenu.prototype.addSubMenu = function(subMenuObj) {
	var newSubObj = new scsBlocksMainMenu(subMenuObj);
	var newIter = this._subMenus.push( newSubObj );
	this._cidToSubId[ newSubObj.getRaw().data('cid') ] = newIter - 1;
};
scsCategoriesMainMenu.prototype.showSubByCid = function(cid) {
	if(this._subMenus[ this._cidToSubId[ cid ] ].checkShow()) {
		this._$.find('[data-id="'+ cid+ '"]').addClass('active');
		for(var i = 0; i < this._subMenus.length; i++) {
			if(this._subMenus[i].getCid() !== cid) {
				this.hideSubByCid( this._subMenus[i].getCid() );
			}
		}
	}
};
scsCategoriesMainMenu.prototype.hideSubByCid = function(cid) {
	if(this._subMenus[ this._cidToSubId[ cid ] ].checkHide()) {
		this._$.find('[data-id="'+ cid+ '"]').removeClass('active');
	}
};
scsCategoriesMainMenu.prototype.show = function() {
	scsCategoriesMainMenu.superclass.show.apply(this, arguments);
	var self = this;
	this._$.animate({
		'left': '0px'
	}, this._animationSpeed, function(){
		self._$.find('.scsMainBarInner').slimScroll({
			height: jQuery(window).height()
		});
	});
	this._setOpenBtnPos( this._catWidth + this._openBtnLeft );
};
scsCategoriesMainMenu.prototype.checkHide = function() {
	if(this._visible && !this.isMouseOver()) {
		for(var i = 0; i < this._subMenus.length; i++) {
			if(this._subMenus[i].isMouseOver())
				return false;
		}
		this.hide();
		return true;
	}
	return false;
};
scsCategoriesMainMenu.prototype.hide = function() {
	scsCategoriesMainMenu.superclass.hide.apply(this, arguments);
	this._$.animate({
		'left': -this._catWidth+ 'px'
	}, this._animationSpeed);
	for(var i = 0; i < this._subMenus.length; i++) {
		this._subMenus[i].checkHide();
	}
	this._setOpenBtnPos( this._openBtnLeft );
};
scsCategoriesMainMenu.prototype.isSubMenuVisible = function() {
	for(var i = 0; i < this._subMenus.length; i++) {
		if(this._subMenus[i].isVisible()) {
			return true;
		}
	}
	return false;
};
/**
 * Blocks Menu Class (Sub Menus)
 */
function scsBlocksMainMenu(element) {
	scsBlocksMainMenu.superclass.constructor.apply(this, arguments);
	this._cid = this._$.data('cid');
}
extendScs(scsBlocksMainMenu, scsMainMenu);
scsBlocksMainMenu.prototype.getCid = function() {
	return this._cid;
};
scsBlocksMainMenu.prototype.show = function() {
	scsBlocksMainMenu.superclass.show.apply(this, arguments);
	var self = this;
	this._$.animate({
		'left': this._catWidth+ 'px'
	}, this._animationSpeed, function(){
		self._$.find('.scsBlockBarInner').slimScroll({
			height: jQuery(window).height()
		});
	});
	this._setOpenBtnPos( this._catWidth + this._openBtnLeft + this._blockWidth );
};
scsBlocksMainMenu.prototype.hide = function() {
	scsBlocksMainMenu.superclass.hide.apply(this, arguments);
	this._$.animate({
		'left': -this._blockWidth+ 'px'
	}, this._animationSpeed);
	this._setOpenBtnPos( this._catWidth + this._openBtnLeft );
};
scsBlocksMainMenu.prototype.checkHide = function() {
	if(this._visible && !this.isMouseOver()) {
		this.hide();
		return true;
	}
	return false;
};