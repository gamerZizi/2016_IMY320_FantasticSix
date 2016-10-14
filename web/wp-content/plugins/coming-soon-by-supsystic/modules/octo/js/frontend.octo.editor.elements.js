/**
 * Destroy current element
 */
scsElementBase.prototype.destroy = function(clb) {
	if(this._$) {
		var childElements = this._getChildElements();
		if(childElements) {
			for(var i = 0; i < childElements.length; i++) {
				childElements[ i ]._remove();
			}
		}
		var self = this;
		this._$.slideUp(this._animationSpeed, function(){
			self._remove();
			if(clb && typeof(clb) === 'function') {
				clb();
			}
			_scsSaveCanvas();
		});
	}
};
scsElementBase.prototype._remove = function() {
	if(this._showMenuEvent == 'click') {
		jQuery(document).unbind('click.menu_el_click_hide_'+ this.getId());
	}
	this._destroyMenu();
	this._$.remove();
	this._$ = null;
	this._afterDestroy();
	this._block.removeElementByIterNum( this.getIterNum() );
};
scsElementBase.prototype._getChildElements = function() {
	var allFoundHtml = this._$.find('.scsEl');
	if(allFoundHtml && allFoundHtml.size()) {
		var foundElements = []
		,	selfBlock = this.getBlock();
		allFoundHtml.each(function(){
			var element = selfBlock.getElementByIterNum( jQuery(this).data('iter-num') );
			if(element) {
				foundElements.push( element );
			}
		});
		return foundElements.length ? foundElements : false;
	}
	return false;
};
scsElementBase.prototype._afterDestroy = function() {
	
};
scsElementBase.prototype.beforeSave = function() {
	this._destroyMoveHandler();
};
scsElementBase.prototype.afterSave = function() {
	this._initMoveHandler();
};
scsElementBase.prototype._initMenu = function() {
	if(this._menuOriginalId && this._menuOriginalId != '') {
		this._initMenuClbs();
		var menuParams = {
			changeable: this._changeable
		};
		if(!window[ this._menuClass ]) {
			console.log('Can not find menu class for '+ this._menuClass+ '!');
			return;
		}
		this._menu = new window[ this._menuClass ]( this._menuOriginalId, this, this._menuClbs, menuParams );
		if(!this._initedComplete) {
			var self = this;
			switch(this._showMenuEvent) {
				case 'hover':
					this._$.hover(function(){
						clearTimeout(jQuery(this).data('hide-menu-timeout'));
						self.showMenu();
					}, function(){
						jQuery(this).data('hide-menu-timeout', setTimeout(function(){
							self.hideMenu();
						}, 1000));	// Let it be visible 1 second more
					});
					this._menu.$().hover(function(){
						clearTimeout(jQuery(self._$).data('hide-menu-timeout'));
					}, function(){
						jQuery(self._$).data('hide-menu-timeout', setTimeout(function(){
							self.hideMenu();
						}, 1000));	// Let it be visible 1 second more
					});
					break;
				case 'click': default:
					this._$.click(function(e){
						e.stopPropagation();
						self.showMenu();
					});
					jQuery(document).bind('click.menu_el_click_hide_'+ this.getId(), jQuery.proxy(this._closeMenuOnDocClick, this));
					break;
			}
		}
		if(this._isMovable) {
			this._initMoveHandler();
			this._initMovableMenu();
		}

		this.initPostLinks(this._menu._$);
	}
};
scsElementBase.prototype.initPostLinks = function($menu) {
	if (! this.includePostLinks) return;

	var $linkTab = $menu.find('.scsPostLinkList')
	,	$field = null
	,	fieldSelector = $linkTab.attr('data-postlink-to');

	if (! fieldSelector.length) return;

	if (fieldSelector.indexOf(':parent') == 0) {
		fieldSelector = fieldSelector.substring(7, fieldSelector.length).trim();

		$field = $linkTab.parent().find(fieldSelector);
	} else {
		$field = jQuery(fieldSelector);
	}

	if (! $field.size()) return;

	this.showPostsLinks($linkTab);

	$linkTab.css({
		height: 120
	});

	$linkTab.on('click', 'li', function () {
		var $item = jQuery(this)
		,	url = $item.attr('data-value');

		if (! url) return;

		$field.val(url);

		$field.change();
	});

	$linkTab.slimScroll({
		height: 120
	,	railVisible: true
	,	alwaysVisible: true
	,	allowPageScroll: true
	,	color: '#f72497'
	,	opacity: 1
	,	distance: 0
	,	borderRadius: '3px'
	});

	$linkTab.parent('.slimScrollDiv')
		.addClass('scsPostLinkRoot')
		.hide();

	var $rootTab = $linkTab.parent('.scsPostLinkRoot');

	/** Hide and show handlers **/
	var ignoreHide = false
	,	isFocus = false;

	$field.on('postlink.hide', function () {
		$rootTab.hide();

		$linkTab.hide();

		$field.trigger('postlink.hide:after');
	});

	$field.focus(function () {
		$field.trigger('postlink.show');

		$rootTab.show();

		$linkTab.show();

		isFocus = true;

		$field.trigger('postlink.show:after');
	});

	$rootTab.hover(function () {
		ignoreHide = true;
	}, function () {
		ignoreHide = false;

		if (! isFocus) {
			$field.trigger('postlink.hide');
		}
	});

	$field.blur(function () {
		isFocus = false;

		if (!ignoreHide) {
			$field.trigger('postlink.hide');
		}
	});
};
scsElementBase.prototype.escapeString = function  (str) {
	return jQuery('<div/>').text(str).html();
}
scsElementBase.prototype.showPostsLinks = function($tab) {
	if (! $tab.find('ul').size()) {
		$tab.html('<ul></ul>');
	}

	$tab.find('ul').html('');

	for (var i in scsEditor.posts) {
		$tab.find('ul')
			.append(
				'<li data-value="' + this.escapeString(scsEditor.posts[i].url) + '">' +
					'<span>' + this.escapeString(scsEditor.posts[i].title) + '</span>' +
				'</li>'
			);
	}
};
scsElementBase.prototype._closeMenuOnDocClick = function(e, element) {
	if(!this._menu.isVisible()) return;
	var $target = jQuery(e.target);
	if(!this.$().find( $target ).size() && !this.getMenu().$().find($target).size()) {
		this.hideMenu();
	}
};
scsElementBase.prototype.getMenu = function() {
	return this._menu;
};
scsElementBase.prototype._initMovableMenu = function() {
	this._menu.setMovable(true);
	this._menu.$().bind('scsElMenuReposite', function(e, menu, top, left){
		var element = menu.getElement()
		,	$element = element.$()
		,	$menu = menu.$()
		,	elWidth = $element.width()
		,	menuWidth = $menu.width()
		,	menuHeight = $menu.height();
		var placePos = menu.$().find('.scsElMenuMoveHandlerPlace').position()
		,	moveTop = -1 * menuHeight + placePos.top;
		if($element.hasClass('hover')) {
			moveTop -= g_scsHoverMargin;
		}
		element._moveHandler.css({
			'top': moveTop
		,	'left': ((elWidth - menuWidth) / 2) + placePos.left - 10
		}).addClass('active');
	}).bind('scsElMenuHide', function(e, menu){
		var element = menu.getElement();
		if(!element._sortInProgress) {
			element._moveHandler.removeClass('active');
		}
	});
};
scsElementBase.prototype.onSortStart = function() {
	this._sortInProgress = true;
	this._moveHandler.addClass('sortInProgress');
	this._menu.hide();
};
scsElementBase.prototype.onSortStop = function() {
	this._sortInProgress = false;
	this._moveHandler.removeClass('sortInProgress');
	this._menu.show();
};
scsElementBase.prototype._initMenuClbs = function() {
	var self = this;
	this._menuClbs['.scsRemoveElBtn'] = function() {
		self.destroy();
	};
	if(this._changeable) {
		this._menuClbs['.scsTypeTxtBtn'] = function() {
			self.getBlock().replaceElement(self, 'txt_item_html', 'txt');
		};
		this._menuClbs['.scsTypeImgBtn'] = function() {
			self.getBlock().replaceElement(self, 'img_item_html', 'img');
		};
		this._menuClbs['.scsTypeIconBtn'] = function() {
			self.getBlock().replaceElement(self, 'icon_item_html', 'icon');
		};
	}
};
scsElementBase.prototype._initMoveHandler = function() {
	if(this._isMovable && !this._moveHandler) {
		this._moveHandler = jQuery('#scsMoveHandlerExl').clone().removeAttr('id').appendTo( this._$ );
	}
};
scsElementBase.prototype._destroyMoveHandler = function() {
	if(this._isMovable) {
		this._moveHandler.remove();
		this._moveHandler = null;
	}
};
scsElementBase.prototype._afterFullContentLoad = function() {
	//sthis.repositeMenu();
};
scsElementBase.prototype._destroyMenu = function() {
	if(this._menu) {
		this._menu.destroy();
		this._menu = null;
	}
};
scsElementBase.prototype.showMenu = function() {
	if(this._menu) {
		this._menu.show();
	}
};
scsElementBase.prototype.hideMenu = function() {
	if(this._menu) {
		this._menu.hide();
	}
};
scsElementBase.prototype.menuInAnimation = function() {
	if(this._menu) {
		return this._menu.inAnimation();
	}
	return false;
};
scsElementBase.prototype.setMovable = function(state) {
	this._isMovable = state;
};
scsElementBase.prototype.repositeMenu = function() {
	if(this._menu) {
		this._menu.reposite();
	}
};
/**
 * Text element
 */
