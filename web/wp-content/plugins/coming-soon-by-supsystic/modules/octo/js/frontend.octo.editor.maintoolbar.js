function _scsMainToolbar() {
	this._$ = jQuery('#scsMainTopBar');
	this._$subBar = jQuery('#scsMainTopSubBar');
	this._$moreShell = jQuery('#scsMainOctoOptMore');
	this._subBarVisible = false;
	this._init();
	if(window._octLoaded)
		this.refresh();
}
_scsMainToolbar.prototype._init = function() {
	var self = this;
	this._$.find('.scsMainTopBarCenter').show();	// it is hidden until whole page will be loaded because elements there is unsorted
	this._$.find('#scsMainOctoOptMoreBtn').click(function(){
		if(self._subBarVisible) {
			self._hideSubBar();
		} else {
			self._showSubBar();
		}
		return false;
	});
};
_scsMainToolbar.prototype._showSubBar = function() {
	if(!this._subBarVisible) {
		this._$subBar.removeClass('flipOutX').addClass('active animated flipInX');
		this._$.find('#scsMainOctoOptMoreBtn').addClass('active');
		this._subBarVisible = true;
	}
};
_scsMainToolbar.prototype._hideSubBar = function() {
	if(this._subBarVisible) {
		this._$subBar.removeClass('flipInX').addClass('flipOutX');
		this._$.find('#scsMainOctoOptMoreBtn').removeClass('active');
		this._subBarVisible = false;
	}
};
_scsMainToolbar.prototype.refresh = function(params) {
	params = params || {};
	var optsWidgetWidth = this._$.width() - this._$.find('.scsMainTopBarLeft').outerWidth() - this._$.find('.scsMainTopBarRight').outerWidth()
	,	optsTotalWidth = this._$.find('.scsMainTopBarCenter').width()
	,	dMargin = 10;
	if(optsWidgetWidth < optsTotalWidth && !params.recursiveBackToMain) {	// Move main elements - to sub panel when in main panel there are no place for them
		var $lastElementInSet = this._$.find('.scsMainOctoOpt:not(#scsMainOctoOptMore):last');
		if($lastElementInSet && $lastElementInSet.size()) {
			$lastElementInSet.get(0)._octWidth = $lastElementInSet.width();	// remember it's width for case when we will need it - but element can be hidden
			$lastElementInSet.prependTo( this._$subBar );
			this._$moreShell.show();
			this.refresh({recursiveToSub: true});
		}
	} else {	// Check if we can move some elements - back to main panel
		var $firstOptInSub = this._$subBar.find('.scsMainOctoOpt:first');
		if($firstOptInSub && $firstOptInSub.size()) {
			if(optsWidgetWidth > optsTotalWidth + $firstOptInSub.get(0)._octWidth + dMargin) {
				$firstOptInSub.insertBefore( this._$moreShell );
				this.refresh({recursiveBackToMain: true});
			}
		} else {
			this._$moreShell.hide();
			this._hideSubBar();
		}
	}
};
_scsMainToolbar.prototype._getAllOptsShells = function() {
	var $shells = this._$.find('.scsMainOctoOpt:not(#scsMainOctoOptMore)');
	return $shells;
};