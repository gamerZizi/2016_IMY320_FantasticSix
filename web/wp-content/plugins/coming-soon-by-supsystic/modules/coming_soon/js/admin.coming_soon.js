jQuery(document).ready(function(){
	jQuery('#scsSettingsSaveBtn').click(function(){
		_scsSaveMainOpts();
		return false;
	});
	jQuery('#scsSettingsForm').submit(function(){
		jQuery(this).sendFormScs({
			btn: jQuery('#scsSettingsSaveBtn')
		,	onSuccess: function(res) {
				if(!res.error) {
					if(jQuery('#scsSettingsForm input[name="opt_values[cs_mode]"]:checked').val() == 'dsbl') {
						jQuery('#wp-admin-bar-comingsoon-supsystic').slideUp( g_scsAnimationSpeed );
					} else {
						jQuery('#wp-admin-bar-comingsoon-supsystic').slideDown( g_scsAnimationSpeed );
					}
				}
			}
		});
		return false;
	});
	jQuery('#scsSettingsForm input[name="opt_values[cs_mode]"]').change(function(){
		if(!jQuery(this).prop('checked')) return;
		var newMode = jQuery(this).val();
		jQuery('.scsOptCat_cs_mode_coming_soon, .scsOptCat_cs_mode_redirect').slideUp( g_scsAnimationSpeed );
		jQuery('.scsOptCat_cs_mode_'+ newMode).slideDown( g_scsAnimationSpeed );
	}).trigger('change');
	scsInitChangeTpl();
	jQuery('.preset-select-btn').click(function(){
		_scsSaveMainOpts();
	});
	jQuery('.template-list-main-select').click(function(e){
		if(!jQuery(this).parents('.temlplate-list-item:first').hasClass('sup-promo')) {
			e.preventDefault();
		}
	});
	// Transform al custom chosen selects
	jQuery('.chosen').chosen();
	
	jQuery('.excludeOrShow').click(function () {
		var $switch = jQuery(this)
		,	isExclude = +$switch.hasClass('show');

		jQuery('#excludeOrShowSwitch').val(isExclude);

		jQuery('.excludeOrShow' + (!isExclude ? '.exclude' : '.show')).css('font-weight', 'bold');
		jQuery('.excludeOrShow' + (isExclude ? '.exclude' : '.show')).css('font-weight', '300');
	});

	scsInitHideIpDlg();

	jQuery('.excludeOrShow' + (jQuery('#excludeOrShowSwitch').val() == 0 ? '.show' : '.exclude')).css('font-weight', '300');
});
function scsInitChangeTpl() {
	jQuery('.temlplate-list-item').click(function(){
		jQuery('.temlplate-list-item').removeClass('active');
		jQuery(this).addClass('active');
		var id = parseInt( jQuery(this).data('id') );
		if(id) {
			jQuery('#scsSettingsForm').find('[name="opt_values[cs_original_tpl_id]"]').val( jQuery(this).data('id') );
		}
	});
	var currentId = parseInt(jQuery('#scsSettingsForm').find('[name="opt_values[cs_original_tpl_id]"]').val());
	if(currentId) {
		jQuery('.temlplate-list .temlplate-list-item[data-id="'+ currentId+ '"]').addClass('active');
	}
}
function _scsSaveMainOpts() {
	jQuery('#scsSettingsForm').submit();
}
function scsInitHideIpDlg() {
	var $container = jQuery('#scsHideForIpWnd').dialog({
		modal:    true
	,	autoOpen: false
	,	width: 400
	,	height: 460
	,	buttons:  {
			OK: function() {
				jQuery('#scsSettingsForm')
					.find('[name="opt_values[hide_or_show_ip]"]')
					.val(
						jQuery('#scsIpTxt').val()
					);
				
				_scsSaveMainOpts();

				$container.dialog('close');
			}
		,	Cancel: function() {
				$container.dialog('close');
			}
		}
	});

	jQuery('.hideForIpBtn').click(function () {
		var $switch = jQuery(this)
		,	isHide = +$switch.hasClass('show');

		jQuery('#hideOrShowIPSwitch').val(isHide);

		jQuery('.hideForIpBtn' + (isHide ? '.hide' : '.show')).css('font-weight', 'bold');
		jQuery('.hideForIpBtn' + (!isHide ? '.hide' : '.show')).css('font-weight', '300');
	});

	jQuery('.hideForIpBtn' + (jQuery('#hideOrShowIPSwitch').val() == 1 ? '.show' : '.hide')).css('font-weight', '300');

	jQuery('#hideOrShowIPButton').click(function(){
		var ips = jQuery('#scsSettingsForm').find('[name="opt_values[hide_or_show_ip]"]').val()
		,	ipsArr = ips ? ips.split(",") : false;
		
		jQuery('#scsIpTxt').val(ipsArr ? ipsArr.join("\n") : '');

		$container.dialog('open');
		return false;
	});
}