function scsElement_txt(jqueryHtml, block) {
	this._elId = null;
	this._editorElement = null;
	this._editor = null;
	this.includePostLinks = true;
	this._editorToolbarBtns = [
		['octo_fontselect'], ['octo_fontsizeselect'], ['bold', 'italic', 'strikethrough'], ['octo_link'], ['forecolor'], ['octo_elementremove']
	];
	scsElement_txt.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_txt, scsElementBase);
scsElement_txt.prototype._afterEditorInit = function(editor) {
	var self = this;
	editor.addButton('octo_elementremove', {
		classes: 'glyphicon glyphicon-trash',
		icon: false,
		title: 'Remove'
	,	onclick: function(e) {
			self.destroy();
		}
	});
};
scsElement_txt.prototype._init = function() {
	scsElement_txt.superclass._init.apply(this, arguments);
	var id = this._$.attr('id')
	,	self = this;
	if(!id || id == '') {
		this._$.attr('id', 'scsTxt_'+ mtRand(1, 99999));
	}
	var toolbarBtns = [];
	for(var i = 0; i < this._editorToolbarBtns.length; i++) {
		toolbarBtns.push( typeof(this._editorToolbarBtns[i]) === 'string' ? this._editorToolbarBtns[i] : this._editorToolbarBtns[i].join(' ') );
	}
	this._editorElement = this._$.tinymce({
		inline: true
	// ' |  | ' is panel buttons delimiter
	,	toolbar: toolbarBtns.join(' |  | ')
	,	menubar: false
	,	plugins: 'octo_textcolor octo_link octo_fontselect octo_fontsizeselect'
	,	fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt 64pt 72pt'
	,	skin : 'octo'
	,	convert_urls: false
	,	setup: function(ed) {
			this._editor = ed;
			ed.on('blur', function(e) {
				if(e.target._scsChanged) {
					e.target._scsChanged = false;
					_scsSaveCanvas();
				}
			});
			ed.on('change', function(e) {
				e.target._scsChanged = true;
				if(e.target._scsChangeTimeout) {
					clearTimeout( e.target._scsChangeTimeout );
				}
				e.target._scsChangeTimeout = setTimeout(function(){
					self.getBlock().contentChanged();
				}, 1000);
			});
			ed.on('keyup', function(e) {
				var selectionCoords = getSelectionCoords();
				scsMceMoveToolbar( self._editorElement.tinymce(), selectionCoords.x );
			});
			ed.on('click', function(e) {
				scsMceMoveToolbar( self._editorElement.tinymce(), e.clientX );

				if (ed.theme.panel.hasOwnProperty('isInitPostlinkClick')) return;

				var handler = function () {
					ed.theme.panel.isInitPostlinkClick = true;

					var $fieldWp = jQuery('#' + self._$.attr('id') + 'scsPostLinkList');

					if ($fieldWp.size()) {
						ed.theme.panel.off('click', handler);	
						
						self.initPostLinks($fieldWp.parents('.mce-container'));					
					}
				};

				ed.theme.panel.on('click', handler);
			});
			/*ed.on('focus', function(e) {
				
			});*/
			if(self._afterEditorInit) {
				self._afterEditorInit( ed );
			}
		}
	});
	this._$.removeClass('mce-edit-focus');
	// Do not allow drop anything it text element outside content area
	this._$.bind('dragover drop', function(event){
		event.preventDefault();
	});
};
scsElement_txt.prototype.getEditorElement = function() {
	return this._editorElement;
};
scsElement_txt.prototype.getEditor = function() {
	return this._editor;
};
scsElement_txt.prototype.beforeSave = function() {
	scsElement_txt.superclass.beforeSave.apply(this, arguments);
	if(!this._$) return;	// TODO: Make this work corect - if there are no html (_$) - then this method should not simple triggger. For now - it trigger even if _$ === null
	this._elId = this._$.attr('id');
	this._$
		.removeAttr('id')
		.removeAttr('contenteditable')
		.removeAttr('spellcheck')
		.removeClass('mce-content-body mce-edit-focus');
};
scsElement_txt.prototype.afterSave = function() {
	scsElement_txt.superclass.afterSave.apply(this, arguments);
	if(this._elId) {
		this._$
			.attr('id', this._elId)
			.attr('contenteditable', 'true')
			.attr('spellcheck', 'false')
			.addClass('mce-content-body');;
	}
};
/**
 * Image element
 */
