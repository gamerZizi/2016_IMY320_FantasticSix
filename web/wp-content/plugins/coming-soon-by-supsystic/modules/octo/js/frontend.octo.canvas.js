function scsCanvas(octoData) {
	var self = this;
	
	this._data = octoData;
	this._$ = jQuery('#scsCanvas');
	
	if(this.getParam('font_family')) {
		this._setFont( this.getParam('font_family'), true );
	}

	if (! Object.keys(octoData.blocks).length && octoData.params.bg_img.length && ! g_scsEdit) {
		this.fitCanvasToScreen();

		jQuery(window).resize(function(){
			self.fitCanvasToScreen();
		});
	}

	var gaTracker = octoData.params.ga_tracking_id;

	if (!g_scsEdit
		&& !scsOcto.isPreviewMode
		&& typeof gaTracker == 'string'
		&& gaTracker.length) {
		this.loadGoogleAnalytics(gaTracker);
	}
}
scsCanvas.prototype.loadGoogleAnalytics = function (gaTracker) {
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', gaTracker, 'auto');
	ga('send', 'pageview');
};
scsCanvas.prototype.fitCanvasToScreen = function () {
	var canvasHeight = jQuery('#scsCanvas').height()
	,	wndHeight = jQuery(window).height();
	
	jQuery('#scsCanvas').height( wndHeight );
}
scsCanvas.prototype.get = function(key) {
	return this._data[ key ];
};
scsCanvas.prototype.getParam = function(key) {
	return (this._data.params && this._data.params[ key ]) 
		? this._data.params[ key ] 
		: false;
};
scsCanvas.prototype.setParam = function(key, value) {
	if(!this._data.params)
		return;
	this._data.params[ key ] = value;
};
scsCanvas.prototype.getRaw = function() {
	return this._$;
};
scsCanvas.prototype._setFont = function(fontFamily, notLoad) {
	var $fontLink = this._getFontLink()
	,	self = this;
	
	if (notLoad != true) {
		this._getFontLink().attr({
			'href': 'https://fonts.googleapis.com/css?family='+ encodeURIComponent(fontFamily)
		,	'data-font-family': fontFamily
		});
	}
	
	this._$.css({
		'font-family': fontFamily
	});
	
	this.setParam('font_family', fontFamily);
};
scsCanvas.prototype._getFontLink = function() {
	var $link = this._$.find('link.scsFont');
	if(!$link.size()) {
		$link = jQuery('<link class="scsFont" rel="stylesheet" type="text/css" href="" />').appendTo( this._$ );
	}
	return $link;
};
scsCanvas.prototype._setFillColor = function( color ) {
	if(typeof(color) === 'undefined') {
		color = this.getParam('bg_color');
	} else {
		this.setParam('bg_color', color);
	}
	this._$.css({
		'background-color': color
	});
};
scsCanvas.prototype._updateFillColorFromColorpicker = function( tinyColor ) {
	this._setFillColor( tinyColor.toRgbString() );
};
scsCanvas.prototype._setBgImg = function( url ) {
	if(typeof(url) === 'undefined') {
		url = this.getParam('bg_img');
	} else {
		this.setParam('bg_img', url);
	}
	if(url) {
		this._$.css({
			'background-image': 'url("'+ url+ '")'
		});
	} else {
		this._$.css({
			'background-image': 'url("")'
		});
	}
};
scsCanvas.prototype._setBgImgPos = function( pos ) {
	if(typeof(pos) === 'undefined') {
		pos = this.getParam('bg_img_pos');
	} else {
		this.setParam('bg_img_pos', pos);
	}
	switch(pos) {
		case 'stretch':
			this._$.css({
				'background-position': 'center center'
			,	'background-repeat': 'no-repeat'
			,	'background-attachment': 'fixed'
			,	'-webkit-background-size': 'cover'
			,	'-moz-background-size': 'cover'
			,	'-o-background-size': 'cover'
			,	'background-size': 'cover'
			});
			break;
		case 'center':
			this._$.css({
				'background-position': 'center center'
			,	'background-repeat': 'no-repeat'
			,	'background-attachment': 'scroll'
			,	'-webkit-background-size': 'auto'
			,	'-moz-background-size': 'auto'
			,	'-o-background-size': 'auto'
			,	'background-size': 'auto'
			});
			break;
		case 'tile':
			this._$.css({
				'background-position': 'left top'
			,	'background-repeat': 'repeat'
			,	'background-attachment': 'scroll'
			,	'-webkit-background-size': 'auto'
			,	'-moz-background-size': 'auto'
			,	'-o-background-size': 'auto'
			,	'background-size': 'auto'
			});
			break;
	}
};
scsCanvas.prototype._setBgType = function(type) {
	switch(type) {
		case 'color':
			this._setFillColor();
			break;
		case 'img':
			this._setBgImg();
			this._setBgImgPos();
			break;
	}
};
scsCanvas.prototype._getFaviconTag = function() {
	var $fav = jQuery('link[rel="shortcut icon"]');
	if(!$fav || !$fav.size()) {
		$fav = jQuery('<link rel="shortcut icon" href="" type="image/x-icon">').appendTo('head');
	}
	return $fav;
};
scsCanvas.prototype._setFavImg = function( url ) {
	if(typeof(url) === 'undefined') {
		url = this.getParam('fav_img');
	} else {
		this.setParam('fav_img', url);
	}
	if(url) {
		this._getFaviconTag().attr('href', url);
	} else {
		// We can't just remove it here - favicon wil be still there, because browser desided to do it in this way, sorry ;)
		// So, we just put it 1px transparent img.
		this._getFaviconTag().attr('href', SCS_DATA.onePxImg);
	}
};
scsCanvas.prototype.setKeywords = function(data) {
	this.setParam('keywords', data);
	this._getKeywordsTag().attr('content', data);
};
scsCanvas.prototype.setDescription = function(data) {
	this.setParam('description', data);
	this._getDescriptionTag().attr('content', data);
};
scsCanvas.prototype._getKeywordsTag = function() {
	var $tag = jQuery('meta[name="keywords"]');
	if(!$tag.size()) {
		$tag = jQuery('<meta name="keywords">').appendTo('head');
	}
	return $tag;
};
scsCanvas.prototype._getDescriptionTag = function() {
	var $tag = jQuery('meta[name="description"]');
	if(!$tag.size()) {
		$tag = jQuery('<meta name="description">').appendTo('head');
	}
	return $tag;
};