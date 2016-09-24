var g_scsEdit = false
,	g_scsBlockFabric = null
,	g_scsCanvas = null
,	g_scsTimeStart = null;

(function () {
	var data = scsOcto.time
	,	dateObject = new Date(
			data.year
		, 	data.mon - 1
		, 	data.mday
		, 	data.hours
		, 	data.minutes
		, 	data.seconds
		);

	scsOcto.time = dateObject.getTime();

	g_scsTimeStart = Date.now();
})();

jQuery(document).ready(function(){
	_scsInitFabric();
	_scsInitCanvas( scsOcto );
	if(scsOcto && scsOcto.blocks && scsOcto.blocks.length) {
		for(var i = 0; i < scsOcto.blocks.length; i++) {
			g_scsBlockFabric.addFromHtml(scsOcto.blocks[ i ], jQuery('#scsCanvas .scsBlock[data-id="'+ scsOcto.blocks[ i ].id+ '"]'));
		}
	}
});
function _scsInitFabric() {
	g_scsBlockFabric = new scsBlockFabric();
}
function _scsGetFabric() {
	return g_scsBlockFabric;
}
function _scsInitCanvas(octoData) {
	g_scsCanvas = new scsCanvas( octoData );
}
function _scsGetCanvas() {
	return g_scsCanvas;
}
function _scsUpdateServerTime() {
	if (Date.now() == g_scsTimeStart) return;

	scsOcto.time = (Date.now() - g_scsTimeStart) + scsOcto.time;

	g_scsTimeStart = Date.now();
}