/**
 * Base block object - for extending
 * @param {object} blockData all block data from database (block database row)
 */
scsBlockBase.prototype.destroy = function() {
	this._clearElements();
	this._$.slideUp(this._animationSpeed, jQuery.proxy(function(){
		this._$.remove();
		g_scsBlockFabric.removeBlockByIter( this.getIter() );
		_scsSaveCanvas();
	}, this));
};
scsBlockBase.prototype.build = function(params) {
	params = params || {};
	var innerHtmlContent = '';
	if(this._data.css && this._data.css != '') {
		innerHtmlContent += '<style type="text/css" class="scsBlockStyle">'+ this._data.css+ '</style>';
	}
	if(this._data.html && this._data.html != '') {
		innerHtmlContent += '<div class="scsBlockContent">'+ this._data.html+ '</div>';
	}
	innerHtmlContent = '<div class="scsBlock" id="{{block.view_id}}">'+ innerHtmlContent+ '</div>';
	if(!this._data.session_id) {
		this._data.session_id = mtRand(1, 999999);
	}
	if(!this._data.view_id) {
		this._data.view_id = 'scsBlock_'+ this._data.session_id;
	}
	var template = twig({
		data: innerHtmlContent
	});
	var generatedHtml = template.render({
		block: this._data
	});
	this._$ = jQuery(generatedHtml);
	if(params.insertAfter) {
		this._$.insertAfter( params.insertAfter );
	}
	this._initElements();
	this._initHtml();
};
scsBlockBase.prototype.set = function(key, value) {
	this._data[ key ] = value;
};
scsBlockBase.prototype.setData = function(data) {
	this._data = data;
};
scsBlockBase.prototype.getData = function() {
	return this._data;
};
scsBlockBase.prototype.appendToCanvas = function() {
	this._$.appendTo('#scsCanvas');
};
scsBlockBase.prototype.getElementByIterNum = function(iterNum) {
	return this._elements[ iterNum ];
};
scsBlockBase.prototype.removeElementByIterNum = function(iterNum) {
	this._elements.splice( iterNum, 1 );
	if(this._elements && this._elements.length) {
		for(var i = 0; i < this._elements.length; i++) {
			this._elements[ i ].setIterNum( i );
		}
	}
};
scsBlockBase.prototype.destroyElementByIterNum = function(iterNum, clb) {
	this.getElementByIterNum( iterNum ).destroy( clb );	// It will call removeElementByIterNum() inside element destroy method
};
scsBlockBase.prototype._initHtml = function() {
	this._beforeInitHtml();
	this._initMenuHtml();
	this._buildMenu();
};
scsBlockBase.prototype._initMenuHtml = function() {
	this._$.prepend( jQuery('#scsBlockToolbarEx').clone().removeAttr('id') );
	this._$.find('.scsBlockRemove').click(jQuery.proxy(function(){
		if(confirm(toeLangScs('Are you sure want to delete this block?'))) {
			this.destroy();
		}
		return false;
	}, this));
	this._$.find('.scsBlockSettings').click(jQuery.proxy(function(event){
		jQuery('#'+ this._$.attr('id')).contextMenu({
			x: event.pageX - 100
		,	y: event.pageY
		});
	}, this));
};
scsBlockBase.prototype._beforeInitHtml = function() {
	
};
scsBlockBase.prototype._rebuildCss = function() {
	var template = twig({
		data: this._data.css
	});
	var generatedHtml = template.render({
		block: this._data
	});
	this.getStyle().html( generatedHtml );
};
scsBlockBase.prototype.getStyle = function() {
	return this._$.find('style.scsBlockStyle');
};
scsBlockBase.prototype.setTaggedStyle = function(style, tag, elData) {
	this.removeTaggedStyle( tag );
	var $style = this.getStyle()
	,	styleHtml = $style.html()
	,	tags = this._getTaggedStyleStartEnd( tag );
	
	var template = twig({
		data: style
	});
	var generatedStyle = template.render({
		el: elData
	,	table: this._data
	}),	fullGeneratedStyleTag = tags.start+ "\n"+ generatedStyle+ "\n"+ tags.end;
	$style.html(styleHtml+ fullGeneratedStyleTag);
	this.set('css', this.get('css')+ this._revertReplaceContent(fullGeneratedStyleTag));
};
scsBlockBase.prototype.removeTaggedStyle = function(tag, params) {
	params = params || {};
	var tags = this._getTaggedStyleStartEnd(tag, true)
	,	$style = params.$style ? params.$style : this.getStyle()
	,	styleHtml = params.styleHtml ? params.styleHtml : $style.html()
	,	replaceRegExp = new RegExp(tags.start+ '(.|[\n\r])+'+ tags.end, 'gmi');
	$style.html( styleHtml.replace(replaceRegExp, '') );
	this.set('css', this.get('css').replace(replaceRegExp, ''));
};
scsBlockBase.prototype.getTaggedStyle = function(tag) {
	// TODO: Finish this method
	var tags = typeof(tag) === 'string' ? this._getTaggedStyleStartEnd(tag) : tag;
};
scsBlockBase.prototype._getTaggedStyleStartEnd = function(tag, forRegExp) {
	return {
		start: forRegExp ? '\\/\\*start for '+ tag+ '\\*\\/' : '/*start for '+ tag+ '*/'
	,	end: forRegExp ? '\\/\\*end for '+ tag+ '\\*\\/' : '/*end for '+ tag+ '*/'
	};
};
scsBlockBase.prototype._initMenuItem = function(newMenuItemHtml, item) {
	if(this['_initMenuItem_'+ item.type] && typeof(this['_initMenuItem_'+ item.type]) === 'function') {
		var menuItemName = this.getParam('menu_item_name_'+ item.type);
		if(menuItemName && menuItemName != '') {
			newMenuItemHtml.find('.scsBlockMenuElTitle').html( menuItemName );
		}
		this['_initMenuItem_'+ item.type]( newMenuItemHtml, item );
	}
};
scsBlockBase.prototype._initMenuItem_align = function(newMenuItemHtml, item) {
	if(this._data.params && this._data.params.align) {
		//newMenuItemHtml.find('input[name="params[align]"]').val( this._data.params.align.val );
		//newMenuItemHtml.find('.scsBlockMenuElElignBtn').removeClass('active');
		//newMenuItemHtml.find('.scsBlockMenuElElignBtn[data-align="'+ this._data.params.align.val+ '"]').addClass('active');
		this._setAlign( this._data.params.align.val, true, newMenuItemHtml );
	}
	var self = this;
	newMenuItemHtml.find('.scsBlockMenuElElignBtn').click(function(){
		self._setAlign( jQuery(this).data('align') );
	});
};
scsBlockBase.prototype._clickMenuItem_align = function(options) {
	return false;
};
scsBlockBase.prototype._setAlign = function( align, ignoreAutoSave, menuItemHtml ) {
	var possibleAligns = ['left', 'center', 'right'];
	for(var i in possibleAligns) {
		this._$.removeClass('scsAlign_'+ possibleAligns[ i ]);
	}
	this._$.addClass('scsAlign_'+ align);
	this.setParam('align', align);
	
	if(!menuItemHtml) {
		var menuOpt = this._$.data('_contentMenuOpt');
		menuItemHtml = menuOpt.items.align.$node;
	}
	menuItemHtml.find('input[name="params[align]"]').val( align );
	menuItemHtml.find('.scsBlockMenuElElignBtn').removeClass('active');
	menuItemHtml.find('.scsBlockMenuElElignBtn[data-align="'+ align+ '"]').addClass('active');
	
	if(!ignoreAutoSave) {
		_scsSaveCanvas();
	}
};
// For now fill color used only in slider, but we assume that it can be used in other block types too - so let it be in base block type for now.
// But if it will be only for slider block type for a long type - you can move it to slider block class - OOP is really good for us:)
scsBlockBase.prototype._initMenuItem_fill_color = function(newMenuItemHtml, item) {
	var self = this;
	
	if(this._data.params && this._data.params.fill_color_enb && parseInt(this._data.params.fill_color_enb.val)) {
		newMenuItemHtml.find('input[name="params[fill_color_enb]"]').attr('checked', 'checked');
		this._updateFillColor( true );
	}
	
	newMenuItemHtml.find('input[name="params[fill_color_enb]"]').change(function(){
		self.setParam('fill_color_enb', jQuery(this).prop('checked') ? 1 : 0);
		self._updateFillColor();
	});
	
	var initColor = new tinycolor( self.getParam('fill_color') );
	initColor.setAlpha( self.getParam('fill_color_opacity') );
	
	var $input = newMenuItemHtml.find('.scsColorpickerInput'),
		options = jQuery.extend({
			convertCallback: function (colors) {
	    		var rgbaString = 'rgba(' + colors.webSmart.r + ',' + colors.webSmart.g + ',' + colors.webSmart.b + ',' + colors.alpha + ')';
	    		var tiny = new tinycolor( rgbaString, 'rgba' );

	    		self._updateFillColorFromColorpicker(tiny, true);

	    		$input.attr('value', rgbaString);
	    	}
		},
		g_scsColorPickerOptions
	);
	
	$input.css('background-color', initColor.toRgbString());
    $input.attr('value', initColor.toRgbString());
    $input.colorPicker(options);
};
scsBlockBase.prototype._updateFillColorFromColorpicker = function( color, ignoreAutoSave ) {
	this.setParam('fill_color', '#'+ color.toHex());
	this.setParam('fill_color_opacity', color.getAlpha());
	this._updateFillColor( ignoreAutoSave );
};
scsBlockBase.prototype._updateFillColor = function( ignoreAutoSave ) {
	var fillColorEnb = this.getParam('fill_color_enb') == 0 ? false : true
	,	overlay = this._$.find('.scsCoverOverlay')
	,	overlayUsed = overlay.size();
	if(!overlayUsed) {
		var fillColorSelector = this.getParam('fill_color_selector');
		overlay = fillColorSelector ? this._$.find( fillColorSelector ) : this._$;
	}
	if(fillColorEnb) {
		var fillColor = this.getParam('fill_color')
		,	fillColorOpacity = this.getParam('fill_color_opacity');

		if(overlayUsed) {
			overlay.css({
				'background-color': fillColor
			,	'opacity': fillColorOpacity
			}).show();
		} else {
			var fillColorObj = new tinycolor( fillColor );
			fillColorObj.setAlpha( fillColorOpacity );
			overlay.css({
				'background-color': fillColorObj.toRgbString()
			});
		}
	} else {
		if(overlayUsed)
			overlay.hide();
	}
	if(!ignoreAutoSave) {
		_scsSaveCanvas();
	}
};
scsBlockBase.prototype._clickMenuItem_fill_color = function(options) {
	// Show color-picker on menu item click
	options.items.fill_color.$node.find('.scsColorpickerInput').trigger('focus');
	/*var fillColorEnb = this.getParam('fill_color_enb')
	,	enbCheck = options.items.fill_color.$node.find('input[name="params[fill_color_enb]"]');
	fillColorEnb ? enbCheck.removeAttr('checked') : enbCheck.attr('checked', 'checked');
	scsCheckUpdate( enbCheck );
	enbCheck.trigger('change');*/
	// Just do nothing for now, if require change checkbox - uncoment code below
	return false;
};
scsBlockBase.prototype._initMenuItem_bg_img = function(newMenuItemHtml, item) {
	if(this._data.params && this._data.params.bg_img_enb && parseInt(this._data.params.bg_img_enb.val)) {
		newMenuItemHtml.find('input[name="params[bg_img_enb]"]').attr('checked', 'checked');
	}
	var self = this;
	newMenuItemHtml.find('input[name="params[bg_img_enb]"]').change(function(){
		self.setParam('bg_img_enb', jQuery(this).prop('checked') ? 1 : 0);
		self._updateBgImg();
	});
};
scsBlockBase.prototype._clickMenuItem_bg_img = function(options) {
	var self = this;
	scsCallWpMedia({
		id: this._$.attr('id')
	,	clb: function(opts, attach, imgUrl) {
			// we will use full image url from attach.url always here (not image with selected size imgUrl) - as this is bg image
			// but if you see really big issue with this - just try to do it better - but don't broke everything:)
			self.setParam('bg_img', attach.url);
			self._updateBgImg();
		}
	});
};
scsBlockBase.prototype._updateBgImg = function( ignoreAutoSave ) {
	this._rebuildCss();

	if(!ignoreAutoSave) {
		_scsSaveCanvas();
	}
};
scsBlockBase.prototype._clickMenuItem = function(key, options) {
	if(this['_clickMenuItem_'+ key] && typeof(this['_clickMenuItem_'+ key]) === 'function') {
		return this['_clickMenuItem_'+ key]( options );
	}
};
scsBlockBase.prototype._buildMenu = function() {
	if(this._data.params && this._data.params.menu_items && this._data.params.menu_items.val != '') {
		var itemKeys = this._data.params.menu_items.val.split('|')
		,	menuItems = {}
		,	self = this;
		for(var i = 0; i < itemKeys.length; i++) {
			menuItems[ itemKeys[i] ] = {
				type: itemKeys[i]
			,	callback: function(key, options) {
					return self._clickMenuItem( key, options );
				}
			,	iterNum: i
			};
			jQuery.contextMenu.types[ itemKeys[i] ] = function(item, opt, root) {
				var html = jQuery('#scsBlockMenuExl').find('.scsBlockMenuEl[data-menu="'+ item.type+ '"]');
				if(html && html.size()) {
					var newMenuItemHtml = html.clone();
					newMenuItemHtml.appendTo(this);
					// We can't use i here - as this is callback for earlier call, so use here our defined param iterNum
					if(item.iterNum < itemKeys.length - 1)	{	// Visual delimiter for all menu items except last one
						jQuery('<div class="scsBlockMenuDelim" />').appendTo(this);
					}
					self._initMenuItem( newMenuItemHtml, item );
				} else {
					console.log('Can not Find Element Menu Item: '+ item.type+ ' !!!');
				}
			};
		}
		var menuOscs = {
			selector: '#'+ this._$.attr('id')
		,	zIndex: 9999
		,	position: function(opt, x, y) {
				if(!opt._scsCustInpInited) {
					scsInitCustomCheckRadio( opt.$menu );
					opt._scsCustInpInited = true;
				}
				opt.$menu.css({top: y, left: x - opt.$menu.width() / 2});
			}
		,	items: menuItems
		};
		jQuery.contextMenu( menuOscs );
	}
};
scsBlockBase.prototype.getContent = function() {
	return this._$.find('.scsBlockContent:first');
};
scsBlockBase.prototype._revertReplaceContent = function(content) {
	var revertReplace = [
		{key: 'view_id'}
	];
	for(var i = 0; i < revertReplace.length; i++) {
		var key = revertReplace[ i ].key
		,	value = this.get( key )
		,	replaceFrom = [ value ]
		,	replaceTo = revertReplace[i].raw ? '{{table.'+ key+ '|raw}}' : '{{table.'+ key+ '}}';
		if(typeof(value) === 'string' && revertReplace[i].raw) {
			replaceFrom.push( value.replace(/\s+\/>/g, '>') );
		}
		for(var j = 0; j < replaceFrom.length; j++) {
			content = str_replace(content, replaceFrom[ j ], replaceTo);
		}
	}
	return content;
};
scsBlockBase.prototype.getHtml = function() {
	var html = this.getContent().html();
	return this._revertReplaceContent( html );
};
scsBlockBase.prototype.getCss = function() {
	var css = this.getStyle().html();
	return this._revertReplaceContent( css );
	return css;
};
scsBlockBase.prototype.getIter = function() {
	return this._iter;
};
scsBlockBase.prototype.beforeSave = function() {
	if(this._elements && this._elements.length) {
		for(var i = 0; i < this._elements.length; i++) {
			this._elements[ i ].beforeSave();
		}
	}
};
scsBlockBase.prototype.afterSave = function() {
	if(this._elements && this._elements.length) {
		for(var i = 0; i < this._elements.length; i++) {
			this._elements[ i ].afterSave();
		}
	}
};
scsBlockBase.prototype.mapElementsFromHtml = function($html, clb) {
	var self = this
	,	mapCall = function($el) {

		var element = self.getElementByIterNum( jQuery($el).data('iter-num') );
		if(element && element[ clb ]) {
			element[ clb ]();
		}
	};
	$html.find('.scsEl').each(function(){
		mapCall( this );
	});
	if($html.hasClass('scsEl')) {
		mapCall( $html );
	}
};
scsBlockBase.prototype.replaceElement = function(element, toParamCode, type) {
	// Save current element content - in new element internal data
	var oldElContent = element.$().get(0).outerHTML
	,	oldElType = element.get('type')
	,	savedContent = element.$().data('pre-el-content');
	if(!savedContent)
		savedContent = {};
	savedContent[ oldElType ] = oldElContent;
	// Check if there are already saved prev. data for this type of element
	var newHtmlContent = savedContent[ type ] ? savedContent[ type ] : this.getParam( toParamCode );
	// Create and append new element HTML after current element
	var $newHtml = jQuery( newHtmlContent );
	$newHtml.insertAfter( element.$() );
	// Destroy current element
	var self = this;
	this.destroyElementByIterNum(element.getIterNum(), function(){
		// Init new element after prev. one was removed
		var newElements = self._initElementsForArea( $newHtml );
		for(var i = 0; i < newElements.length; i++) {
			// Save prev. updated content info - in new elements $()
			newElements[ i ].$().data('pre-el-content', savedContent);
		}
		self.contentChanged();
	});
};
scsBlockBase.prototype.contentChanged = function() {
	this._$.trigger('scsBlockContentChanged', this);
};
/**
 * Price table block base class
 */