function scsElement_img(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuImgExl';
	}
	this._menuClass = 'scsElementMenu_img';
	this.includePostLinks = true;
	scsElement_img.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_img, scsElementBase);
scsElement_img.prototype._beforeImgChange = function(opts, attach, imgUrl, imgToChange) {
	
};
scsElement_img.prototype._afterImgChange = function(opts, attach, imgUrl, imgToChange) {
	
};
scsElement_img.prototype._initMenuClbs = function() {
	scsElement_img.superclass._initMenuClbs.apply(this, arguments);
	var self = this;
	this._menuClbs['.scsImgChangeBtn'] = function() {
		self.set('type', 'img');
		self._getImg().show();
		self._getVideoFrame().remove();
		scsCallWpMedia({
			id: self._$.attr('id')
		,	clb: function(opts, attach, imgUrl) {
				var $imgToChange = self._getImg();
				//self._block.beforeSave();
				self._innerImgsLoaded = 0;
				self._beforeImgChange( opts, attach, imgUrl, $imgToChange );
				$imgToChange.attr('src', imgUrl);
				self._afterImgChange( opts, attach, imgUrl, $imgToChange );
				//self._block.afterSave();
				self._block.contentChanged();
				//self.hideMenu();
				//_scsSaveCanvas();
			}
		});
	};
	this._menuClbs['.scsImgVideoSetBtn'] = function() {
		self.set('type', 'video');
		self._buildVideo( self._menu.$().find('[name=video_link]').val() );
	};
};
scsElement_img.prototype._buildVideo = function(url) {
	url = url ? jQuery.trim( url ) : false;
	if(url) {
		var $editArea = this._getEditArea()
		,	$videoFrame = this._getVideoFrame( $editArea )
		,	$img = this._getImg( $editArea )
		,	src = scsUtils.urlToVideoSrc( url );
		$videoFrame.attr({
			'src': src
		,	'width': $img.width()
		,	'height': $img.height()
		}).show();
		$img.hide();
	}
};
scsElement_img.prototype._getVideoFrame = function( editArea ) {
	editArea = editArea ? editArea : this._getEditArea();
	var videoFrame = editArea.find('iframe.scsVideo');
	if(!videoFrame.size()) {
		videoFrame = jQuery('<iframe class="scsVideo" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen />').appendTo( editArea );
	}
	return videoFrame;
};
scsElement_img.prototype._getImg = function(editArea) {
	editArea = editArea ? editArea : this._getEditArea();
	return editArea.find('img');
};
scsElement_img.prototype._initMenu = function() {
	scsElement_img.superclass._initMenu.apply(this, arguments);
	var self = this;
	this._menu.$().find('[name=video_link]').change(function(){
		self._buildVideo( jQuery(this).val() );
	}).keyup(function(e){
		if(e.keyCode == 13) {	// Enter
			self._buildVideo( jQuery(this).val() );
		}
	});
};
scsElement_img.prototype._getLink = function() {
	var $link = this._$.find('a.scsLink');
	return $link.size() ? $link : false;
};
scsElement_img.prototype._setLinkAttr = function(attr, val) {
	switch(attr) {
		case 'href':
			if(val) {
				var $link = this._createLink();
				$link.attr(attr, val);
			} else
				this._removeLink();
			break;
		case 'title':
			var $link = this._createLink();
			$link.attr(attr, val);
			break;
		case 'target':
			var $link = this._createLink();
			val ? $link.attr('target', '_blank') : $link.removeAttr('target');
			break;
	}
};
scsElement_img.prototype._createLink = function() {
	var $link = this._getLink();
	if(!$link) {
		$link = jQuery('<a class="scsLink" />').append( this._$.find('img') ).appendTo( this._$ );
		$link.click(function(e){
			e.preventDefault();
		});
	}
	return $link;
};
scsElement_img.prototype._removeLink = function() {
	var $link = this._getLink();
	if($link) {
		this._$.append( $link.find('.scsInputShell') );
		$link.remove();
	}
};
/**
 * Gallery image element
 */
