var g_scsMainMenu = null
,	g_scsFileFrame = null	// File frame for wp media uploader
,	g_scsEdit = true
,	g_scsSortInProgress = false
,	g_scsTopBarH = 93	// Height of the Top Editor Bar
,	g_scsMainColorDark = '#09c9da'
,	g_scsMainTollbar = null
,	g_scsColorPickerOptions = {
		size: 2
	,	mode: 'hsv-h'
	,	actionCallback: function (eventObject, eventName, colorPicker) {
			if (! eventObject || ! eventName) return;
			else if ((eventName == 'keyup' || eventName == 'paste') & ! eventObject.hasOwnProperty('HEXModified')) return;
			else if (eventName != 'keyup' && eventName != 'paste' && eventName != 'keypress') return;

			var self = this
			,	colorValidation = /(^[0-9A-F]{6}$)|(^[0-9A-F]{3}$)/i
			,	HEX_INGORED_HANDLER = 'ColorPicker-Hex-Ingnored'
			,	sourceElement = eventObject.srcElement
			,	$sourceElement = jQuery(sourceElement)
			,	isHEXDisp = sourceElement.classList.contains('HEX-disp')
			,	colorValue = null
			,	setColorByHEX = function (colorValue) {
					var color = new Colors();

					color.setColor('#' + colorValue, 'hex', 1);
					color.colors.rgbaMixCustom = {luminance: 1};

					if (self.hasOwnProperty('renderCallback'))
						self.renderCallback(color.colors, color.options);

					if (self.hasOwnProperty('convertCallback'))
						self.convertCallback(color.colors);
				};

			colorValue = sourceElement.innerText;

			if (! colorValue.length) return;

			if (colorValidation.test(colorValue)) {
				colorPicker.setColor('#' + colorValue, 'hex', 1);

				colorPicker.startRender();
				colorPicker.stopRender();

				setColorByHEX(colorValue);
			}

			$sourceElement.html(colorValue);

			// set cursor to end of text
			setTimeout(function () {
				$sourceElement.focus();

				var node = sourceElement
				,	textNode = node.firstChild
				,	caret = textNode.nodeValue.length
				,	range = document.createRange()
				,	sel = window.getSelection();

				range.setStart(textNode, caret);
				range.setEnd(textNode, caret);

				sel.removeAllRanges();
				sel.addRange(range);
			});
		}
};
jQuery(document).ready(function(){
	// Adding beforeStart event for sortable
	var oldMouseStart = jQuery.ui.sortable.prototype._mouseStart;
	jQuery.ui.sortable.prototype._mouseStart = function (event, overrideHandle, noActivation) {
		this._trigger("beforeStart", event, this._uiHash());
		oldMouseStart.apply(this, [event, overrideHandle, noActivation]);
	};
	jQuery('#scsCanvas').on('click', 'a', function(event){
		event.preventDefault();
	});
	_scsInitMainMenu();
	_scsInitDraggable();
	_scsInitMainToolbar();

	_scsFitCanvasToScreen();
	jQuery(window).resize(function(){
		_scsFitCanvasToScreen();
		_scsGetMainToolbar().refresh();
	}).load(function(){
		window._octLoaded = true;
		_scsGetMainToolbar().refresh();
	});
	jQuery('.scsMainSaveBtn').click(function(){
		_scsSaveCanvas();
		return false;
	});
	_scsInitOctoDataChange();
	// Preview btn click
	jQuery('#scsPreviewTplBtn').click(function(e){
		var $self = jQuery(this);
		_scsSaveCanvas(function(){
			window.open( $self.attr('href') );
		});
		return false;
	});
	// Transform al custom chosen selects
	jQuery('.chosen').chosen({
		disable_search_threshold: 5
	});
});
function _scsInitMainToolbar() {
	g_scsMainTollbar = new _scsMainToolbar();
}
function _scsGetMainToolbar() {
	return g_scsMainTollbar;
}
function _scsInitMainMenu() {
	var mainDelay = 100;
	jQuery('.scsBlocksBar').slimScroll({
		height: jQuery(window).height() - g_scsTopBarH
	,	railVisible: true
	,	alwaysVisible: true
	,	allowPageScroll: true
	,	position: 'right'
	,	color: g_scsMainColorDark
	,	opacity: 1
	,	distance: 0
	,	borderRadius: '3px'
	,	wrapperPos: 'fixed'
	});
	jQuery('.scsBlocksBar').each(function(){
		var classes = jQuery(this).attr('class');
		jQuery(this).attr('class', 'scsBlockBarInner').parent().addClass(classes).attr('data-cid', jQuery(this).data('cid'));
	});
	jQuery('.scsMainBar').slimScroll({
		height: jQuery(window).height() - g_scsTopBarH
	,	railVisible: true
	,	alwaysVisible: true
	,	allowPageScroll: true
	,	color: g_scsMainColorDark
	,	opacity: 1
	,	distance: 0
	,	borderRadius: '3px'
	,	width: jQuery('.scsMainBar').width()
	,	wrapperPos: 'fixed'
	,	position: 'left'
	});
	jQuery('.scsMainBar').each(function(){
		var classes = jQuery(this).attr('class');
		jQuery(this).attr('class', 'scsMainBarInner').parent().addClass(classes);
	});
	g_scsMainMenu = new scsCategoriesMainMenu('.scsMainBar');
	jQuery('.scsBlocksBar').each(function(){
		g_scsMainMenu.addSubMenu(this);
	});
	jQuery('.scsMainBarHandle').click(function(){
		if(g_scsMainMenu.isVisible()) {
			g_scsMainMenu.checkHide();
		} else {
			g_scsMainMenu.checkShow();
		}
		return false;
	});
	jQuery('.scsCatElement').mouseover(function(){
		var self = this;
		this._scsMouseOver = true;
		var cid = jQuery(this).data('id');
		setTimeout(function(){
			if(self._scsMouseOver)
				g_scsMainMenu.showSubByCid( cid );
		}, mainDelay);
	}).mouseleave(function(e){
		this._scsMouseOver = false;
		var cid = jQuery(this).data('id')
		,	movedTo = jQuery(e.relatedTarget)
		,	movedToBlockBar = false
		,	movedToCatBar = false;
		if(movedTo) {
			movedToBlockBar = movedTo.hasClass('scsBlocksBar') || movedTo.parents('.scsBlocksBar:first').size();
			if(!movedToBlockBar)	// Do not detect this each time - save processor time:)
				movedToCatBar = movedTo.hasClass('scsCatElement') || movedTo.parents('.scsMainBar').size();
		}
		if(movedTo && movedTo.size()
			&& (movedToBlockBar || movedToCatBar)
		) {
			return;
		}
		g_scsMainMenu.hideSubByCid( cid );
	});
	jQuery('.scsBlocksBar').mouseleave(function(e){
		var cid = jQuery(this).data('cid');
		g_scsMainMenu.hideSubByCid( cid );
	});
}
function _scsInitDraggable() {
	jQuery('#scsCanvas').sortable({
		revert: true
	,	placeholder: 'ui-state-highlight'
	,	handle: '.scsBlockMove'	// Use this setting to enable handler, or 2 setting above - to make sure it will not interupt other block/element clicking
	,	start: function(event, ui) {
			g_scsBlockFabric.checkSortStart( ui );
			g_scsMainMenu.checkHide();
		}
	,	stop: function(event, ui) {
			g_scsBlockFabric.checkSortStop( ui );
			_scsSaveCanvasDelay( 400 );
		}
    });
    jQuery('.scsBlocksList .scsBlockElement').draggable({
		connectToSortable: '#scsCanvas'
	,	helper: 'clone'
	,	revert: 'invalid'
	,	stop: function(event, ui) {
			if(!ui.helper.parents('#scsCanvas').size()) {	// Element dropped not in the canvas container
				ui.helper.remove();
				return;
			}
			g_scsBlockFabric.addFromDrag( ui.helper, jQuery('#scsCanvas').find('.ui-state-highlight') );
			g_scsMainMenu.checkHide();
		}
    });
    jQuery('.scsBlocksList, .scsBlocksList li').disableSelection();
}
function _scsFitCanvasToScreen() {
	var canvasHeight = jQuery('#scsCanvas').height()
	,	wndHeight = jQuery(window).height();
	if(canvasHeight < wndHeight) {
		jQuery('#scsCanvas').height( wndHeight );
	}
}
function _scsShowMainLoader() {
	jQuery('.scsMainSaveBtn').width( jQuery('.scsMainSaveBtn').width() );
	jQuery('.scsMainSaveBtn').find('.scsMainSaveBtnTxt').hide();
	jQuery('.scsMainSaveBtn').find('.scsMainSaveBtnLoader').show();
	jQuery('.scsMainSaveBtn')
		.attr('disabled', 'disabled')
		.addClass('active');
	//jQuery('#scsMainLoder').slideDown( g_scsAnimationSpeed );
}
function _scsHideMainLoader() {
	jQuery('.scsMainSaveBtn').find('.scsMainSaveBtnTxt').show();
	jQuery('.scsMainSaveBtn').find('.scsMainSaveBtnLoader').hide();
	jQuery('.scsMainSaveBtn')
		.removeAttr('disabled')
		.removeClass('active');
	//jQuery('#scsMainLoder').slideUp( g_scsAnimationSpeed );
}
function _scsSaveCanvasDelay(delay) {
	delay = delay ? delay : 200;
	setTimeout(_scsSaveCanvas, delay);
}
function _scsSaveCanvas(clb) {
	if(typeof(scsOcto) === 'undefined' || !scsOcto || !scsOcto.id) {
		return;
	}
	_scsShowMainLoader();
	var saveData = {
		id: scsOcto.id
	,	blocks: g_scsBlockFabric.getDataForSave()
	,	octo: jQuery('#scsMainOctoForm').serializeAssoc()
	};
	jQuery.sendFormScs({
		data: {mod: 'octo', action: 'save', data: saveData}
	,	onSuccess: function(res){
			_scsHideMainLoader();
			if(!res.error) {
				if(res.data.id_sort_order_data) {
					var allBlocks = g_scsBlockFabric.getBlocks();
					if(allBlocks.length) {
						for(var i = 0; i < res.data.id_sort_order_data.length; i++) {
							var sortOrderFind = parseInt(res.data.id_sort_order_data[ i ].sort_order);
							for(var j = 0; j < allBlocks.length; j++) {
								if(allBlocks[ j ].get('sort_order') == sortOrderFind && !allBlocks[ j ].get('id')) {
									allBlocks[ j ].set('id', parseInt(res.data.id_sort_order_data[ i ].id));
								}
							}
						}
					}
				}
				if(clb && typeof(clb) === 'function') {
					clb();
				}
			}
		}
	});
}
function _scsSortInProgress() {
	return g_scsSortInProgress;
}
function _scsSetSortInProgress(state) {
	g_scsSortInProgress = state;
}
function _scsInitOctoDataChange() {
	// Transform all custom checkbox / radiobuttons in admin bar
	scsInitCustomCheckRadio('#scsMainOctoForm');
	// Label setting - should be as title for page
	jQuery('#scsMainOctoForm [name="label"]').change(function(){
		jQuery('head title').html( jQuery(this).val() );
	});
	// Font setting
	jQuery('#scsMainOctoForm [name="params[font_family]"]').change(function(){
		_scsGetCanvas()._setFont( jQuery(this).val() );
	});
	// Maintenance Start date
	jQuery('#scsMainOctoForm [name="params[maint_start]"]').change(function(){
		_scsGetCanvas().setParam('maint_start', jQuery(this).val());
		_scsAfterMaintDatesChange();
	});
	// Maintenance End date
	jQuery('#scsMainOctoForm [name="params[maint_end]"]').change(function(){
		_scsGetCanvas().setParam('maint_end', jQuery(this).val());
		_scsAfterMaintDatesChange();
	});
	// Bg type switch
	jQuery('#scsMainOctoForm [name="params[bg_type]"]').change(function(){
		if(!jQuery(this).prop('checked')) return;
		_scsGetCanvas()._setBgType( jQuery(this).val() );
	});
	// Bg color input init
	_scsCreateColorPickerOpt('.scsOctoBgColor',  _scsGetCanvas().getParam('bg_color'), function(container, color){
		_scsGetCanvas()._updateFillColorFromColorpicker( color.tiny );
	});
	// Bg img selection
	jQuery('#scsMainOctoForm .scsOctoBgImgBtn').click(function(e){
		e.preventDefault();
		scsCallWpMedia({
			clb: function(opts, attach, imgUrl) {
				__scsSetCanvasBgImgOpt( attach.url );
			}
		});
	});
	// Bg img clear
	jQuery('#scsMainOctoForm .scsOctoBgImgRemove').click(function(e){
		e.preventDefault();
		__scsSetCanvasBgImgOpt('');
	});
	// Favicon img selection
	jQuery('#scsMainOctoForm .scsOctoFavImgBtn').click(function(e){
		e.preventDefault();
		scsCallWpMedia({
			clb: function(opts, attach, imgUrl) {
				__scsSetCanvasFavImgOpt( attach.url );
			}
		});
	});
	// Favicon img clear
	jQuery('#scsMainOctoForm .scsOctoFavImgRemove').click(function(e){
		e.preventDefault();
		__scsSetCanvasFavImgOpt('');
	});
	// Bg img position options
	jQuery('#scsMainOctoForm [name="params[bg_img_pos]"]').change(function(){
		_scsGetCanvas()._setBgImgPos( jQuery(this).val() );
	});
	// Keywords meta tags manipulation
	jQuery('#scsMainOctoForm [name="params[keywords]"]').change(function(){
		_scsGetCanvas().setKeywords( jQuery(this).val() );
	});
	// Description meta tags manipulation
	jQuery('#scsMainOctoForm [name="params[description]"]').change(function(){
		_scsGetCanvas().setDescription( jQuery(this).val() );
	});
	// Reset template by default
	jQuery('#scsMainOctoForm .scsResetTplBtn').click(function(){
		if(confirm(toeLangScs('Are you sure want to reset template by default? This will remove all your changes in this template.'))) {
			jQuery.sendFormScs({
				btn: jQuery(this)
			,	data: {mod: 'octo', action: 'resetTpl', id: scsOcto.id}
			,	onSuccess: function(res) {
					if(!res.error) {
						toeReload();
					}
				}
			});
		}
		return false;
	});
	//
	jQuery('#scsBackToAdminBtn').click(function(){
		_scsSaveCanvas();
	});
}
function __scsSetCanvasBgImgOpt(url) {
	_scsGetCanvas()._setBgImg( url );
	jQuery('#scsMainOctoForm .scsOctoBgImg').attr('src', url ? url : jQuery('#scsMainOctoForm .scsOctoBgImg').data('noimg-url'));
	jQuery('#scsMainOctoForm input[name="params[bg_img]"]').val( url );
	url
		? jQuery('#scsMainOctoForm .scsOctoBgImgRemove').show()
		: jQuery('#scsMainOctoForm .scsOctoBgImgRemove').hide();
	setTimeout(function(){
		_scsGetMainToolbar().refresh();	// Image canb have different size - so we need to check toolbar settings after this
	}, 500);
}
function __scsSetCanvasFavImgOpt(url) {
	_scsGetCanvas()._setFavImg( url );
	jQuery('#scsMainOctoForm .scsOctoFavImg').attr('src', url ? url : jQuery('#scsMainOctoForm .scsOctoFavImg').data('noimg-url'));
	jQuery('#scsMainOctoForm input[name="params[fav_img]"]').val( url );
	url
		? jQuery('#scsMainOctoForm .scsOctoFavImgRemove').show()
		: jQuery('#scsMainOctoForm .scsOctoFavImgRemove').hide();
	setTimeout(function(){
		_scsGetMainToolbar().refresh();	// Image canb have different size - so we need to check toolbar settings after this
	}, 500);
}
function _scsAfterMaintDatesChange() {
	var progrElements = _scsGetFabric().getElementsByCode('progress_bar');
	if(progrElements) {
		for(var i = 0; i < progrElements.length; i++) {
			progrElements[ i ].refreshProgress();
		}
	}
	var timerElements = _scsGetFabric().getElementsByCode('timer');
	if(timerElements) {
		for(var i = 0; i < timerElements.length; i++) {
			timerElements[ i ].initFinishDate();
		}
	}
}
function _scsCreateColorPickerOpt(selector, color, clb) {
    var $input = jQuery(selector).find('.scsColorpickerInput'),
    	options = jQuery.extend({
    		convertCallback: function (colors) {
	    		var rgbaString = 'rgba(' + colors.webSmart.r + ',' + colors.webSmart.g + ',' + colors.webSmart.b + ',' + colors.alpha + ')';
	    		colors.tiny = new tinycolor( '#' + colors.HEX );
	    		colors.tiny.setAlpha( colors.alpha );
	    		colors.tiny.toRgbString = function () {
	    			return rgbaString;
	    		};

	    		if (clb)
	    			clb($input, colors);

	    		$input.val(rgbaString);
	    	}
    	},
    	g_scsColorPickerOptions
    );

    $input.css('background-color', color);

    $input.colorPicker(options);
}
