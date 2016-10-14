/**
 * Base block object - for extending
 * @param {object} blockData all block data from database (block database row)
 */
function scsBlockBase(blockData) {
	this._data = blockData;
	this._$ = null;
	this._original$ = null;
	this._id = 0;
	this._iter = 0;
	this._elements = [];
	this._animationSpeed = 300;
	//this._oneTimeElementsInited = false;
}
scsBlockBase.prototype.get = function(key) {
	return this._data[ key ];
};
scsBlockBase.prototype.getParam = function(key) {
	return this._data.params[ key ] ? this._data.params[ key ].val : false;
};
scsBlockBase.prototype.setParam = function(key, value) {
	if(!this._data.params[ key ]) this._data.params[ key ] = {};
	this._data.params[ key ].val = value;
};
scsBlockBase.prototype.getRaw = function() {
	return this._$;
};
/**
 * Alias for getRaw method
 */
scsBlockBase.prototype.$ = function() {
	return this.getRaw();
};
scsBlockBase.prototype.setRaw = function(jqueryHtml) {
	this._$ = jqueryHtml;
	this._resetElements();
	this._initHtml();
	if(this.getParam('font_family')) {
		this._setFont( this.getParam('font_family') );
	}
};
scsBlockBase.prototype._initElements = function() {
	this._initElementsForArea( this._$ );
};
scsBlockBase.prototype._initElementsForArea = function(area) {
	var block = this
	,	addedElements = [];
	var initElement = function(htmlEl) {
		var elementCode = jQuery(htmlEl).data('el')
		,	elementClass = window[ 'scsElement_'+ elementCode ];
		if(elementClass) {
			var newElement = new elementClass(jQuery(htmlEl), block);
			newElement._setCode(elementCode);
			var newIterNum = block._elements.push( newElement );
			addedElements.push( newElement );
			newElement.setIterNum( newIterNum - 1 );	// newIterNum == new length of _elements array, iterator number for element - is new length - 1
		} else {
			if(g_scsEdit)
				console.log('Undefined Element ['+ elementCode+ '] !!!');
		}
	};
	jQuery( area ).find('.scsEl').each(function(){
		initElement(this);
	});
	if(jQuery( area ).hasClass('scsEl')) {
		initElement( area );
	}
	this._afterInitElements();
	return addedElements;
};
scsBlockBase.prototype._afterInitElements = function() {
	
};
scsBlockBase.prototype._resetElements = function() {
	this._clearElements();
	this._initElements();
};
scsBlockBase.prototype._clearElements = function() {
	if(this._elements && this._elements.length) {
		for(var i = 0; i < this._elements.length; i++) {
			this._elements[ i ].destroy();
		}
		this._elements = [];
	}
};
scsBlockBase.prototype.getElements = function() {
	return this._elements;
};
scsBlockBase.prototype._initHtml = function() {

};
/**
 * ID number in list of canvas elements
 * @param {numeric} iter Iterator - number in all blocks array - for this element
 */
scsBlockBase.prototype.setIter = function(iter) {
	this._iter = iter;
};
scsBlockBase.prototype.showLoader = function(txt) {
	var loaderHtml = jQuery('#scsBlockLoader');
	txt = txt ? txt : loaderHtml.data('base-txt');
	loaderHtml.find('.scsBlockLoaderTxt').html( txt );
	loaderHtml.css({
		'height': this._$.height()
	,	'top': this._$.offset().top
	}).addClass('active');
};
scsBlockBase.prototype.hideLoader = function() {
	var loaderHtml = jQuery('#scsBlockLoader');
	loaderHtml.removeClass('active');
};
scsBlockBase.prototype._setFont = function(fontFamily) {
	var $fontLink = this._getFontLink();
	if($fontLink.data('font-family') === fontFamily) {	// It is already loaded
		return;
	}
	this._getFontLink().attr({
		'href': 'https://fonts.googleapis.com/css?family='+ encodeURIComponent(fontFamily)
	,	'data-font-family': fontFamily
	});
	this._$.css({
		'font-family': fontFamily
	});
	this.setParam('font_family', fontFamily);
};
scsBlockBase.prototype._getFontLink = function() {
	var $link = this._$.find('link.scsFont');
	if(!$link.size()) {
		$link = jQuery('<link class="scsFont" rel="stylesheet" type="text/css" href="" />').appendTo( this._$ );
	}
	return $link;
};
/**
 * Price table block base class
 */