function scsElement_gal_img(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuGalItemExl';
	}
	scsElement_gal_img.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_gal_img, scsElement_img);
scsElement_gal_img.prototype._afterDestroy = function() {
	scsElement_gal_img.superclass._afterDestroy.apply(this, arguments);
	this._block.recalcRows();
};
scsElement_gal_img.prototype._afterImgChange = function(opts, attach, imgUrl, imgToChange) {
	scsElement_gal_img.superclass._afterImgChange.apply(this, arguments);
	imgToChange.attr('data-full-img', attach.url);
	imgToChange.parents('.scsGalLink:first').attr('href', attach.url);
};
scsElement_gal_img.prototype._updateLink = function() {
	var newLink = jQuery.trim( this._menu.$().find('[name=gal_item_link]').val() )
	,	linkHtml = this._$.find('.scsGalLink');
	if(newLink && newLink != '') {
		newLink = scsUtils.converUrl( newLink );
		linkHtml.attr('href', newLink);
		var newWnd = this._menu.$().find('[name=gal_item_link_new_wnd]').attr('checked');
		newWnd ? linkHtml.attr('target', '_blank') : linkHtml.removeAttr('target');
		linkHtml.addClass('scsGalLinkOut').attr('data-link', newLink).attr('data-new-wnd', newWnd ? 1 : 0);
		this._block._initLightbox();
	} else {
		linkHtml
			.attr('href', this._$.find('img').data('full-img'))
			.removeAttr('target data-link data-new-wnd')
			.removeClass('scsGalLinkOut');
	}
};
/**
 * Menu item element
 */
function scsElement_menu_item(jqueryHtml, block) {
	/*if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuGalItemExl';
	}*/
	scsElement_menu_item.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_menu_item, scsElement_txt);
scsElement_menu_item.prototype._afterEditorInit = function(editor) {
	var self = this;
	editor.addButton('tables_remove', {
		title: 'Remove'
	,	onclick: function(e) {
			self.destroy();
		}
	});
};
scsElement_menu_item.prototype._beforeInit = function() {
	this._editorToolbarBtns.push('tables_remove');
};
/**
 * Menu item image
 */