scsBlock_price_table.prototype.addColumn = function() {
	var $colsWrap = this._getColsContainer()
	,	$cols = this._getCols()
	,	$col = null
	,	self = this;
	if($cols.size()) {
		var $lastCol = $cols.last();
		this.mapElementsFromHtml($lastCol, 'beforeSave');
		$col = $cols.last().clone();
		this.mapElementsFromHtml($lastCol, 'afterSave');
	} else {
		$col = jQuery( this.getParam('new_column_html') );
	}
	$colsWrap.append( $col );
	this._initElementsForArea( $col );
	this._initRemoveRowBtns( $col.find('.scsCell') );
	this._refreshColNumbers();
	$cols = this._getCols();
	$cols.each(function(){
		var element = self.getElementByIterNum( jQuery(this).data('iter-num') );
		if(element) {
			// Update CSS style if required for updated classes
			element._setColor();
		}
	});
	this.checkColWidthPerc();
};
scsBlock_price_table.prototype.getColsNum = function() {
	return this._getCols().size();
};
scsBlock_price_table.prototype.addRow = function() {
	var $cols = this._getCols( true )
	,	self = this;
	$cols.each(function(){
		var $rowsWrap = jQuery(this).find('.scsRows')
		,	$cell = jQuery( self.getParam('new_cell_html') );
		$rowsWrap.append( $cell );
		self._initElementsForArea( $cell );
		self._initRemoveRowBtns( $cell );
	});
	this.contentChanged();
};
scsBlock_price_table.prototype.beforeSave = function() {
	scsBlock_price_table.superclass.beforeSave.apply(this, arguments);
	this._destroyRemoveRowBtns();
};
scsBlock_price_table.prototype.afterSave = function() {
	scsBlock_price_table.superclass.afterSave.apply(this, arguments);
	this._initRemoveRowBtns();
};
scsBlock_price_table.prototype._initRemoveRowBtns = function( $cell ) {
	var block = this;
	$cell = $cell ? $cell : this._$.find('.scsCell');
	$cell.each(function(){
		if(jQuery(this).find('.scsRemoveRowBtn').size()) {
			jQuery(this).find('.scsRemoveRowBtn').remove();
		}
		jQuery(this).append( jQuery('#scsRemoveRowBtnExl').clone().removeAttr('id') );
		jQuery(this).hover(function(){
			jQuery(this).find('.scsRemoveRowBtn').addClass('active');
		}, function(){
			jQuery(this).find('.scsRemoveRowBtn').removeClass('active');
		});
		jQuery(this).find('.scsRemoveRowBtn').click(function(){
			block._removeRow( jQuery(this).parents('.scsCell:first'));
			return false;
		});
	});
};
scsBlock_price_table.prototype._destroyRemoveRowBtns = function( $cell ) {
	this._$.find('.scsRemoveRowBtn').remove();
};
scsBlock_price_table.prototype._removeRow = function( $cell ) {
	var block = this
	,	cellIndex = $cell.index()
	,	$cols = this._getCols( true );
	$cols.each(function(){
		var $rowsWrap = jQuery(this).find('.scsRows')
		,	$removeCell = $rowsWrap.find('.scsCell:eq('+ cellIndex+ ')');
		if($removeCell && $removeCell.size()) {
			var $elements = $removeCell.find('.scsEl');
			$elements.each(function(){
				block.removeElementByIterNum( jQuery(this).data('iter-num') );
			});
			setTimeout(function(){
				$removeCell.animateRemoveScs( g_scsAnimationSpeed );
			}, g_scsAnimationSpeed);	// Wait animation speed time to finally remove cell html element
		}
	});
	setTimeout(function(){
		block.contentChanged();
	}, g_scsAnimationSpeed);
};
scsBlock_price_table.prototype.getRowsNum = function() {
	return this._getCols().first().find('.scsRows').find('.scsCell').size();
};
scsBlock_price_table.prototype._initHtml = function() {
	scsBlock_price_table.superclass._initHtml.apply(this, arguments);
	var $colsWrap = this._getColsContainer()
	,	self = this;
	$colsWrap.sortable({
		items: '.scsCol:not(.scsTableDescCol)'
	,	axis: 'x'
	,	handle: '.scsMoveHandler'
	,	start: function(e, ui) {
			_scsSetSortInProgress( true );
			var dragElement = self.getElementByIterNum( ui.item.data('iter-num') );
			if(dragElement) {
				dragElement.onSortStart();
			}
		}
	,	stop: function(e, ui) {
			_scsSetSortInProgress( false );
			var dragElement = self.getElementByIterNum( ui.item.data('iter-num') );
			if(dragElement) {
				dragElement.onSortStop();
			}
		}
	});
	// Set cols numbers for all columns
	this._refreshColNumbers();
	this._initRemoveRowBtns();
};
scsBlock_price_table.prototype._refreshColNumbers = function() {
	var	self = this
	,	$cols = this._getCols()
	,	num = 1;
	$cols.each(function(){
		var element = self.getElementByIterNum( jQuery(this).data('iter-num') );
		if(element) {
			element._setColNum( num );
			var classes = jQuery(this).attr('class')
			,	newClasses = '';
			newClasses = (classes.replace(/scsCol\-\d+/g, '')+ ' scsCol-'+ num).replace(/\s+/g, ' ');
			jQuery(this).attr('class', newClasses);
		}
		num++;
	});
};
scsBlock_price_table.prototype.getMaxColsSizes = function() {
	var $cols = this._getCols()
	,	sizes = {
			header: {sel: '.scsColHeader'}
		,	desc: {sel: '.scsColDesc'}
		,	rows: {sel: '.scsRows'}
		,	cells: {sel: '.scsCell'}
		,	footer: {sel: '.scsColFooter'}
	};
	$cols.each(function(){
		for(var key in sizes) {
			var $entity = jQuery(this).find(sizes[ key ].sel);
			if($entity && $entity.size()) {
				if(key == 'cells') {
					if(!sizes[ key ].height)
						sizes[ key ].height = [];
					var cellNum = 0;
					$entity.each(function(){
						var height = jQuery(this).height();
						if(!sizes[ key ].height[ cellNum ] || sizes[ key ].height[ cellNum ] < height) {
							sizes[ key ].height[ cellNum ] = height;
						}
						cellNum++;
					});
				} else {
					var height = $entity.height();
					if(!sizes[ key ].height || sizes[ key ].height < height) {
						sizes[ key ].height = $entity.height();
					}
				}
			}
		}
	});
	return sizes;
};
scsBlock_price_table.prototype._updateFillColorFromColorpicker = function( color, ignoreAutoSave ) {
	this.setParam('bg_color', color.toRgbString());
	this._updateFillColor( ignoreAutoSave );
};
scsBlock_price_table.prototype._updateFillColor = function( ignoreAutoSave ) {
	this._rebuildCss();
	if(!ignoreAutoSave) {
		_scsSaveCanvas();
	}
};
scsBlock_price_table.prototype._updateTextColorFromColorpicker = function( color, ignoreAutoSave ) {
	this.setParam('text_color', color.toRgbString());
	this._updateTextColor( ignoreAutoSave );
};
scsBlock_price_table.prototype._updateTextColor = function( ignoreAutoSave ) {
	this._rebuildCss();
	/*this._$.css({
		'color': this.getParam('text_color')
	});*/
};
scsBlock_price_table.prototype._getDescCol = function() {
	return this._$.find('.scsTableDescCol');
};
scsBlock_price_table.prototype.switchDescCol = function(state) {
	var $descCol = this._getDescCol();
	this.setParam('enb_desc_col', state ? 1 : 0);
	state 
		? $descCol.show()
		: $descCol.hide();
	this.checkColWidthPerc();
};
scsBlock_price_table.prototype.setColsWidth = function(width, perc) {
	width = parseInt(width);
	if(width) {
		if(!perc) {
			this.setParam('col_width', width);
		}
		var $cols = this._getCols( true );
		if(perc) {
			width += '%';
		} else {
			width += 'px';
		}
		$cols.css({
			'width': width 
		});
	}
};
scsBlock_price_table.prototype.checkColWidthPerc = function() {
	if(this.getParam('calc_width') === 'table') {
		this.setColWidthPerc();
	}
};
scsBlock_price_table.prototype.setColWidthPerc = function() {
	var $cols = this._getCols( parseInt(this.getParam('enb_desc_col')) );
	this.setColsWidth( 100 / $cols.size(), true );
};
scsBlock_price_table.prototype.setTableWidth = function(width, measure) {
	if(width && parseInt(width)) {
		width = parseInt(width);
		this.setParam('table_width', width);
	} else {
		width = this.getParam('table_width');
	}
	if(measure) {
		this.setParam('table_width_measure', measure);
	} else {
		measure = this.getParam('table_width_measure');
	}
	this._$.width( width+ measure );
};
scsBlock_price_table.prototype.setCalcWidth = function(type) {
	if(type) {
		this.setParam('calc_width', type);
	} else {
		type = this.getParam('calc_width');
	}
	switch(type) {
		case 'table':
			this.setTableWidth();
			this.setColWidthPerc();
			break;
		case 'col':
			this._$.width('auto');
			this.setColsWidth( this.getParam('col_width') );
			break;
	}
};
/**
 * Sliders block base class
 */

