/**
 * Theme Customizer custom scripts
 * Control of show/hide events for Customizer
 */
(function($) {
    //Add Upgrade Button
    $('.preview-notice').prepend('<span id="jomsom_upgrade"><a target="_blank" class="button btn-upgrade" href="' + jomsom_misc_links.upgrade_link + '">' + jomsom_misc_links.upgrade_text + '</a></span>');
    jQuery('#customize-info .btn-upgrade, .misc_links').click(function(event) {
        event.stopPropagation();
    });
})(jQuery);