function scsElement_menu_item_img(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuMenuItemImgExl';
	}
	scsElement_menu_item_img.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_menu_item_img, scsElement_img);
/**
 * Input item
 */
function scsElement_input(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuInputExl';
	}
	if(typeof(this._isMovable) === 'undefined') {
		this._isMovable = true;
	}
	scsElement_input.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_input, scsElementBase);
scsElement_input.prototype._init = function() {
	scsElement_input.superclass._init.apply(this, arguments);
	var saveClb = function(element) {
		jQuery(element).attr('placeholder', jQuery(element).val());
		jQuery(element).val('');
		_scsSaveCanvasDelay();
	};
	this._getInput().focus(function(){
		jQuery(this).val(jQuery(this).attr('placeholder'));
	}).blur(function(){
		if(jQuery(this).data('saved')) {
			jQuery(this).data('saved', 0);
			return;
		}
		saveClb(this)
	}).keyup(function(e){
		if(e.keyCode == 13) {	// Enter
			saveClb(this);
			jQuery(this).data('saved', 1).trigger('blur');	// We must blur from element after each save in any case
		}
	});
};
scsElement_input.prototype._getInput = function() {
	if(!this._$) return;	// TODO: Make this work corect - if there are no html (_$) - then this method should not simple triggger. For now - it trigger even if _$ === null
	// TODO: Modify this to return all fields types
	return this._$.find('input');
};
scsElement_input.prototype._initMenu = function(){
	scsElement_input.superclass._initMenu.apply(this, arguments);
	if(!this._$) return;	// TODO: Make this work corect - if there are no html (_$) - then this method should not simple triggger. For now - it trigger even if _$ === null
	var self = this
	,	menuReqCheck = this._menu.$().find('[name="input_required"]');
	menuReqCheck.change(function(){
		var required = jQuery(this).attr('checked');
		if(required) {
			self._getInput().attr('required', '1');
		} else {
			self._getInput().removeAttr('required');
		}
		self._block.setFieldRequired(self._getInput().attr('name'), (helperChecked ? 1 : 0));
		_scsSaveCanvasDelay();
	});
	self._getInput().attr('required')
		? menuReqCheck.attr('checked', 'checked')
		: menuReqCheck.removeAttr('checked');
	scsCheckUpdate( menuReqCheck );
};
scsElement_input.prototype.destroy = function() {
	// Remove field from block fields list at first
	var name = this._getInput().attr('name');
	this._block.removeField( name );
	scsElement_input.superclass.destroy.apply(this, arguments);
};
/**
 * Input button item
 */
function scsElement_input_btn(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuInputBtnExl';
	}
	if(typeof(this._isMovable) === 'undefined') {
		this._isMovable = false;
	}
	scsElement_input_btn.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_input_btn, scsElementBase);
scsElement_input_btn.prototype._getInput = function() {
	// TODO: Modify this to return all fields types
	var $btn = this._$.find('input');
	if(!$btn || !$btn.size()) {
		$btn = this._$.find('button');
	}
	return $btn;
};
scsElement_input_btn.prototype._init = function() {
	scsElement_input_btn.superclass._init.apply(this, arguments);
	var isIconic = parseInt(this.get('iconic'));
	var saveClb = function(element) {
		jQuery(element).attr('type', 'submit');
		_scsSaveCanvasDelay();
	};
	var self = this;
	this._getInput().click(function(){
		if(isIconic) {
			scsUtils.showIconsLibWnd( self );
		}
		return false;
	}).focus(function(){
		if(isIconic) return;
		var value = jQuery(this).val();
		jQuery(this).attr('type', 'text').val( value );
	}).blur(function(){
		if(isIconic) return;
		if(jQuery(this).data('saved')) {
			jQuery(this).data('saved', 0);
			return;
		}
		saveClb(this);
	}).keyup(function(e){
		if(isIconic) return;
		if(e.keyCode == 13) {	// Enter
			saveClb(this);
			jQuery(this).data('saved', 1).trigger('blur');	// We must blur from element after each save in any case
		}
	});
};
/**
 * Standart button item
 */
