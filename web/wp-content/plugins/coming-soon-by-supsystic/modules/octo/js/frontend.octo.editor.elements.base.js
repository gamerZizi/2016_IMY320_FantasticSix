function scsElementBase(jqueryHtml, block) {
	this._iterNum = 0;
	this._id = 'el_'+ mtRand(1, 999999);
	this._animationSpeed = g_scsAnimationSpeed;
	this._$ = jqueryHtml;
	this._block = block;
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = '';
	}
	this._innerImgsCount = 0;
	this._innerImgsLoaded = 0;
	//this._$menu = null;
	this._menu = null;
	this._menuClbs = {};
	if(typeof(this._menuClass) === 'undefined') {
		this._menuClass = 'scsElementMenu';
	}
	this._menuOnBottom = false;
	this._code = 'base';

	this._initedComplete = false;
	this._editArea = null;
	if(typeof(this._isMovable) === 'undefined') {
		this._isMovable = false;
	}
	this._moveHandler = null;
	this._sortInProgress = false;
	if(typeof(this._showMenuEvent) === 'undefined') {
		this._showMenuEvent = 'click';
	}
	if(typeof(this._changeable) === 'undefined') {
		this._changeable = false;
	}
	if(g_scsEdit) {
		this._init();
		this._initMenuClbs();
		this._initMenu();

		var images = this._$.find('img');
		if(images && (this._innerImgsCount = images.size())) {
			this._innerImgsLoaded = 0;
			var self = this;
			images.load(function(){
				self._innerImgsLoaded++;
				if(self._$.find('img').size() == self._innerImgsLoaded) {
					self._afterFullContentLoad();
				}
			});
		}
	}
	this._onlyFirstHtmlInit();
	this._initedComplete = true;
}
scsElementBase.prototype.getId = function() {
	return this._id;
};
scsElementBase.prototype.getBlock = function() {
	return this._block;
};
scsElementBase.prototype._onlyFirstHtmlInit = function() {
	if(this._$ && !this._$.data('first-inited')) {
		this._$.data('first-inited', 1);
		return true;
	}
	return false;
};
scsElementBase.prototype.setIterNum = function(num) {
	this._iterNum = num;
	this._$.data('iter-num', num);
};
scsElementBase.prototype.getIterNum = function() {
	return this._iterNum;
};
scsElementBase.prototype.$ = function() {
	return this._$;
};
scsElementBase.prototype.getCode = function() {
	return this._code;
};
scsElementBase.prototype._setCode = function(code) {
	this._code = code;
};
scsElementBase.prototype._init = function() {
	this._beforeInit();
};
scsElementBase.prototype._beforeInit = function() {
	
};
scsElementBase.prototype.destroy = function() {
	
};
scsElementBase.prototype.get = function(opt) {
	return this._$.attr( 'data-'+ opt );	// not .data() - as it should be saved even after page reload, .data() will not create element attribute
};
scsElementBase.prototype.set = function(opt, val) {
	this._$.attr( 'data-'+ opt, val );	// not .data() - as it should be saved even after page reload, .data() will not create element attribute
};
scsElementBase.prototype._getEditArea = function() {
	if(!this._editArea) {
		this._editArea = this._$.children('.scsElArea');
		if(!this._editArea.size()) {
			this._editArea = this._$.find('.scsInputShell');
		}
	}
	return this._editArea;
};
scsElementBase.prototype._getOverlay = function() {
	return this._$.find('.scsElOverlay');
};
scsElementBase.prototype._isParent = function() {
	return parseInt(this.get('parent')) ? true : false;
};
/**
 * Standart button item
 */
