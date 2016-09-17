jQuery(document).ready(function(){
	// Tooltipster initialization
	var tooltipsterSettings = {
		contentAsHTML: true
	,	interactive: true
	,	speed: 250
	,	delay: 0
	,	animation: 'swing'
	,	maxWidth: 450
	};
	if(jQuery('.supsystic-tooltip').size()) {
		tooltipsterSettings.position = 'top-left';
		jQuery('.supsystic-tooltip').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-bottom').size()) {
		tooltipsterSettings.position = 'bottom-left';
		jQuery('.supsystic-tooltip-bottom').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-left').size()) {
		tooltipsterSettings.position = 'left';
		jQuery('.supsystic-tooltip-left').tooltipster( tooltipsterSettings );
	}
	if(jQuery('.supsystic-tooltip-right').size()) {
		tooltipsterSettings.position = 'right';
		jQuery('.supsystic-tooltip-right').tooltipster( tooltipsterSettings );
	}
});