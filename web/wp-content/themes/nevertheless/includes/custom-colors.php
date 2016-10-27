<?php
/**
 * Color Options
 * @since 1.0.0
**/

/* Color Options */
add_action( 'customize_register', 'nevertheless_color_customizer' );

/**
 * Register Customizer Setting
 * @since 1.0.0
 */
function nevertheless_color_customizer( $wp_customize ){

	/* === LINK COLOR === */

	/* Color setting */
	$wp_customize->add_setting( 'color_link', array(
		'default'             => '#ea7521',
		'type'                => 'theme_mod',
		'transport'           => 'postMessage',
		'capability'          => 'edit_theme_options',
		'sanitize_callback'   => 'tamatebako_sanitize_hex_color',
	));

	/* Add in color section */
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_link',
			array(
				'label'               => _x( 'Link Color', 'customizer', 'nevertheless' ),
				'section'             => 'colors',
				'settings'            => 'color_link',
				'priority'            => 10,
			)
		)
	);

	/* === HEADER BG COLOR === */

	/* Color setting */
	$wp_customize->add_setting( 'color_header_bg', array(
		'default'             => '#ea7521',
		'type'                => 'theme_mod',
		'transport'           => 'postMessage',
		'capability'          => 'edit_theme_options',
		'sanitize_callback'   => 'tamatebako_sanitize_hex_color',
	));

	/* Add in color section */
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_header_bg',
			array(
				'label'               => _x( 'Header Background Color', 'customizer', 'nevertheless' ),
				'section'             => 'colors',
				'settings'            => 'color_header_bg',
				'priority'            => 10,
			)
		)
	);

	/* === SITE TITLE COLOR === */

	/* Color setting */
	$wp_customize->add_setting( 'color_site_title', array(
		'default'             => '#ffffff',
		'type'                => 'theme_mod',
		'transport'           => 'postMessage',
		'capability'          => 'edit_theme_options',
		'sanitize_callback'   => 'tamatebako_sanitize_hex_color',
	));

	/* Add in color section */
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_site_title',
			array(
				'label'               => _x( 'Site Title Color', 'customizer', 'nevertheless' ),
				'section'             => 'colors',
				'settings'            => 'color_site_title',
				'priority'            => 10,
			)
		)
	);

	/* === TAGLINE COLOR === */

	/* Color setting */
	$wp_customize->add_setting( 'color_site_description', array(
		'default'             => '#444444',
		'type'                => 'theme_mod',
		'transport'           => 'postMessage',
		'capability'          => 'edit_theme_options',
		'sanitize_callback'   => 'tamatebako_sanitize_hex_color',
	));

	/* Add in color section */
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_site_description',
			array(
				'label'               => _x( 'Tagline Color', 'customizer', 'nevertheless' ),
				'section'             => 'colors',
				'settings'            => 'color_site_description',
				'priority'            => 10,
			)
		)
	);

	/* === NAVIGATION BG COLOR === */

	/* Color setting */
	$wp_customize->add_setting( 'color_nav_bg', array(
		'default'             => '#ea7521',
		'type'                => 'theme_mod',
		'transport'           => 'postMessage',
		'capability'          => 'edit_theme_options',
		'sanitize_callback'   => 'tamatebako_sanitize_hex_color',
	));

	/* Add in color section */
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_nav_bg',
			array(
				'label'               => _x( 'Navigation Background Color', 'customizer', 'nevertheless' ),
				'section'             => 'colors',
				'settings'            => 'color_nav_bg',
				'priority'            => 10,
			)
		)
	);

	/* === NAVIGATION COLOR === */

	/* Color setting */
	$wp_customize->add_setting( 'color_nav', array(
		'default'             => '#ffffff',
		'type'                => 'theme_mod',
		'transport'           => 'postMessage',
		'capability'          => 'edit_theme_options',
		'sanitize_callback'   => 'tamatebako_sanitize_hex_color',
	));

	/* Add in color section */
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'color_nav',
			array(
				'label'               => _x( 'Navigation Text Color', 'customizer', 'nevertheless' ),
				'section'             => 'colors',
				'settings'            => 'color_nav',
				'priority'            => 10,
			)
		)
	);

}

/* Customizer Preview JS */
add_action( 'customize_preview_init', 'nevertheless_customize_preview_js' );

/**
 * Preview JS
 * @since 1.0.0
 */
function nevertheless_customize_preview_js(){

	/* Color */
	wp_enqueue_script( 'nevertheless-customizer-color', trailingslashit( get_template_directory_uri() ) . 'assets/js/customizer-color.js', array( 'jquery', 'customize-preview' ), tamatebako_theme_version(), true );
}

/* Print CSS to wp_head */
add_action( 'wp_head', 'nevertheless_color_print_css' );

/**
 * Print BG Color CSS
 */
