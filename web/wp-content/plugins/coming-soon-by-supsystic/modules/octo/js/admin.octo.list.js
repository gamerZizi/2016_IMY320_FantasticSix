jQuery(document).ready(function(){
	var tblId = 'scsPagesTbl';
	jQuery('#'+ tblId).jqGrid({ 
		url: scsTblDataUrl
	,	datatype: 'json'
	,	autowidth: true
	,	shrinkToFit: true
	,	colNames:[toeLangScs('ID'), toeLangScs('Label'), toeLangScs('Action')]
	,	colModel:[
			{name: 'ID', index: 'ID', searchoptions: {sopt: ['eq']}, width: '50', align: 'center'}
		,	{name: 'post_title', index: 'post_title', searchoptions: {sopt: ['eq']}, align: 'center'}
		,	{name: 'actions', index: 'actions', searchoptions: {sopt: ['eq']}, align: 'center'}
		]
	,	postData: {
			search: {
				text_like: jQuery('#'+ tblId+ 'SearchTxt').val()
			}
		}
	,	rowNum:10
	,	rowList:[10, 20, 30, 1000]
	,	pager: '#'+ tblId+ 'Nav'
	,	sortname: 'ID'
	,	viewrecords: true
	,	sortorder: 'desc'
	,	jsonReader: { repeatitems : false, id: '0' }
	,	caption: toeLangScs('Current Page')
	,	height: '100%' 
	,	emptyrecords: toeLangScs('You have no Pages for now.')
	,	multiselect: true
	,	onSelectRow: function(rowid, e) {
			var tblId = jQuery(this).attr('id')
			,	selectedRowIds = jQuery('#'+ tblId).jqGrid ('getGridParam', 'selarrrow')
			,	totalRows = jQuery('#'+ tblId).getGridParam('reccount')
			,	totalRowsSelected = selectedRowIds.length;
			if(totalRowsSelected) {
				jQuery('#scsPagesRemoveGroupBtn').removeAttr('disabled');
				if(totalRowsSelected == totalRows) {
					jQuery('#cb_'+ tblId).prop('indeterminate', false);
					jQuery('#cb_'+ tblId).attr('checked', 'checked');
				} else {
					jQuery('#cb_'+ tblId).prop('indeterminate', true);
				}
			} else {
				jQuery('#scsPagesRemoveGroupBtn').attr('disabled', 'disabled');
				jQuery('#cb_'+ tblId).prop('indeterminate', false);
				jQuery('#cb_'+ tblId).removeAttr('checked');
			}
			scsCheckUpdate(jQuery(this).find('tr:eq('+rowid+')').find('input[type=checkbox].cbox'));
			scsCheckUpdate('#cb_'+ tblId);
		}
	,	gridComplete: function(a, b, c) {
			var tblId = jQuery(this).attr('id');
			jQuery('#scsPagesRemoveGroupBtn').attr('disabled', 'disabled');
			jQuery('#cb_'+ tblId).prop('indeterminate', false);
			jQuery('#cb_'+ tblId).removeAttr('checked');
			if(jQuery('#'+ tblId).jqGrid('getGridParam', 'records'))	// If we have at least one row - allow to clear whole list
				jQuery('#scsPagesClearBtn').removeAttr('disabled');
			else
				jQuery('#scsPagesClearBtn').attr('disabled', 'disabled');
			// Custom checkbox manipulation
			scsInitCustomCheckRadio('#'+ jQuery(this).attr('id') );
			scsCheckUpdate('#cb_'+ jQuery(this).attr('id'));
		}
	,	loadComplete: function() {
			var tblId = jQuery(this).attr('id');
			if (this.p.reccount === 0) {
				jQuery(this).hide();
				jQuery('#'+ tblId+ 'EmptyMsg').show();
			} else {
				jQuery(this).show();
				jQuery('#'+ tblId+ 'EmptyMsg').hide();
			}
		}
	});
	jQuery('#'+ tblId+ 'NavShell').append( jQuery('#'+ tblId+ 'Nav') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-selbox').insertAfter( jQuery('#'+ tblId+ 'Nav').find('.ui-paging-info') );
	jQuery('#'+ tblId+ 'Nav').find('.ui-pg-table td:first').remove();
	jQuery('#'+ tblId+ 'SearchTxt').keyup(function(){
		var searchVal = jQuery.trim( jQuery(this).val() );
		if(searchVal && searchVal != '') {
			scsGridDoListSearch({
				text_like: searchVal
			}, tblId);
		}
	});
	
	jQuery('#'+ tblId+ 'EmptyMsg').insertAfter(jQuery('#'+ tblId+ '').parent());
	jQuery('#'+ tblId+ '').jqGrid('navGrid', '#'+ tblId+ 'Nav', {edit: false, add: false, del: false});
	jQuery('#cb_'+ tblId+ '').change(function(){
		jQuery(this).attr('checked') 
			? jQuery('#scsPagesRemoveGroupBtn').removeAttr('disabled')
			: jQuery('#scsPagesRemoveGroupBtn').attr('disabled', 'disabled');
	});
	jQuery('#scsPagesRemoveGroupBtn').click(function(){
		var selectedRowIds = jQuery('#scsPagesTbl').jqGrid ('getGridParam', 'selarrrow')
		,	listIds = [];
		for(var i in selectedRowIds) {
			var rowData = jQuery('#scsPagesTbl').jqGrid('getRowData', selectedRowIds[ i ]);
			listIds.push( rowData.ID );
		}
		var popupLabel = '';
		if(listIds.length == 1) {	// In table label cell there can be some additional links
			var labelCellData = scsGetGridColDataById(listIds[0], 'label', 'scsPagesTbl');
			popupLabel = jQuery(labelCellData).text();
		}
		var confirmMsg = listIds.length > 1
			? toeLangScs('Are you sur want to remove '+ listIds.length+ ' Pages?')
			: toeLangScs('Are you sure want to remove "'+ popupLabel+ '" Page?')
		if(confirm(confirmMsg)) {
			jQuery.sendFormScs({
				btn: this
			,	data: {mod: 'octo', action: 'removeGroup', listIds: listIds}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#scsPagesTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});
	jQuery('#scsPagesClearBtn').click(function(){
		if(confirm(toeLangScs('Clear whole pages list?'))) {
			jQuery.sendFormScs({
				btn: this
			,	data: {mod: 'octo', action: 'clear'}
			,	onSuccess: function(res) {
					if(!res.error) {
						jQuery('#scsPagesTbl').trigger( 'reloadGrid' );
					}
				}
			});
		}
		return false;
	});
	
	scsInitCustomCheckRadio('#'+ tblId+ '_cb');
});