scsElement_btn.prototype.beforeSave = function() {
	scsElement_btn.superclass.beforeSave.apply(this, arguments);
	this._getEditArea().removeAttr('contenteditable');
};
scsElement_btn.prototype.afterSave = function() {
	scsElement_btn.superclass.afterSave.apply(this, arguments);
	this._getEditArea().attr('contenteditable', true);
};
scsElement_btn.prototype._init = function() {
	scsElement_btn.superclass._init.apply(this, arguments);
	var self = this;
	this._getEditArea().attr('contenteditable', true).blur(function(){
		self._block.contentChanged();
		//_scsSaveCanvasDelay();
	}).keypress(function(e){
		if(e.keyCode == 13 && window.getSelection) {	// Enter
			document.execCommand('insertHTML', false, '<br>');
			if (typeof e.preventDefault != "undefined") {
                e.preventDefault();
            } else {
                e.returnValue = false;
            }
		}
	});
	if(this.get('customhover-clb')) {

	}
};
scsElement_btn.prototype._setColor = function(color) {
	this.set('bgcolor', color);
	var bgElements = this.get('bgcolor-elements');
	if(bgElements)
		bgElements = this._$.find(bgElements);
	else
		bgElements = this._$;
	switch(this.get('bgcolor-to')) {
		case 'border':	// Change only borders color
			bgElements.css({
				'border-color': color
			});
			break;
		case 'txt':
			bgElements.css({
				'color': color
			});
			break;
		case 'bg':
		default:
			bgElements.css({
				'background-color': color
			});
			break;
	}
	if(this._haveAdditionBgEl === null) {
		this._haveAdditionBgEl = this._$.find('.scsAddBgEl');
		if(!this._haveAdditionBgEl.size()) {
			this._haveAdditionBgEl = false;
		}
	}
	if(this._haveAdditionBgEl) {
		this._haveAdditionBgEl.css({
			'background-color': color
		});
	}
	if(this.get('bgcolor-clb')) {
		var clbName = this.get('bgcolor-clb');
		if(typeof(this[clbName]) === 'function') {
			this[clbName]( color );
		}
	}
};
/**
 * Icon item
 */
function scsElement_icon(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuIconExl';
	}
	this.includePostLinks = true;
	this._menuClass = 'scsElementMenu_icon';
	scsElement_icon.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_icon, scsElementBase);
scsElement_icon.prototype._setColor = function(color) {
	this.set('color', color);
	this._getEditArea().css('color', color);
};
scsElement_icon.prototype._getLink = function() {
	var $link = this._$.find('a.scsLink');
	return $link.size() ? $link : false;
};
scsElement_icon.prototype._setLinkAttr = function(attr, val) {
	switch(attr) {
		case 'href':
			if(val) {
				var $link = this._createLink();
				$link.attr(attr, val);
			} else
				this._removeLink();
			break;
		case 'title':
			var $link = this._createLink();
			$link.attr(attr, val);
			break;
		case 'target':
			var $link = this._createLink();
			val ? $link.attr('target', '_blank') : $link.removeAttr('target');
			break;
	}
};
scsElement_icon.prototype._createLink = function() {
	var $link = this._getLink();
	if(!$link) {
		$link = jQuery('<a class="scsLink" />').append( this._$.find('.scsInputShell') ).appendTo( this._$ );
		$link.click(function(e){
			e.preventDefault();
		});
	}
	return $link;
};
scsElement_icon.prototype._removeLink = function() {
	var $link = this._getLink();
	if($link) {
		this._$.append( $link.find('.scsInputShell') );
		$link.remove();
	}
};
/**
 * Table column element
 */
function scsElement_table_col(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuTableColExl';
	}
	if(typeof(this._menuClass) === 'undefined') {
		this._menuClass = 'scsElementMenu_table_col';
	}
	if(typeof(this._isMovable) === 'undefined') {
		this._isMovable = true;
	}
	this._showMenuEvent = 'hover';
	this._colNum = 0;
	scsElement_table_col.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_table_col, scsElementBase);
scsElement_table_col.prototype._setColor = function(color) {
	if(color) {
		this.set('color', color);
	} else {
		color = this.get('color');
	}
	var enbColor = parseInt(this.get('enb-color'))
	,	block = this.getBlock()
	,	colNum = this._colNum
	,	cssTag = 'col color '+ colNum;
	if(enbColor) {
		block.setTaggedStyle(block.getParam('cell_color_css'), cssTag, {num: colNum, color: color});
	} else {
		block.removeTaggedStyle(cssTag);
	}
	//_scsSaveCanvas();
};
scsElement_table_col.prototype._setColNum = function(num) {
	this._colNum = num;
};
scsElement_table_col.prototype._afterDestroy = function() {
	scsElement_table_col.superclass._afterDestroy.apply(this, arguments);
	this._block.checkColWidthPerc();
};
scsElement_table_col.prototype._showSelectBadgeWnd = function() {
	this.hideMenu();
	scsUtils.showBadgesLibWnd( this );
};
scsElement_table_col.prototype._disableBadge = function() {
	this._getBadgeHtml().hide();
};
scsElement_table_col.prototype._setBadge = function(data) {
	if(data) {
		for(var key in data) {
			this.set('badge-'+ key, data[ key ]);
		}
	} else {
		data = this._getBadgeData();
	}
	if(!data) return;
	
	scsUtils.updateBadgePrevLib( this._getBadgeHtml().show() );
	this.set('enb-badge', 1);
	var $enbBadgeCheck = this._menu.$().find('[name=enb_badge_col]');
	$enbBadgeCheck.attr('checked', 'checked');
	scsCheckUpdate( $enbBadgeCheck );
};
scsElement_table_col.prototype._getBadgeData = function() {
	var keys = ['badge_name', 'badge_bg_color', 'badge_txt_color', 'badge_pos']
	,	data = {};
	for(var i = 0; i < keys.length; i++) {
		data[ keys[i] ] = this.get('badge-'+ keys[ i ]);
		if(!data[ keys[i] ])
			return false;
	}
	return data;
};
scsElement_table_col.prototype._getBadgeHtml = function() {
	var $badge = this._$.find('.scsColBadge');
	if(!$badge.size()) {
		$badge = jQuery('<div class="scsColBadge"><div class="scsColBadgeContent"></div></div>').appendTo( this._getEditArea() );
	}
	return $badge;
};
/**
 * Table description column element
 */
