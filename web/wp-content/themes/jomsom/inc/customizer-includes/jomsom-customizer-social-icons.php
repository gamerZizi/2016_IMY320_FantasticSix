<?php
/**
 * The template for Social Links in Customizer
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

// Social Icons
$wp_customize->add_panel( 'jomsom_social_links', array(
    'capability'     => 'edit_theme_options',
    'description'	=> __( 'Note: Enter the url for correponding social networking website', 'jomsom' ),
    'priority'       => 700,
	'title'    		 => __( 'Social Links', 'jomsom' ),
) );

$wp_customize->add_section( 'jomsom_social_links', array(
	'panel'			=> 'jomsom_social_links',
	'priority' 		=> 1,
	'title'   	 	=> __( 'Social Links', 'jomsom' ),
) );

$jomsom_social_icons 	=	jomsom_get_social_icons_list();

foreach ( $jomsom_social_icons as $key => $value ){
	if( 'skype_link' == $key ){
		$wp_customize->add_setting( 'jomsom_theme_options['. $key .']', array(
				'capability'		=> 'edit_theme_options',
				'sanitize_callback' => 'esc_attr',
			) );

		$wp_customize->add_control( 'jomsom_theme_options['. $key .']', array(
			'description'	=> __( 'Skype link can be of formats:<br>callto://+{number}<br> skype:{username}?{action}. More Information in readme file', 'jomsom' ),
			'label'    		=> $value['label'],
			'section'  		=> 'jomsom_social_links',
			'settings' 		=> 'jomsom_theme_options['. $key .']',
			'type'	   		=> 'url',
		) );
	}
	else {
		if( 'email_link' == $key ){
			$wp_customize->add_setting( 'jomsom_theme_options['. $key .']', array(
					'capability'		=> 'edit_theme_options',
					'sanitize_callback' => 'sanitize_email',
				) );
		}
		else if( 'handset_link' == $key || 'phone_link' == $key ){
			$wp_customize->add_setting( 'jomsom_theme_options['. $key .']', array(
					'capability'		=> 'edit_theme_options',
					'sanitize_callback' => 'sanitize_text_field',
				) );
		}
		else {
			$wp_customize->add_setting( 'jomsom_theme_options['. $key .']', array(
					'capability'		=> 'edit_theme_options',
					'sanitize_callback' => 'esc_url_raw',
				) );
		}

		$wp_customize->add_control( 'jomsom_theme_options['. $key .']', array(
			'label'    => $value['label'],
			'section'  => 'jomsom_social_links',
			'settings' => 'jomsom_theme_options['. $key .']',
			'type'	   => 'url',
		) );
	}
}

$wp_customize->add_section( 'jomsom_additional_options', array(
	'description'	=> __( 'Social Icons Additional Options', 'jomsom' ),
	'panel'			=> 'jomsom_social_links',
	'priority' 		=> 2,
	'title'   	 	=> __( 'Additional Options', 'jomsom' ),
) );

$wp_customize->add_setting( 'jomsom_theme_options[disable_social_footer]', array(
	'capability'		=> 'edit_theme_options',
	'default' 			=> $defaults['disable_social_footer'],
	'sanitize_callback'	=> 'jomsom_sanitize_number_range',
	)
);

$wp_customize->add_control( 'jomsom_theme_options[disable_social_footer]', array(
	'label'    	=> __( 'Check to disable social icons on footer', 'jomsom' ),
	'section' 	=> 'jomsom_additional_options',
	'settings'	=> 'jomsom_theme_options[disable_social_footer]',
	'type'		=> 'checkbox'
	)
);
// Social Icons End