function nevertheless_color_print_css(){

	/* Var */
	$css = '';

	$color_link = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_link', 'ea7521' ) );
	$color_header_bg = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_header_bg', 'ea7521' ) );
	$color_site_title = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_site_title', 'ffffff' ) );
	$color_site_description = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_site_description', '444444' ) );
	$color_nav_bg = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_nav_bg', 'ea7521' ) );
	$color_nav = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_nav', 'ffffff' ) );


	/* Link Color */
	if ( 'ea7521' != $color_link ){
		$css .= "a,a:hover,a:focus{color:#{$color_link}}";
		$css .= 'input[type="submit"]:hover,input[type="submit"]:focus,input[type="button"]:hover,input[type="button"]:focus,input[type="reset"]:hover,input[type="reset"]:focus,button:hover,button:focus,.button:hover,.button:focus{ border-color: #' . $color_link . '; background: #' . $color_link . '; }';
		$css .= ".archive-title:before{color:#{$color_link}}";
		$css .= ".entry-title a:hover,.entry-title a:focus{color:#{$color_link}}";
		$css .= ".more-link{color:#{$color_link}}";
		$css .= ".more-link:hover,.more-link:focus{border-color:#{$color_link}}";
		$css .= ".navigation.pagination a.page-numbers:hover,.navigation.pagination a.page-numbers:focus{border-color:#{$color_link};background:#{$color_link}}";
		$css .= ".widget_recent_entries a:hover,.widget_recent_entries a:focus{color:#{$color_link}}";
		$css .= ".widget_rss li a.rsswidget:hover,.widget_rss li a.rsswidget:focus{color:#{$color_link}}";
	}

	/* Header BG Color */
	if ( 'ea7521' != $color_header_bg ){
		$css .= "#header{ background-color: #{$color_header_bg}; }";
	}

	/* Site Title Color */
	if ( 'ffffff' != $color_site_title ){
		$css .= "#site-title a,#site-title a:hover,#site-title a:focus{ color: #{$color_site_title}; }";
	}

	/* Site Description Color */
	if ( '444444' != $color_site_description ){
		$css .= "#site-description{ color: #{$color_site_description}; }";
	}

	/* Nav BG Color */
	if ( 'ea7521' != $color_nav_bg ){
		$css .= "#menu-primary .menu-container{ background-color: #{$color_nav_bg}; }";
		$css .= "#menu-primary-items > li > a{ background-color: #{$color_nav_bg}; }";
	}

	/* Nav Text Color */
	if ( 'ffffff' != $color_nav ){
		$css .= "#menu-primary-items > li > a{ color: #{$color_nav}; }";
		$css .= "#menu-primary .menu-toggle a{ color: #{$color_nav}; }";
		$css .= "#menu-primary .menu-search .search-toggle{ color: #{$color_nav}; }";
		$css .= "#menu-primary .search-toggle-active.menu-search button{ color: #{$color_nav}; }";
	}

	/* Print it. */
	if ( !empty( $css ) ){
		echo "\n" . '<style type="text/css" id="nevertheless-color-css">' . trim( wp_strip_all_tags( $css ) ) . '</style>' . "\n";
	}

	/* Customizer Only (special placeholder for link color). */
	global $wp_customize;
	if ( isset( $wp_customize ) ){
		echo "\n" . '<style type="text/css" id="nevertheless-link-color-css"></style>' . "\n";
		echo "\n" . '<style type="text/css" id="nevertheless-header-bg-color-css"></style>' . "\n";
		echo "\n" . '<style type="text/css" id="nevertheless-site-title-color-css"></style>' . "\n";
		echo "\n" . '<style type="text/css" id="nevertheless-site-description-color-css"></style>' . "\n";
		echo "\n" . '<style type="text/css" id="nevertheless-nav-bg-color-css"></style>' . "\n";
		echo "\n" . '<style type="text/css" id="nevertheless-nav-color-css"></style>' . "\n";
	}
}

/* === EDITOR STYLE === */

/* Add Editor Style */
add_filter( 'mce_css', 'nevertheless_color_mce_css' );

/**
 * Add Link Color Option in Editor Style (MCE CSS)
 * @since 1.1.0
 */
function nevertheless_color_mce_css( $mce_css ){
	$mce_css .= ', ' . add_query_arg( array( 'action' => 'nevertheless_color_mce_css', '_nonce' => wp_create_nonce( 'nevertheless-color-mce-nonce', __FILE__ ) ), admin_url( 'admin-ajax.php' ) );
	return $mce_css;
}

/* Ajax: editor style CSS */
add_action( 'wp_ajax_nevertheless_color_mce_css', 'nevertheless_color_mce_css_ajax_callback' );
add_action( 'wp_ajax_no_priv_nevertheless_color_mce_css', 'nevertheless_color_mce_css_ajax_callback' );

/**
 * Ajax Callback
 */
function nevertheless_color_mce_css_ajax_callback(){

	/* Check nonce */
	$nonce = isset( $_REQUEST['_nonce'] ) ? $_REQUEST['_nonce'] : '';
	if( ! wp_verify_nonce( $nonce, 'nevertheless-color-mce-nonce' ) ){
		die();
	}

	/* Get Link Color */
	$color_link = tamatebako_sanitize_hex_color_no_hash( get_theme_mod( 'color_link', 'ea7521' ) );

	/* Set File Type */
	header( 'Content-type: text/css' );
	echo wp_strip_all_tags( "a,a:hover,a:focus{color:#{$color_link}}" );
	die();
}