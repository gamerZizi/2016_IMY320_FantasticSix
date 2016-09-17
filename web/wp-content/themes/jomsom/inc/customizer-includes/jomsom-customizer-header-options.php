<?php
/**
 * The template for adding Additional Header Option in Customizer
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

// Header Options
$wp_customize->add_setting( 'jomsom_theme_options[enable_featured_header_image]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['enable_featured_header_image'],
	'sanitize_callback' => 'sanitize_key',
) );

$jomsom_enable_featured_header_image_options = jomsom_enable_featured_header_image_options();
$choices = array();
foreach ( $jomsom_enable_featured_header_image_options as $jomsom_enable_featured_header_image_option ) {
	$choices[$jomsom_enable_featured_header_image_option['value']] = $jomsom_enable_featured_header_image_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[enable_featured_header_image]', array(
		'choices'  	=> $choices,
		'label'		=> __( 'Enable Featured Header Image on ', 'jomsom' ),
		'priority'	=> '1',
		'section'   => 'header_image',
        'settings'  => 'jomsom_theme_options[enable_featured_header_image]',
        'type'	  	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_image_size]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_image_size'],
	'sanitize_callback' => 'sanitize_key',
) );

$jomsom_featured_image_size_options = jomsom_featured_image_size_options();
$choices = array();
foreach ( $jomsom_featured_image_size_options as $jomsom_featured_image_size_option ) {
	$choices[$jomsom_featured_image_size_option['value']] = $jomsom_featured_image_size_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_image_size]', array(
		'choices'  	=> $choices,
		'label'		=> __( 'Page/Post Featured Header Image Size', 'jomsom' ),
		'section'   => 'header_image',
		'settings'  => 'jomsom_theme_options[featured_image_size]',
		'type'	  	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_header_image_alt]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_header_image_alt'],
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_header_image_alt]', array(
		'label'		=> __( 'Featured Header Image Alt/Title Tag ', 'jomsom' ),
		'section'   => 'header_image',
        'settings'  => 'jomsom_theme_options[featured_header_image_alt]',
        'type'	  	=> 'text',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_header_image_url]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_header_image_url'],
	'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_header_image_url]', array(
		'label'		=> __( 'Featured Header Image Link URL', 'jomsom' ),
		'section'   => 'header_image',
        'settings'  => 'jomsom_theme_options[featured_header_image_url]',
        'type'	  	=> 'text',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_header_image_base]', array(
	'capability'		=> 'edit_theme_options',
	'default'	=> $defaults['featured_header_image_url'],
	'sanitize_callback' => 'jomsom_sanitize_checkbox',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_header_image_base]', array(
	'label'    	=> __( 'Check to Open Link in New Window/Tab', 'jomsom' ),
	'section'  	=> 'header_image',
	'settings' 	=> 'jomsom_theme_options[featured_header_image_base]',
	'type'     	=> 'checkbox',
) );
// Header Options End