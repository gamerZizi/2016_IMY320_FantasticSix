( function( $ ) {

	/* Link Color */
	wp.customize( 'color_link', function( value ) {
		value.bind( function( to ) {
			var color_link_css = '';
			color_link_css += 'a,a:hover,a:focus{color:' +  to + '}';
			color_link_css += 'input[type="submit"]:hover,input[type="submit"]:focus,input[type="button"]:hover,input[type="button"]:focus,input[type="reset"]:hover,input[type="reset"]:focus,button:hover,button:focus,.button:hover,.button:focus{ border-color:' +  to + ';background:' + to + '}';
			color_link_css += '.archive-title:before{color:' +  to + '}';
			color_link_css += '.entry-title a:hover,.entry-title a:focus{color:' +  to + '}';
			color_link_css += '.more-link{color:' +  to + '}';
			color_link_css += '.more-link:hover,.more-link:focus{border-color:' +  to + '}';
			color_link_css += '.navigation.pagination a.page-numbers:hover,.navigation.pagination a.page-numbers:focus{border-color:' +  to + ';background:' + to + '}';
			color_link_css += '.widget_recent_entries a:hover,.widget_recent_entries a:focus{color:' +  to + '}';
			color_link_css += '.widget_rss li a.rsswidget:hover,.widget_rss li a.rsswidget:focus{color:' +  to + '}';

			$( '#nevertheless-link-color-css' ).html( color_link_css );
		} );
	} );

	/* Header BG Color */
	wp.customize( 'color_header_bg', function( value ) {
		value.bind( function( to ) {
			var header_bg_css = '';
			header_bg_css += '#header{ background-color:' +  to + '}';
			$( '#nevertheless-header-bg-color-css' ).html( header_bg_css );
		} );
	} );

	/* Site Title Color */
	wp.customize( 'color_site_title', function( value ) {
		value.bind( function( to ) {
			var site_title_css = '';
			site_title_css += '#site-title a,#site-title a:hover,#site-title a:focus{ color:' +  to + '}';
			$( '#nevertheless-site-title-color-css' ).html( site_title_css );
		} );
	} );

	/* Site Description Color */
	wp.customize( 'color_site_description', function( value ) {
		value.bind( function( to ) {
			var site_desc_css = '';
			site_desc_css += '#site-description{ color:' +  to + '}';
			$( '#nevertheless-site-description-color-css' ).html( site_desc_css );
		} );
	} );

	/* Navigation BG Color */
	wp.customize( 'color_nav_bg', function( value ) {
		value.bind( function( to ) {
			var nav_bg_css = '';
			nav_bg_css += '#menu-primary .menu-container{ background-color:' +  to + '}';
			nav_bg_css += '#menu-primary-items > li > a{ background-color:' +  to + '}';
			$( '#nevertheless-nav-bg-color-css' ).html( nav_bg_css );
		} );
	} );

	/* Navigation Text Color */
	wp.customize( 'color_nav', function( value ) {
		value.bind( function( to ) {
			var nav_css = '';
			nav_css += '#menu-primary-items > li > a{ color:' +  to + '}';
			nav_css += '#menu-primary .menu-toggle a{ color:' +  to + '}';
			nav_css += '#menu-primary .menu-search .search-toggle{ color:' +  to + '}';
			nav_css += '#menu-primary .search-toggle-active.menu-search button{ color:' +  to + '}';
			$( '#nevertheless-nav-color-css' ).html( nav_css );
		} );
	} );


} )( jQuery );