function scsElement_btn(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuBtnExl';
	}
	this._menuClass = 'scsElementMenu_btn';
	this._haveAdditionBgEl = null;
	this.includePostLinks = true;
	scsElement_btn.superclass.constructor.apply(this, arguments);
}
extendScs(scsElement_btn, scsElementBase);
scsElement_btn.prototype._onlyFirstHtmlInit = function() {
	if(scsElement_btn.superclass._onlyFirstHtmlInit.apply(this, arguments)) {
		if(this.get('customhover-clb')) {
			var clbName = this.get('customhover-clb');
			if(typeof(this[clbName]) === 'function') {
				var self = this;
				this._getEditArea().hover(function(){
					self[clbName](true, this);
				}, function(){
					self[clbName](false, this);
				});
			}
		}
	}
};
scsElement_btn.prototype._hoverChangeFontColor = function( hover, element ) {
	if(hover) {
		jQuery(element)
			.data('original-color', this._getEditArea().css('color'))
			.css('color', jQuery(element).parents('.scsEl:first').attr('data-bgcolor'));	// Ugly, but only one way to get this value in dynamic way for now
	} else {
		jQuery(element)
			.css('color', jQuery(element).data('original-color'));
	}
};
scsElement_btn.prototype._hoverChangeBgColor = function( hover, element ) {
	var parentElement = jQuery(element).parents('.scsEl:first');	// Actual element html
	if(hover) {
		parentElement
			.data('original-color', parentElement.css('background-color'))
			.css('background-color', parentElement.attr('data-bgcolor'));	// Ugly, but only one way to get this value in dynamic way for now
	} else {
		parentElement
			.css('background-color', parentElement.data('original-color'));
	}
};
/**
 * Progress bar
 */
function scsElement_progress_bar(jqueryHtml, block) {
	scsElement_progress_bar.superclass.constructor.apply(this, arguments);
	this._progress = 0;
	this._animProgr = 0;
	this._animProgrSpeed = 1000;

	this.refreshProgress();
	var self = this;
	jQuery(window).bind('resize.el_progr_bar_'+ this._id, function(){
		self.refreshProgress();
	});
}
extendScs(scsElement_progress_bar, scsElementBase);
scsElement_progress_bar.prototype.refreshProgress = function() {
	var start = (new Date( _scsGetCanvas().getParam('maint_start') )).getTime()
	,	end = (new Date( _scsGetCanvas().getParam('maint_end') )).getTime()
	,	now = (new Date()).getTime()
	,	shellWidth = this._$.find('.scsProgrBarShell').innerWidth();
	if(!start || !end) return;
	this._progress = Math.ceil((now - start) * 100 / (end - start));

	this._$.find('.scsFillProgrBar').animate({
		'width': shellWidth * this._progress / 100
	}, this._animProgrSpeed);
	this._$.find('.scsPointerProgrBar').animate({
		'left': shellWidth * this._progress / 100
	}, this._animProgrSpeed);
	this._$.find('.scsValueProgrBar').html( this._progress );
};
/**
 * Timer (countdown)
 */
