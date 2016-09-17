jQuery(document).ready(function ($) {
	// Search Toggle
	var $header_search = $( '#search-toggle' );
	$header_search.click( function() {
		var $this_el_search = jQuery(this),
			$form_search = $this_el_search.siblings( '#search-container' );

		if ( $form_search.hasClass( 'displaynone' ) ) {
			$form_search.removeClass( 'displaynone' ).addClass( 'displayblock' ).animate( { opacity : 1 }, 300 );
		} else {
			$form_search.removeClass( 'displayblock' ).addClass( 'displaynone' ).animate( { opacity : 0 }, 300 );
		}
	});

    //sidr
	if ( jQuery.isFunction( jQuery.fn.sidr ) ) {
		jQuery('#mobile-header-left-menu').sidr({
		   name: 'mobile-header-left-nav',
		   side: 'left' // By default
		});
	}

});