scsBlock_sliders.prototype.beforeSave = function() {
	scsBlock_sliders.superclass.beforeSave.apply(this, arguments);
	if(this._slider && this._slider.getCurrentSlide) {
		this._currentSlide = this._slider.getCurrentSlide();
	}
	this._destroySlider();
};
scsBlock_sliders.prototype.afterSave = function() {
	scsBlock_sliders.superclass.afterSave.apply(this, arguments);
	this._refreshSlides();
	this._initSlider();
};
scsBlock_sliders.prototype._destroySlider = function() {
	if(this._slider) {
		this._slider.destroySlider();
	}
};
scsBlock_sliders.prototype._clickMenuItem_add_slide = function(options, params) {
	params = params || {};
	var self = this;
	scsCallWpMedia({
		id: this._$.attr('id')
	,	clb: function(opts, attach, imgUrl) {
			self.beforeSave();
			var value = self._data.params.new_slide_html.val;
			var newSlideHtml = jQuery( value );
			newSlideHtml.find('.scsSlideImg').attr('src', imgUrl);
			self._$.find('.bxslider').append( newSlideHtml );
			var addedElements = self._initElementsForArea( newSlideHtml );
			// We added some elemtns, they were created and initialized - but all elements should be nulled, 
			// it was done in self.beforeSave(); for alll elements except those list. So, lets null them too, they will be re-initialized in 
			// code bellow - self.afterSave();
			if(addedElements && addedElements.length) {
				for(var i = 0; i < addedElements.length; i++) {
					addedElements[ i ].beforeSave();
				}
			}
			self.afterSave();
			_scsSaveCanvas();
			// We add slide to the end of slider - so let's go to new slide right now
			self._slider.goToSlide( self._slider.getSlideCount() - 1 );
			if(params.clb && typeof(params.clb) == 'function') {
				params.clb();
			}
		}
	});
};
scsBlock_sliders.prototype._clickMenuItem_edit_slides = function(options) {
	scsUtils.showSlidesEditWnd( this );
};
scsBlock_sliders.prototype._beforeInitHtml = function() {
	scsBlock_sliders.superclass._beforeInitHtml.apply(this, arguments);
	this._refreshSlides();
};
scsBlock_sliders.prototype._refreshSlides = function() {
	var iter = 1;
	this._$.find('.scsSlide').each(function(){
		jQuery(this).data('slide-id', iter);
		iter++;
	});
	this._slides = this._$.find('.scsSlide');
};
scsBlock_sliders.prototype.getSlides = function() {
	return this._slides;
};
scsBlock_sliders.prototype.getSliderShell = function() {
	return this._$.find('.scsSliderShell');
};
/**
 * Galleries block
 */
