jQuery(document).ready(function(){
	jQuery('#scsMailTestForm').submit(function(){
		jQuery(this).sendFormScs({
			btn: jQuery(this).find('button:first')
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#scsMailTestForm').slideUp( 300 );
					jQuery('#scsMailTestResShell').slideDown( 300 );
				}
			}
		});
		return false;
	});
	jQuery('.scsMailTestResBtn').click(function(){
		var result = parseInt(jQuery(this).data('res'));
		jQuery.sendFormScs({
			btn: this
		,	data: {mod: 'mail', action: 'saveMailTestRes', result: result}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#scsMailTestResShell').slideUp( 300 );
					jQuery('#'+ (result ? 'scsMailTestResSuccess' : 'scsMailTestResFail')).slideDown( 300 );
				}
			}
		});
		return false;
	});
	jQuery('#scsMailSettingsForm').submit(function(){
		jQuery(this).sendFormScs({
			btn: jQuery(this).find('button:first')
		});
		return false; 
	});
});