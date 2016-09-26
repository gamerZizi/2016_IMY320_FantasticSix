<?php
/**
 * The main template for implementing Theme/Customzer Options
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

/**
 * Implements jomsom theme options into Theme Customizer.
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Jomsom 0.1
 */
function jomsom_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport			= 'postMessage';

	$wp_customize->get_setting( 'blogdescription' )->transport	= 'postMessage';

	$options  = jomsom_get_theme_options();

	$defaults = jomsom_get_default_theme_options();

	//Custom Controls
	require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-custom-controls.php';

	// Custom Logo (added to Site Title and Tagline section in Theme Customizer)
	$wp_customize->add_setting( 'jomsom_theme_options[hide_site_title]', array(
		'capability'		=> 'edit_theme_options',
		'default'			=> $defaults['hide_site_title'],
		'sanitize_callback' => 'jomsom_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'jomsom_theme_options[hide_site_title]', array(
		'active_callback' => 'jomsom_is_header_text_displayed',
		'label'           => __( 'Check to hide Site Title only', 'jomsom' ),
		'priority'        => 50, // Set it below Display Header Text
		'section'         => 'title_tagline',
		'settings'        => 'jomsom_theme_options[hide_site_title]',
		'type'            => 'checkbox',
	) );

	//@remove if block when WordPress 4.8 is released
	if ( !function_exists( 'has_custom_logo' ) ) {
		$wp_customize->add_setting( 'jomsom_theme_options[logo_message]', array(
			'capability'		=> 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control(
			new Jomsom_Note_Control(
				$wp_customize,
				'logo_message',
				array(
					'label'    => __( 'Site logo', 'jomsom' ),
					'priority' => 100,
					'section'  => 'title_tagline',
					'settings' => 'jomsom_theme_options[logo_message]',
			        'type'     	=> 'description',
				)
			)
		);

		$wp_customize->add_setting( 'jomsom_theme_options[logo_enable]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $defaults['logo_enable'],
			'sanitize_callback' => 'jomsom_sanitize_checkbox',
		) );

		$wp_customize->add_control( 'jomsom_theme_options[logo_enable]', array(
			'label'    => __( 'Check to enable logo', 'jomsom' ),
			'priority' => 100,
			'section'  => 'title_tagline',
			'settings' => 'jomsom_theme_options[logo_enable]',
			'type'     => 'checkbox',
		) );

		$wp_customize->add_setting( 'jomsom_theme_options[logo]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $defaults['logo'],
			'sanitize_callback'	=> 'jomsom_sanitize_image'
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo', array(
			'active_callback' => 'jomsom_is_logo_available',
			'label'           => __( 'Logo', 'jomsom' ),
			'priority'        => 101,
			'section'         => 'title_tagline',
			'settings'        => 'jomsom_theme_options[logo]',
	    ) ) );

		$wp_customize->add_setting( 'jomsom_theme_options[logo_alt_text]', array(
			'capability'		=> 'edit_theme_options',
			'default'			=> $defaults['logo_alt_text'],
			'sanitize_callback'	=> 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'jomsom_logo_alt_text', array(
			'active_callback' => 'jomsom_is_logo_available',
			'label'           => __( 'Logo Alt Text', 'jomsom' ),
			'priority'        => 102,
			'section'         => 'title_tagline',
			'settings'        => 'jomsom_theme_options[logo_alt_text]',
			'type'            => 'text',
		) );
	}

	$wp_customize->add_setting( 'jomsom_theme_options[move_title_tagline]', array(
		'capability'		=> 'edit_theme_options',
		'default'			=> $defaults['move_title_tagline'],
		'sanitize_callback' => 'jomsom_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'jomsom_theme_options[move_title_tagline]', array(
		'active_callback' => 'jomsom_is_logo_available',
		'label'           => __( 'Check to move Site Title and Tagline before logo', 'jomsom' ),
		'priority'        => 103,
		'section'         => 'title_tagline',
		'settings'        => 'jomsom_theme_options[move_title_tagline]',
		'type'            => 'checkbox',
	) );
	// Custom Logo End

	// Header Options (added to Header section in Theme Customizer)
	require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-header-options.php';


   	//Additional Menu Options
	$wp_customize->add_section( 'jomsom_menu_options', array(
		'description'	=> __( 'Extra Menu Options specific to this theme', 'jomsom' ),
		'priority' 		=> 105,
		'title'    		=> __( 'Menu Options', 'jomsom' ),
	) );

	$wp_customize->add_setting( 'jomsom_theme_options[fix_primary_menu]', array(
		'capability'		=> 'edit_theme_options',
		'default'			=> $defaults['fix_primary_menu'],
		'sanitize_callback' => 'jomsom_sanitize_checkbox'
	) );

	$wp_customize->add_control( 'jomsom_theme_options[fix_primary_menu]', array(
		'label'    => __( 'Check to enable fixed primary menu', 'jomsom' ),
		'section'  => 'jomsom_menu_options',
		'settings' => 'jomsom_theme_options[fix_primary_menu]',
		'type'     => 'checkbox',
	) );

	//Theme Options
	require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-theme-options.php';

	// Color Options
	//Tagline Color Option
	$wp_customize->add_setting( 'jomsom_theme_options[tagline_color]', array(
		'capability'		=> 'edit_theme_options',
		'default'			=> $defaults['tagline_color'],
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'jomsom_theme_options[tagline_color]', array(
		'label'		=> __( 'Tagline Color', 'jomsom' ),
		'section'	=> 'colors',
		'settings'	=> 'jomsom_theme_options[tagline_color]',
	) ) );

	//Featured Content
	require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-featured-content.php';

	//Featured Slider
	require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-featured-slider.php';

	//Social Links
	require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-social-icons.php';

	// Reset all settings to default
	$wp_customize->add_section( 'jomsom_reset_all_settings', array(
		'description'	=> __( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'jomsom' ),
		'priority' 		=> 700,
		'title'    		=> __( 'Reset all settings', 'jomsom' ),
	) );

	$wp_customize->add_setting( 'jomsom_theme_options[reset_all_settings]', array(
		'capability'		=> 'edit_theme_options',
		'default'			=> $defaults['reset_all_settings'],
		'sanitize_callback' => 'jomsom_reset_all_settings',
		'transport'			=> 'postMessage',
	) );

	$wp_customize->add_control( 'jomsom_theme_options[reset_all_settings]', array(
		'label'    => __( 'Check to reset all settings to default', 'jomsom' ),
		'section'  => 'jomsom_reset_all_settings',
		'settings' => 'jomsom_theme_options[reset_all_settings]',
		'type'     => 'checkbox',
	) );
	// Reset all settings to default end


	//Important Links
		$wp_customize->add_section( 'important_links', array(
			'priority' 		=> 999,
			'title'   	 	=> __( 'Important Links', 'jomsom' ),
		) );

		/**
		 * Has dummy Sanitizaition function as it contains no value to be sanitized
		 */
		$wp_customize->add_setting( 'important_links', array(
			'sanitize_callback'	=> 'jomsom_sanitize_important_link',
		) );

		$wp_customize->add_control( new jomsom_Important_Links( $wp_customize, 'important_links', array(
	        'label'   	=> __( 'Important Links', 'jomsom' ),
	         'section'  	=> 'important_links',
	        'settings' 	=> 'important_links',
	        'type'     	=> 'important_links',
	    ) ) );
	    //Important Links End
}
add_action( 'customize_register', 'jomsom_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously for jomsom.
 * And flushes out all transient data on preview
 *
 * @since Jomsom 0.1
 */
function jomsom_customize_preview() {
	wp_enqueue_script( 'jomsom_customizer', get_template_directory_uri() . '/js/jomsom-customizer.min.js', array( 'customize-preview' ), '20120827', true );

	//Flush transients
	jomsom_flush_transients();
}
add_action( 'customize_preview_init', 'jomsom_customize_preview' );

/**
 * Custom scripts and styles on customize.php for jomsom.
 *
 * @since Jomsom 0.7
 */
function jomsom_customize_scripts() {
	wp_enqueue_script( 'jomsom_customizer_custom', get_template_directory_uri() . '/js/jomsom-customizer-custom-scripts.min.js', array(), '20160520', true );

	$jomsom_misc_links = array(
		'upgrade_link' 				=> esc_url( 'http://catchthemes.com/themes/jomsom-pro/' ),
		'upgrade_text'	 			=> __( 'Upgrade To Pro &raquo;', 'jomsom' )
	);

	//Add Upgrade Button via localized script
	wp_localize_script( 'jomsom_customizer_custom', 'jomsom_misc_links', $jomsom_misc_links );

	wp_enqueue_style( 'jomsom_customizer_custom_css', get_template_directory_uri() . '/css/jomsom-customizer.css');
}
add_action( 'customize_controls_enqueue_scripts', 'jomsom_customize_scripts');

//Active callbacks for customizer
require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-active-callbacks.php';


//Sanitize functions for customizer
require get_template_directory() . '/inc/customizer-includes/jomsom-customizer-sanitize-functions.php';