scsBlock_galleries.prototype.recalcRows = function() {
	var imgPerRow = parseInt(this.getParam('img_per_row'))
	,	rows = this._$.find('.row');
	
	for(var i = 0; i < rows.length; i++) {
		var rowImgsCount = jQuery(rows[ i ]).find('.scsGalItem').size();
		if(rowImgsCount < imgPerRow && rows[ i + 1 ]) {
			// TODO: Make it to append not only first one, but all first elements count (imgPerRow - rowImgsCount)
			jQuery(rows[ i ]).append( jQuery(rows[ i + 1 ]).find('.scsGalItem:first') );
		}
		if(rowImgsCount > imgPerRow) {
			if(rows[ i + 1 ]) {
				jQuery(rows[ i + 1 ]).prepend( jQuery(rows[ i ]).find('.scsGalItem:last') );
			} else {
				jQuery('<div class="row" />').insertAfter( rows[ i ] ).prepend( jQuery(rows[ i ]).find('.scsGalItem:last') );
			}
		}
	}
};
scsBlock_galleries.prototype._initHtml = function() {
	scsBlock_galleries.superclass._initHtml.apply(this, arguments);
	var self = this
	,	placeholderClasses = this._$.find('.scsGalItem').attr('class');
	placeholderClasses += ' ui-state-highlight-gal-item';
	this._$.sortable({
		revert: true
	,	placeholder: placeholderClasses
	,	handle: '.scsImgMoveBtn'
	,	items: '.scsGalItem'
	,	start: function(event, ui) {
			var galleryItem = self._$.find('.scsGalItem:first');
			ui.placeholder.css({
				'height': galleryItem.height()+ 'px'
			});
		}
	,	stop: function(event, ui) {
			self.recalcRows();
			setTimeout(function(){
				_scsSaveCanvas();
			}, 400);
		}
	});
	this._initLightbox();
};
scsBlock_galleries.prototype._clickMenuItem_add_gal_item = function(options, params) {
	params = params || {};
	var self = this;
	scsCallWpMedia({
		id: this._$.attr('id')
	,	clb: function(opts, attach, imgUrl) {
			self.beforeSave();
			var value = self.getParam('new_item_html');
			value = twig({
				data: value
			}).render({
				block: self._data
			});
			var	newItemHtml = jQuery( value );
			newItemHtml.find('.scsGalImg').attr('src', imgUrl).attr('data-full-img', attach.url);
			newItemHtml.find('.scsGalLink').attr('href', attach.url);

			var appendToRow = self._$.find('.row:last')
			,	imgPerRow = parseInt(self.getParam('img_per_row'));
			if(appendToRow.find('.scsGalItem').size() >= imgPerRow) {
				jQuery('<div class="row" />').insertAfter( appendToRow );
				appendToRow = self._$.find('.row:last');
			}
			appendToRow.append( newItemHtml );
			self._initLightbox();
			var addedElements = self._initElementsForArea( newItemHtml );
			// We added some elemtns, they were created and initialized - but all elements should be nulled, 
			// it was done in self.beforeSave(); for alll elements except those list. So, lets null them too, they will be re-initialized in 
			// code bellow - self.afterSave();
			if(addedElements && addedElements.length) {
				for(var i = 0; i < addedElements.length; i++) {
					addedElements[ i ].beforeSave();
				}
			}
			self.afterSave();
			_scsSaveCanvas();
			if(params.clb && typeof(params.clb) == 'function') {
				params.clb();
			}
		}
	});
};
scsBlock_galleries.prototype._updateFillColorFromColorpicker = function( color, ignoreAutoSave ) {
	this.setParam('fill_color', color.toRgbString());
	this.setParam('fill_color_opacity', color.getAlpha());
	this._updateFillColor( ignoreAutoSave );
};
scsBlock_galleries.prototype._updateFillColor = function( ignoreAutoSave ) {
	var fillColorEnb = this.getParam('fill_color_enb')
	,	captions = this._$.find('.scsGalItemCaption');
	if(fillColorEnb) {
		var fillColor = this.getParam('fill_color');
		captions.css({
			'background-color': fillColor
		}).show();
	} else {
		captions.hide();
	}
};
scsBlock_galleries.prototype._onShowFillColorPicker = function() {
	this._$.find('.scsGalItemCaption').addClass('mce-edit-focus');
};
scsBlock_galleries.prototype._onHideFillColorPicker = function() {
	this._$.find('.scsGalItemCaption').removeClass('mce-edit-focus');
	_scsSaveCanvas();
};
/**
 * Menu block base class
 */