function scsBlock_price_table(blockData) {
	this._increaseHoverFontPerc = 20;	// Increase font on hover effect, %
	scsBlock_price_table.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_price_table, scsBlockBase);
scsBlock_price_table.prototype._getColsContainer = function() {
	return this._$.find('.scsColsWrapper:first');
};
scsBlock_price_table.prototype._getCols = function(includeDescCol) {
	return this._getColsContainer().find('.scsCol'+ (includeDescCol ? '' : ':not(.scsTableDescCol)'));
};
scsBlock_price_table.prototype._afterInitElements = function() {
	scsBlock_price_table.superclass._afterInitElements.apply(this, arguments);
	if(parseInt(this.getParam('enb_hover_animation'))) {
		this._initHoverEffect();
	}
};
scsBlock_price_table.prototype._initHoverEffect = function() {
	/*if(_scsIsEditMode()) {
		this.setParam('enb_hover_animation', 1);
		return;
	}*/
	var $cols = this._getCols()
	,	self = this;
	this._disableHoverEffect( $cols );
	$cols.bind('hover.animation', function(e){
		switch(e.type) {
			case 'mouseenter': case 'mousein':
				jQuery(this).addClass('hover');
				self._increaseHoverFont( jQuery(this) );
				break;
			case 'mouseleave': case 'mouseout':
				jQuery(this).removeClass('hover');
				self._backHoverFont( jQuery(this) );
				break;
		}
	});
	this.setParam('enb_hover_animation', 1);
};
scsBlock_price_table.prototype._increaseHoverFont = function($col) {
	var self = this;
	$col.find('.scsColDesc span').each(function(){
		var newFontSize = jQuery(this).data('new-font-size');
		if(!newFontSize) {
			var prevFontSize = jQuery(this).css('font-size')
			,	fontUnits = prevFontSize.replace(/\d+/, '')
			,	fontSize = parseInt(str_replace(prevFontSize, fontUnits, ''));
			if(fontSize && fontUnits) {
				newFontSize = Math.ceil(fontSize + (self._increaseHoverFontPerc * fontSize / 100));
				jQuery(this)
					.data('prev-font-size', prevFontSize)
					.data('font-units', fontUnits)
					.data('new-font-size', newFontSize);
			}
		}
		if(newFontSize) {
			jQuery(this).css('font-size', newFontSize+ jQuery(this).data('font-units'));
		}
	});
	if(_scsIsEditMode()) {
		setTimeout(function(){
			var colElement = self.getElementByIterNum($col.data('iter-num'));
			if(colElement) {
				colElement.repositeMenu();
			}
		}, g_scsHoverAnim);	// 300 - standard animation speed
	}
};
scsBlock_price_table.prototype._backHoverFont = function($col) {
	$col.find('.scsColDesc span').each(function(){
		var prevFontSize = jQuery(this).data('prev-font-size');
		if(prevFontSize) {
			jQuery(this).css('font-size', prevFontSize);
		}
	});
};
scsBlock_price_table.prototype._disableHoverEffect = function($cols) {
	this.setParam('enb_hover_animation', 0);
	//if(_scsIsEditMode()) return;
	$cols = $cols ? $cols : this._getCols();
	$cols.unbind('hover.animation');
};
/**
 * Covers block base class
 */
function scsBlock_covers(blockData) {
	scsBlock_covers.superclass.constructor.apply(this, arguments);

	//this._resizeBinded = false;
	this._bindResize();
}
extendScs(scsBlock_covers, scsBlockBase);
scsBlock_covers.prototype._initHtml = function() {
	scsBlock_covers.superclass._initHtml.apply(this, arguments);
	this._onResize();
};
scsBlock_covers.prototype._bindResize = function() {
	jQuery(window).resize(jQuery.proxy(function(){
		this._onResize();
	}, this));
};
scsBlock_covers.prototype._onResize = function() {
	var wndHeight = jQuery(window).height();
	if (jQuery(window).width() < 745) 
		this._$.height( 'auto' );
	else
		this._$.height( wndHeight );
};
/**
 * Sliders block base class
 */