function scsElement_timer(jqueryHtml, block) {
	if(typeof(this._menuOriginalId) === 'undefined') {
		this._menuOriginalId = 'scsElMenuTimerExl';
	}
	this._menuClass = 'scsElementMenu_timer';
	scsElement_timer.superclass.constructor.apply(this, arguments);
	this._intervalId = false;
	this._progressTime = 0;
	this._$helpers = {};
	this._initHelpers();
	this.initFinishDate();
	this.refreshProgress();
}
extendScs(scsElement_timer, scsElementBase);
scsElement_timer.prototype.initFormat = function (jqueryHtml) {
	var dateFormat = jqueryHtml.attr('data-dateformat'),
		markers = {},
		markersName = [],
		markerStandart = {
			'd' : 'days',
			'h' : 'hours',
			'm' : 'minutes',
			's' : 'seconds'
		},
		maxGridCol = {
			'lg' : 0,
			'md' : 0,
			'sm' : 0,
			'xs' : 0
		},
		numberVisible = 0,
		maxColAttr = jqueryHtml.attr('data-datemaxcol');

	var nf = '';
	
	for (var i = 0, len = dateFormat.length; i < len; i++) {
		  if (nf.indexOf(dateFormat[i]) == -1)
		  		nf += dateFormat[i];
	}
	
	dateFormat = nf;
	
	if (! dateFormat || dateFormat.length == 0) return;
	var formatList = jqueryHtml.attr('data-dateformat').toString().split('');

	// creating a succession of markers
	for (var i = 0; i < formatList.length; i++) {
		if (formatList[i] in markerStandart) {
			if (! this._$helpers.hasOwnProperty(markerStandart[formatList[i]])) continue;

			markers[formatList[i]] = markerStandart[formatList[i]];
		}
	}

	for (var m in markers)
		markersName.push(markerStandart[m]);

	if (markersName.length == 0) return;

	var isMaxColSet = false;

	if (maxColAttr) {
		var properties = maxColAttr.split(', ');
		var obj = {};
		properties.forEach(function(property) {
		    var tup = property.split(':');
		    obj[tup[0]] = parseInt(tup[1]);
		});
		var newMaxObj = {};

		for (var i in maxGridCol) {
			newMaxObj[i] = maxGridCol[i];

			if (obj.hasOwnProperty(i)) {
				newMaxObj[i] = obj[i];

				isMaxColSet = true;
			}
		}

		maxGridCol = newMaxObj;
	}

	if (! isMaxColSet) {
		// calculation of the maximum value of a cell
		for(var key in this._$helpers) {
			var classList = this._$helpers[key].$root.attr('class').split(' ');
			
			if (! Array.isArray(classList)) continue;
			if (this._$helpers[key].$root.css('display') === 'none') continue;

			for (var i = 0;  i < classList.length; i++) {
				for (var gridType in maxGridCol) {
					if (classList[i].slice(0, (gridType.length + 12)) != 'col-'+gridType+'-offset-'
						&& classList[i].slice(0, (gridType.length + 5)) == 'col-'+gridType+'-') {
						maxGridCol[gridType] += parseInt(
							classList[i].substring(
								('col-'+gridType+'-').length
							)
						);
					}
				}
			}
		}
	}

	// hide unwanted elements
	for (var i in this._$helpers) {
		var entity = this._$helpers[i];

		if (markersName.indexOf(i) == -1) {
			entity.$root.hide();
			entity.$root.removeClass('visible');
		} else {
			entity.$root.show();
			if (!entity.$root.hasClass('visible'))
				entity.$root.addClass('visible');
			numberVisible++;
		}
	}

	// remove markers, elements that do not exits
	if (markersName.length > numberVisible && numberVisible > 0) {
		var newMarkersName = [];

		for (var i = 0; i < markersName.length; i++) {
			if (this._$helpers.hasOwnProperty(markersName[i])) {
				newMarkersName.push(markersName[i]);
			} else {
				var newMarkers = {};

				for (var j in markers) {
					if (markers[j] != markersName[i])
						newMarkers[j] = markers[j];
				}

				markers = newMarkers;
			}
		}

		markersName = newMarkersName;
	}
	
	var callFirstOffsetHandler = false,
		firstElem = 0,
		isFirstElemOffset = false,
		firstOffsetCol = {
		'lg' : 0,
		'md' : 0,
		'sm' : 0,
		'xs' : 0
	};

	// we set the width of the cell, based on the maximum value and the amount of
	for (var i in this._$helpers) {
		var entity = this._$helpers[i];

		if (markersName.indexOf(i) > -1) {
			var classes = entity.$root.attr("class").split(' ');
			
			jQuery.each(classes, function(i, c) {
			    for (var gridType in maxGridCol) { 
				    if (c.indexOf('col-'+gridType+'-') == 0
				    	&& c.indexOf('col-'+gridType+'-offset-') != 0)
				        entity.$root.removeClass(c);
				}
			});

			for (var gridType in maxGridCol) { 
				if (maxGridCol[gridType] <= 0) continue;
				var col = Math.floor(maxGridCol[gridType] / markersName.length);
				entity.$root.addClass('col-'+gridType+'-'+col);
			}
		}

		if (firstElem == 0)
			firstElem = this._$helpers[i];

		var comb = firstElem.$root.get(0).compareDocumentPosition(
						this._$helpers[i].$root.get(0)
		);

		if (comb & 2 && firstElem.$root.css('display') != 'none')
			firstElem = this._$helpers[i];
	}

	if (markersName.length == 1 || numberVisible == 0) return;

	if (firstElem != 0) {
		var classes = firstElem.$root.attr("class").split(' ');
			
		jQuery.each(classes, function(i, c) {
		    for (var gridType in maxGridCol) { 
			    if (c.indexOf('col-'+gridType+'-offset-') == 0) {
			    	firstOffsetCol[gridType] += parseInt(
			    		c.substring((gridType.length + 12))
			    	);

			    	isFirstElemOffset = true;
			    }
			}
		});
	}

	for (var i in this._$helpers) {
		var entity = this._$helpers[i];

		if (entity != firstElem) {
			jQuery.each(classes, function(i, c) {
			    for (var gridType in maxGridCol) { 
				    if (c.indexOf('col-'+gridType+'-offset-') == 0) {
				    	callFirstOffsetHandler = true;
				    }
				}
			});

			if (callFirstOffsetHandler) break;
		}
	}

	// makes sorting items according to the succession of markers
	var prevMark = 0;
	for (var m in markers) {
		if (prevMark == 0) {
			prevMark = markers[m];
			continue;
		}

		if (! this._$helpers.hasOwnProperty(prevMark)) continue;

		this._$helpers[prevMark]
			.$root
			.after(
				this._$helpers[markers[m]].$root
			);

		prevMark = markers[m];
	}

	if (callFirstOffsetHandler) {
		var fm = 0;

		for (var m in markers) {
			fm = markers[m];
			break;
		}

		for (var of in firstOffsetCol) {
			if (firstOffsetCol[of] == 0) continue;

			firstElem.$root.removeClass('col-'+of+'-offset-'+firstOffsetCol[of]);
			this._$helpers[fm].$root.addClass('col-'+of+'-offset-'+firstOffsetCol[of]);
		}
	}
};
scsElement_timer.prototype._initHelpers = function() {
	var self = this;
	this._$.find('.scsTimerNumShell').each(function(){
		self._$helpers[ jQuery(this).data('num') ] = {
			$root: jQuery(this)
		,	$num: jQuery(this).find('.scsTimerNum')
		};
	});
};
scsElement_timer.prototype.initFinishDate = function() {
	_scsUpdateServerTime();

	var endTime = (
			new Date (
				_scsGetCanvas().getParam('maint_end')
			)
		).getTime()
	,	currentTime = scsOcto.time;	

	this._progressTime = (endTime - currentTime) * (endTime < currentTime ? -1 : 1) / 1000;
};
scsElement_timer.prototype.refreshProgress = function() {
	this.destroyInterval();
	
	var self = this;
	this._intervalId = setInterval(function(){
		self._updateTimer();
	}, 1000);	// Update timer each second
};
scsElement_timer.prototype.destroyInterval = function() {
	if(this._intervalId !== false) {
		clearInterval( this._intervalId );
	}
};
scsElement_timer.prototype._updateTimer = function() {
	var values = {}
	,	min = 60
	,	hour = 60 * min
	,	day = 24 * hour
	,	isFirst = true;

	var endTime = (
			new Date (
				_scsGetCanvas().getParam('maint_end')
			)
		).getTime()
	,	currentTime = scsOcto.time;	

	values['days'] = Math.floor( this._progressTime / day );
	values['hours'] = Math.floor( this._progressTime / hour );
	values['minutes'] = Math.floor( this._progressTime / min );
	values['seconds'] = Math.floor( this._progressTime );

	for(var key in values) {
		 switch(key) {
			case 'days':
				break;
			case 'hours':
				if(values['days'])
					values[ key ] -= values['days'] * 24;

				break;
			case 'minutes':
				if(values['days'])
					values[ key ] -= values['days'] * 24 * 60;
				if(values['hours'])
					values[ key ] -= values['hours'] * 60;
				break;
			case 'seconds':
				if(values['days'])
					values[ key ] -= values['days'] * 24 * 60 * 60;
				if(values['hours'])
					values[ key ] -= values['hours'] * 60 * 60;
				if(values['minutes'])
					values[ key ] -= values['minutes'] * 60;
				break;
		}


		if (this._$helpers.hasOwnProperty(key)) {
			this._$helpers[ key ].$num.html(this._formatTimeNum(values[ key ]));
		}
	}

	var d = {};

	for (var k in this._$helpers) {
		if (!this._$helpers[k].$num.is(':visible')) continue;

		switch(k){
			case 'days' :
				d[k] = values[k];
			case 'hours':
				if (!d.hasOwnProperty('days'))
					d[k] = this._progressTime / hour ;
				else
					d[k] = null;
				break;
			case 'minutes':
				if (!d.hasOwnProperty('hours'))
					d[k] = this._progressTime / min ;
				else
					d[k] = null;
				break;
			case 'seconds':
				if (!d.hasOwnProperty('minutes'))
					d[k] = this._progressTime ;
				else
					d[k] = null;
				break;
		}

		if (d[k] != null) {
			var timeEntity = this._formatTimeNum(parseInt(d[k]));

			if (isFirst && endTime < currentTime && this._$helpers[key].$num.is(':visible')) {
				isFirst = false;

				timeEntity = '-' + timeEntity;
			}

			this._$helpers[ k ].$num.html(timeEntity);
		}
	}

	if (endTime > currentTime) {
		this._progressTime--;
	} else {
		this._progressTime++;
	}
};
scsElement_timer.prototype._formatTimeNum = function(num) {
	if(typeof(num) === 'number')
		num = num.toString();
	if(num.length < 2) {
		num = '0'+ num;
	}
	return num;
};