scsBlock_menus.prototype._afterInitElements = function() {
	scsBlock_menus.superclass._afterInitElements.apply(this, arguments);
	// TODO: Fix this drag-&-drop menu items functionality, problem was when we enable it - text editor stop showing-up
	/*this._$.find('.scsMenuMain').sortable({
		items: '.scsMenuItem'
	,	delay: 150
	,	distance: 5
	});*/
};
scsBlock_menus.prototype._clickMenuItem_add_menu_item = function(options, params) {
	this._showAddMenuItemWnd();
};
scsBlock_menus.prototype._showAddMenuItemWnd = function() {
	scsUtils.addMenuItemWndBlock = this;
	if(!scsUtils.addMenuItemWnd) {
		scsUtils.addMenuItemWnd = jQuery('#scsAddMenuItemWnd').modal({
			show: false
		});
		scsUtils.addMenuItemWnd.find('.scsAddMenuItemSaveBtn').click(function(){
			var text = jQuery.trim( scsUtils.addMenuItemWnd.find('[name="menu_item_text"]').val() )
			,	link = jQuery.trim( scsUtils.addMenuItemWnd.find('[name="menu_item_link"]').val() )
			,	newWnd = scsUtils.addMenuItemWnd.find('[name="menu_item_new_window"]').attr('checked') ? 1 : 0;
			if(text && text != '') {
				if(link && link != '') {
					var newItemHtml = jQuery( scsUtils.addMenuItemWndBlock.getParam('new_item_html') )
					,	linkHtml = newItemHtml.find('a')
					,	menuMainRow = scsUtils.addMenuItemWndBlock._$.find('.scsMenuMain');
					link = scsUtils.converUrl( link );
					linkHtml.attr('data-mce-href', link).attr('href', link).html( text );
					if(newWnd) {
						linkHtml.attr('target', '_blank');
					}
					menuMainRow.append( newItemHtml );
					var addedElements = scsUtils.addMenuItemWndBlock._initElementsForArea( newItemHtml );
					_scsSaveCanvas();
					scsUtils.addMenuItemWnd.modal('hide');
				} else {
					scsUtils.addMenuItemWnd.find('[name="menu_item_link"]').addClass('scsInputError');
				}
			} else {
				scsUtils.addMenuItemWnd.find('[name="menu_item_text"]').addClass('scsInputError');
			}
			return false;
		});
		scsInitCustomCheckRadio( scsUtils.addMenuItemWnd );
	}
	scsUtils.addMenuItemWnd.find('[name="menu_item_text"]').removeClass('scsInputError').val(''),
	scsUtils.addMenuItemWnd.find('[name="menu_item_link"]').removeClass('scsInputError').val('');
	scsCheckUpdate( scsUtils.addMenuItemWnd.find('[name="menu_item_new_window"]').removeAttr('checked') );
	scsUtils.addMenuItemWnd.modal('show');
};
/**
 * Subscribe block base class
 */