function scsBlock_sliders(blockData) {
	scsBlock_sliders.superclass.constructor.apply(this, arguments);
	this._slider = null;
	this._slides = null;
	this._currentSlide = 0;
}
extendScs(scsBlock_sliders, scsBlockBase);
scsBlock_sliders.prototype._initHtml = function() {
	scsBlock_sliders.superclass._initHtml.apply(this, arguments);
	this._initSlider();
};
scsBlock_sliders.prototype._initSlider = function() {
	var sliderElId = this._$.find('.bxslider').attr('id');
	this._slider = jQuery('#'+ sliderElId).bxSlider({
		/*infiniteLoop: false,
		hideControlOnEnd: false*/
		//adaptiveHeight: true
	});
	if(this._currentSlide) {
		this._slider.goToSlide( this._currentSlide );
	}
};
/**
 * Galleries block base class
 */
function scsBlock_galleries(blockData) {
	scsBlock_galleries.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_galleries, scsBlockBase);
scsBlock_galleries.prototype._initHtml = function() {
	scsBlock_galleries.superclass._initHtml.apply(this, arguments);
	this._initLightbox();
};
scsBlock_galleries.prototype._initLightbox = function() {
	this._$.find('.scsGalLink:not(.scsGalLinkOut)').prettyPhoto({
		slideshow: 5000
	,	social_tools: false
	,	deeplinking: false	// For now - let avoid placing hash in browser URL, maybe enable this latter
	});
};
/**
 * Banner block base class
 */
function scsBlock_banners(blockData) {
	scsBlock_banners.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_banners, scsBlockBase);
/**
 * Banner block base class
 */
function scsBlock_footers(blockData) {
	scsBlock_footers.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_footers, scsBlockBase);
/**
 * Menu block base class
 */
function scsBlock_menus(blockData) {
	scsBlock_menus.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_menus, scsBlockBase);
/**
 * Subscribe block base class
 */
function scsBlock_subscribes(blockData) {
	this._fields = null;
	scsBlock_subscribes.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_subscribes, scsBlockBase);
scsBlock_subscribes.prototype._initHtml = function() {
	scsBlock_subscribes.superclass._initHtml.apply(this, arguments);
	this._initForm();
};
scsBlock_subscribes.prototype._getForm = function() {
	return this._$.find('.scsSubscribeForm');
};
scsBlock_subscribes.prototype._getFormShell = function() {
	return this._$.find('.scsFormShell');
};
scsBlock_subscribes.prototype._initForm = function() {
	// Some forms require usual submit
	if(toeInArrayScs(this.getParam('sub_dest'), ['aweber'])) return;
	var form = this._getForm()
	,	self = this;
	form.submit(function(){
		var msgEl = jQuery(this).find('.scsSubMsg')
		,	form = jQuery(this);
		jQuery(this).sendFormScs({
			msgElID: msgEl
		,	msgCloseBtn: true
		,	hideLoader: true
		,	errorClass: 'alert alert-danger alert-dismissible'
		,	successClass: 'alert alert-success alert-dismissible'
		,	onBeforeSend: function() {
				self.showLoader();
			}
		,	onSuccess: function(res) {
				self.hideLoader();
				/*Add msg close btn*/
				
				//msgEl.append();
				if(!res.error) {
					msgEl.appendTo( self._getFormShell() );
					form.slideUp(self._animationSpeed, function(){
						form.remove();
					});
					/*setTimeout(function(){
						
					}, 2000);*/
				}
			}
		});
		return false;
	});
};
/**
 * Grid block base class
 */
function scsBlock_grids(blockData) {
	scsBlock_grids.superclass.constructor.apply(this, arguments);
}
extendScs(scsBlock_grids, scsBlockBase);