function scsElement_table_col_desc(jqueryHtml, block) {
	this._isMovable = false;
	scsElement_table_col_desc.superclass.constructor.apply(this, arguments);
	this.refreshHeight();
	var self = this;
	this.getBlock().$().bind('scsBlockContentChanged', function(){
		self.refreshHeight();
	});
}
extendScs(scsElement_table_col_desc, scsElement_table_col);
scsElement_table_col_desc.prototype.refreshHeight = function() {
	var sizes = this.getBlock().getMaxColsSizes();
	for(var key in sizes) {
		var $entity = this._$.find(sizes[ key ].sel);
		if($entity && $entity.size()) {
			if(key == 'cells' &&  sizes[ key ].height) {
				var cellNum = 0;
				$entity.each(function(){
					if(typeof(sizes[ key ].height[ cellNum ]) !== 'undefined') {
						jQuery(this).height( sizes[ key ].height[ cellNum ] );
					}
					cellNum++;
				});
			} else {
				$entity.height( sizes[ key ].height );
			}
		}
	}
};
scsElement_table_col_desc.prototype._initMenu = function() {
	scsElement_table_col_desc.superclass._initMenu.apply(this, arguments);
	// Column description created from usual table column element, with it's menu.
	// But we can't move or remove (we can hide this from block settings) this type of column, so let's just remove it's move handle from menu.
	var $moveHandle = this._menu.$().find('.scsElMenuMoveHandlerPlace')
	,	$removeBtn = this._menu.$().find('.scsRemoveElBtn');
	$moveHandle.next('.scsElMenuBtnDelimiter').remove();
	$moveHandle.remove();
	$removeBtn.prev('.scsElMenuBtnDelimiter').remove();
	$removeBtn.remove();
	this._menu.$().css('min-width', '130px');
};
/**
 * Table cell element
 */
function scsElement_table_cell(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuTableCellExl';
	}
	this._menuClass = 'scsElementMenu_table_cell';
	scsElement_table_cell.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_table_cell, scsElementBase);
scsElement_table_cell.prototype._initMenuClbs = function() {
	scsElement_table_cell.superclass._initMenuClbs.apply(this, arguments);
	var self = this;
	this._menuClbs['.scsTypeTxtBtn'] = function() {
		self._replaceElement('txt_cell_item', 'txt');
	};
	this._menuClbs['.scsTypeImgBtn'] = function() {
		self._replaceElement('img_cell_item', 'img');
	};
	this._menuClbs['.scsTypeIconBtn'] = function() {
		self._replaceElement('icon_cell_item', 'icon');
	};
};
scsElement_table_cell.prototype._replaceElement = function(toParamCode, type) {
	var editArea = this._getEditArea()
	,	elementIter = editArea.find('.scsEl').data('iter-num')
	,	block = this.getBlock();
	// Destroy current element in cell
	block.destroyElementByIterNum( elementIter );
	// Add new one
	editArea.html( block.getParam( toParamCode ) );
	block._initElementsForArea( editArea );
	this.set('type', type);
	this._menu.$().find('[name=type]').removeAttr('checked').filter('[value='+ type+ ']').attr('checked', 'checked');
};
/**
 * Table Cell Icon element
 */
function scsElement_table_cell_icon(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuTableCellIconExl';
	}
	this._changeable = true;
	scsElement_table_cell_icon.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_table_cell_icon, scsElement_icon);
/**
 * Table Cell Image element
 */
function scsElement_table_cell_img(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuTableCellImgExl';
	}
	this._changeable = true;
	scsElement_table_cell_img.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_table_cell_img, scsElement_img);
/**
 * Table Cell Image element
 */
function scsElement_table_cell_txt(jqueryHtml, block) {
	this._typeBtns = {
		octo_el_menu_type_txt: {
			text: toeLangScs('Text')
		,	type: 'txt'
		,	checked: true
		}
	,	octo_el_menu_type_img: {
			text: toeLangScs('Image / Video')
		,	type: 'img'
		}
	,	octo_el_menu_type_icon: {
			text: toeLangScs('Icon')
		,	type: 'icon'
		}
	};
	scsElement_table_cell_txt.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_table_cell_txt, scsElement_txt);