scsBlock_subscribes.prototype.getFields = function() {
	if(!this._fields) {
		/*var fieldsStr = this.getParam('fields');
		this._fields = unserialize(fieldsStr);*/
		this._fields = this.getParam('fields');
	}
	return this._fields;
};
scsBlock_subscribes.prototype.updateFields = function() {
	this.setParam('fields', this._fields);
};
scsBlock_subscribes.prototype.setFieldParam = function(name, paramKey, paramVal) {
	this.getFields();
	if(this._fields.length) {
		for(var i = 0; i < this._fields.length; i++) {
			if(this._fields[i].name == name) {
				this._fields[i][ paramKey ] = paramVal;
				this.updateFields();
				break;
			}
		}
	}
};
scsBlock_subscribes.prototype.setFieldLabel = function(name, label) {
	this.setFieldParam(name, 'label', label);
};
scsBlock_subscribes.prototype.setFieldRequired = function(name, required) {
	this.setFieldParam(name, 'required', required);
};
scsBlock_subscribes.prototype.addField = function(data) {
	this.getFields();
	this._fields.push( data );
};
scsBlock_subscribes.prototype.removeField = function(name) {
	this.getFields();
	if(this._fields.length) {
		for(var i = 0; i < this._fields.length; i++) {
			if(this._fields[i].name == name) {
				this._fields.splice(i, 1);
				this.updateFields();
				break;
			}
		}
	}
};
scsBlock_subscribes.prototype._afterInitElements = function() {
	scsBlock_subscribes.superclass._afterInitElements.apply(this, arguments);
	var placeholderClasses = this._$.find('.scsElInput:first').attr('class');
	placeholderClasses += ' ui-state-highlight-gal-item';
	this._$.find('.scsFormShell').sortable({
		items: '.scsElInput'
	,	handle: '.scsMoveHandler'
	,	placeholder: placeholderClasses
	//,	containment: 'parent'
	//,	forceHelperSize: true
	//,	forcePlaceholderSize : true
	//,	cursorAt : {top: -20}
	,	start: function(event, ui) {
			var placeholderSub = ui.item.clone();
			placeholderSub.find('.scsElMenu').remove();
			ui.placeholder.html( placeholderSub.html() );
		}
	,	stop: function(event, ui) {
			_scsSaveCanvasDelay();
		}
	});
};
scsBlock_subscribes.prototype._clickMenuItem_sub_settings = function(options, params) {
	this._showSubSettingsWnd();
};
scsBlock_subscribes.prototype._showSubSettingsWnd = function() {
	scsUtils.subSettingsWndBlock = this;
	var self = this;
	if(!scsUtils.subSettingsWnd) {
		scsUtils.subSettingsWnd = jQuery('#scsSubSettingsWnd').modal({
			show: false
		});
		scsUtils.subSettingsWnd.find('.scsSubSettingsSaveBtn').click(function(){
			// TODO: Move such functionality (values to parameters) to separate class, or at least - to scsUtils
			scsUtils.subSettingsWnd.find('.scsSettingFieldsShell').find('input, textarea, select').each(function(){
				var paramName = jQuery(this).attr('name')
				,	paramCheckbox = jQuery(this).attr('type') == 'checkbox'
				,	paramValue = '';
				if(paramCheckbox) {
					paramValue = jQuery(this).prop('checked') ? 1 : 0;
				} else {
					paramValue = jQuery(this).val();
				}
				if(paramName.indexOf('[]')) {
					paramName = str_replace(paramName, '[]', '');
				}
				scsUtils.subSettingsWndBlock.setParam(paramName, paramValue);
			});
			_scsSaveCanvas();
			scsUtils.subSettingsWnd.modal('hide');
			return false;
		});
		scsInitCustomCheckRadio( scsUtils.subSettingsWnd );
		scsUtils.subSettingsWnd.find('#scsSubSettingsWndTabs').wpTabs();
		scsUtils.subSettingsWnd.find('.scsSettingFieldsShell [name=sub_dest]').change(function(){
			scsUtils.subSettingsWnd.find('.scsSettingFieldsShell .scsSubDestRow').slideUp( self._animationSpeed );
			scsUtils.subSettingsWnd.find('.scsSettingFieldsShell .scsSubDestRow.scsSubDestRow_'+ jQuery(this).val()).slideDown( self._animationSpeed );
		});
		scsUtils.subSettingsWnd.find('[name=sub_mailchimp_api_key]').change(function(){
			scsUtils.subUpdateMailchimpLists();
		});
	}
	// TODO: Move such functionality (parameters to values) to separate class, or at least - to scsUtils
	scsUtils.subSettingsWnd.find('.scsSettingFieldsShell').find('input, textarea, select').each(function(){
		var paramName = jQuery(this).attr('name')
		,	paramCheckbox = jQuery(this).attr('type') == 'checkbox'
		,	paramValue = scsUtils.subSettingsWndBlock.getParam( paramName );
		if(paramCheckbox) {
			parseInt(paramValue) 
				? jQuery(this).attr('checked', 'checked')
				: jQuery(this).removeAttr('checked');
			scsCheckUpdate( this );
		} else {
			jQuery(this).val( paramValue ? paramValue : jQuery(this).data('default') );
		}
	});
	scsUtils.subSettingsWnd.find('.scsSettingFieldsShell [name=sub_dest]').trigger('change');
	scsUtils.subUpdateMailchimpLists();
	scsUtils.subSettingsWnd.modal('show');
};
scsBlock_subscribes.prototype._clickMenuItem_add_field = function(options, params) {
	this._showAddFieldWnd();
};
scsBlock_subscribes.prototype._showAddFieldWnd = function() {
	scsUtils.subAddFieldWndBlock = this;
	if(!scsUtils.subAddFieldWnd) {
		scsUtils.subAddFieldWnd = jQuery('#scsAddFieldWnd').modal({
			show: false
		});
		scsUtils.subAddFieldWnd.find('.scsAddFieldSaveBtn').click(function(){
			var label = jQuery.trim( scsUtils.subAddFieldWnd.find('[name="new_field_label"]').val() )
			,	name = jQuery.trim( scsUtils.subAddFieldWnd.find('[name="new_field_name"]').val() )
			,	htmlType = scsUtils.subAddFieldWnd.find('[name="new_field_html"]').val()
			,	required = scsUtils.subAddFieldWnd.find('[name="new_field_reuired"]').prop('checked') ? 1 : 0;
			if(label && label != '') {
				if(name && name != '')  {
					scsUtils.subAddFieldWndBlock.getFields();
					var newItemHtml = jQuery( scsUtils.subAddFieldWndBlock.getParam('new_item_html') )
					,	inputHtml = newItemHtml.find('input')	// TODO: Make this work with all types of input (textarea, select, ...)
					,	formInputsShell = scsUtils.subAddFieldWndBlock._$.find('.scsFormFieldsShell');
					inputHtml.attr('placeholder', label).attr('name', name).attr('type', htmlType);
					if(required) {
						inputHtml.attr('required', '1');
					}
					formInputsShell.append( newItemHtml );
					var addedElements = scsUtils.subAddFieldWndBlock._initElementsForArea( newItemHtml );
					scsUtils.subAddFieldWndBlock.addField({
						name: name
					,	label: label
					,	html: htmlType
					,	required: required
					});
					_scsSaveCanvas();
					scsUtils.subAddFieldWnd.modal('hide');
				} else {
					scsUtils.subAddFieldWnd.find('[name="new_field_name"]').addClass('scsInputError');
				}
			} else {
				scsUtils.subAddFieldWnd.find('[name="new_field_label"]').addClass('scsInputError');
			}
			return false;
		});
		scsInitCustomCheckRadio( scsUtils.subAddFieldWnd );
	}
	scsUtils.subAddFieldWnd.find('[name="new_field_label"]').removeClass('scsInputError').val('');
	scsUtils.subAddFieldWnd.find('[name="new_field_name"]').removeClass('scsInputError').val('');
	scsUtils.subAddFieldWnd.find('[name="new_field_html"]').removeClass('scsInputError').val('text');
	scsCheckUpdate( scsUtils.subAddFieldWnd.find('[name="new_field_reuired"]').removeAttr('checked') );
	scsUtils.subAddFieldWnd.modal('show');
};
/*scsBlock_subscribes.prototype.beforeSave = function() {
	scsBlock_subscribes.superclass.beforeSave.apply(this, arguments);
};
scsBlock_subscribes.prototype.afterSave = function() {
	scsBlock_subscribes.superclass.afterSave.apply(this, arguments);
};*/
scsBlock_subscribes.prototype.getHtml = function() {
	var html = scsBlock_subscribes.superclass.getHtml.apply(this, arguments);
	// We should replace start and end of our form each time we are doing save - as we need this content to be dynamicaly generated
	html = html.replace(/<\!--sub_form_start_open-->.+<\!--sub_form_start_close-->/g, '{{block.sub_form_start|raw}}');
	html = html.replace(/<\!--sub_form_end_open-->.+<\!--sub_form_end_close-->/g, '{{block.sub_form_end|raw}}');
	return html;
};
/**
 * Grid block base class
 */