scsElement_table_cell_txt.prototype._afterEditorInit = function(editor) {
	var onclickClb = function() {
		
		var $btn = jQuery('#'+ this._id).find('button:first')
		,	$btnsGroupShell = $btn.parents('.mce-container.mce-btn-group:first')
		,	$radio = $btn.find('input[type=radio]')
		,	type = $radio.val();
		
		if(type === 'txt') return;
		
		$btnsGroupShell.find('input[type=radio]').removeAttr('checked');
		$radio.attr('checked') 
			? $radio.removeAttr('checked')
			: $radio.attr('checked', 'checked');
		scsCheckUpdateArea( $btnsGroupShell );
		// And now - let's make element change
		var element = this.settings._scsElement;
		element.getBlock().replaceElement(element, type+ '_item_html', type);
	},	onPostRenderClb = function(type, checked) {
		
		var $btnShell = jQuery('#'+ this._id)
		,	$btn = $btnShell.find('button:first')
		,	txt = $btn.html();
		$btn.html('<label><input type="radio" name="type" value="'+ type+ '" '+ (checked ? 'checked' : '')+' />'+ txt+ '</label>');
		scsInitCustomCheckRadio( $btn );
	};
	for(var btnKey in this._typeBtns) {
		editor.addButton(btnKey, {
			text: this._typeBtns[ btnKey ].text
		,	_scsType: this._typeBtns[ btnKey ].type
		,	_scsChecked: this._typeBtns[ btnKey ].checked
		,	_scsElement: this
		,	classes: 'btn'
		,	onclick: function() {
				jQuery.proxy(onclickClb, this)();
			}
		,	onpostrender: function(e) {
				jQuery.proxy(onPostRenderClb, this)(this.settings._scsType, this.settings._scsChecked);
			}
		});
	}
};
scsElement_table_cell_txt.prototype._beforeInit = function() {
	var btnsPack = [];
	for(var btnKey in this._typeBtns) {
		btnsPack.push( btnKey );
	}
	this._editorToolbarBtns.push( btnsPack );
};
/**
 * Grid column element
 */
function scsElement_grid_col(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuGridColExl';
	}
	this._menuClass = 'scsElementMenu_grid_col';
	scsElement_grid_col.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_grid_col, scsElementBase);
scsElement_grid_col.prototype._setColor = function(color) {
	if(color) {
		this.set('color', color);
	} else {
		color = this.get('color');
	}
	var enbColor = parseInt(this.get('enb-color'));
	if(enbColor) {
		this._getOverlay().css({
			'background-color': color
		,	'display': 'block'
		});
	} else {
		this._getOverlay().css({
			'display': 'none'
		});
	}
	_scsSaveCanvas();
};
scsElement_grid_col.prototype._setImg = function(imgUrl) {
	if(imgUrl) {
		this.set('bg-img', imgUrl);
	} else {
		imgUrl = this.get('bg-img');
	}
	var enbBgImg = parseInt(this.get('enb-bg-img'));
	if(enbBgImg) {
		this._getEditArea().css({
			'background-image': 'url("'+ imgUrl+ '")'
		});
	} else {
		this._getEditArea().css({
			'background-image': 'url("")'
		});
	}
	_scsSaveCanvas();
};
scsElement_grid_col.prototype._initMenuClbs = function() {
	scsElement_grid_col.superclass._initMenuClbs.apply(this, arguments);
	var self = this;
	this._menuClbs['.scsImgChangeBtn'] = function() {
		scsCallWpMedia({
			id: self._$.attr('id')
		,	clb: function(opts, attach, imgUrl) {
				self._setImg( imgUrl );
			}
		});
	};
};
scsElement_grid_col.prototype._afterDestroy = function() {
	this.getBlock()._recalcColsClasses();
};
/**
 * Delimiter
 */
function scsElement_delimiter(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuDelimiterExl';
	}
	this._menuClass = 'scsElementMenu_delimiter';
	scsElement_delimiter.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_delimiter, scsElementBase);
scsElement_delimiter.prototype._setColor = function(color) {
	this.set('color', color);
	this._$.find('.scsDelimContent').css('background-color', color);
};
scsElement_progress_bar.prototype._remove = function() {
	scsElement_progress_bar.superclass._remove.apply(this, arguments);
	jQuery(window).unbind('resize.el_progr_bar_'+ this._id);
};
// Clear prev. width as it can be just saved in html
scsElement_progress_bar.prototype.beforeSave = function() {
	scsElement_progress_bar.superclass.beforeSave.apply(this, arguments);
	var $fillBar = this._$.find('.scsFillProgrBar')
	,	$pointer = this._$.find('.scsPointerProgrBar');
	$fillBar.data('width', $fillBar.width()).css('width', '0px');
	$pointer.data('left', $pointer.css('left')).css('left', '0px');
};
scsElement_progress_bar.prototype.afterSave = function() {
	scsElement_progress_bar.superclass.afterSave.apply(this, arguments);
	var $fillBar = this._$.find('.scsFillProgrBar')
	,	$pointer = this._$.find('.scsPointerProgrBar');
	$fillBar.css('width', $fillBar.data('width'));
	$pointer.css('left', $pointer.data('left'));
};
/**
 * Timer element
 */
scsElement_timer.prototype._setColor = function(color) {
	this._$.css('color', color);
};
/**
 * Social Icons elements
 */
/*function scsElement_social_icons(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuSocIconsExl';
	}
	this._menuClass = 'scsElementMenu_social_icons';
	scsElement_social_icons.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_social_icons, scsElementBase);
scsElement_social_icons.prototype._setColor = function(color) {
	
};*/