scsBlock_grids.prototype._getGridWrapper = function() {
	return this._$.find('.scsGridWrapper');
};
scsBlock_grids.prototype._getAllCols = function() {
	return this._getGridWrapper().find('.scsGridElement');
};
scsBlock_grids.prototype._getBootstrapItemClass = function(allCols, maxRowCols, newCol) {
	console.log('s');
	var res = {current: '', required: ''}
	,	colsNum = allCols.size();
	if(newCol)
		colsNum++;
	if(colsNum) {
		var lastCol = allCols.last()
		,	currBSClasses = lastCol && lastCol.size() ? scsUtils.extractBootstrapColsClasses( lastCol ) : []	// BS - BootStrap
		,	newItemClasses = newCol ? scsUtils.extractBootstrapColsClasses( newCol ) : false;
		if(newCol && newItemClasses.length) {
			for(var i = 0; i < newItemClasses.length; i++) {
				if(toeInArray(newItemClasses[ i ], currBSClasses) === -1) {
					currBSClasses.push( newItemClasses[i] );
				}
			}
		}
		res.current = currBSClasses.join(' ');
		var newI = colsNum > maxRowCols ? (12 / maxRowCols) : (12 / colsNum);	// 12 - is from bootstrap documentation - 12 cols per one responsive row
		var newBSClasses = jQuery.map(currBSClasses, function(element){
			if(element !== 'col') {
				element = element.replace(/(col\-\w{2}\-)(\d{1,2})/, '$1'+ newI);	// replace one last digit in class name, foe xample col-sm-4 - can be col-sm-3 now
			}
			return element;
		});
		res.required = newBSClasses.join(' ');
	} else {
		res.required = 'col col-sm-12';	// Full width column
	}
	return res;
	
};
scsBlock_grids.prototype._clickMenuItem_add_grid_item = function(options, params) {
	params = params || {};
	//var self = this;
	var wrapper = this._getGridWrapper()
	,	allCols = this._getAllCols()
	,	maxRowCols = parseInt(this.getParam('max_row_cols'))
	//,	lastCol = allCols && allCols.size() ? allCols.last() : false
	,	newItemHtml = this.getParam('new_item_html');
	
	newItemHtml = twig({
		data: newItemHtml
	}).render({
		block: this._data
	});
	var newItem = jQuery( newItemHtml );
	wrapper.append( newItem );
	var addedElements = this._initElementsForArea( newItem )
	,	rowClasses = this._getBootstrapItemClass( allCols, maxRowCols, newItem );
	// We can't use here allCols variable - as we need to get all columns including our last added column
	/*this._recalcColsClasses();
	this._getAllCols()
		.removeClass(rowClasses.current)
		.addClass(rowClasses.required);*/
	this._recalcColsClasses({
		rowClasses: rowClasses
	});
};
scsBlock_grids.prototype._recalcColsClasses = function(params) {
	params = params || {};
	var rowClasses = null
	,	allCols = this._getAllCols();
	if(!params.rowClasses) {
		var maxRowCols = parseInt(this.getParam('max_row_cols'));
		if(allCols && allCols.size()) {
			rowClasses = this._getBootstrapItemClass(allCols, maxRowCols);
			
		}
	} else {
		rowClasses = params.rowClasses;
	}
	if(rowClasses) {
		allCols
			.removeClass( rowClasses.current )
			.addClass( rowClasses.required );
		var allElements = this.getElements();
		if(allElements && allElements.length) {
			for(var i = 0; i < allElements.length; i++) {
				allElements[ i ].repositeMenu();
			}
		}
	}
};
