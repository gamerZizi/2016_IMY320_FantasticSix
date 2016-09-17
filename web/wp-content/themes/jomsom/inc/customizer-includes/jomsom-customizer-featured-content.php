<?php
/**
 * The template for adding Featured Content Settings in Customizer
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

// Featured Content Options
$wp_customize->add_section( 'jomsom_featured_content', array(
	'priority'		=> 400,
	'title'			=> __( 'Featured Content', 'jomsom' ),
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_option]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_option'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$jomsom_featured_slider_content_options = jomsom_featured_slider_content_options();
$choices = array();
foreach ( $jomsom_featured_slider_content_options as $jomsom_featured_slider_content_option ) {
	$choices[$jomsom_featured_slider_content_option['value']] = $jomsom_featured_slider_content_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_content_option]', array(
	'choices'  	=> $choices,
	'label'    	=> __( 'Enable Featured Content on', 'jomsom' ),
	'priority'	=> '1',
	'section'  	=> 'jomsom_featured_content',
	'settings' 	=> 'jomsom_theme_options[featured_content_option]',
	'type'	  	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_layout]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_layout'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$jomsom_featured_content_layout_options = jomsom_featured_content_layout_options();
$choices = array();
foreach ( $jomsom_featured_content_layout_options as $jomsom_featured_content_layout_option ) {
	$choices[$jomsom_featured_content_layout_option['value']] = $jomsom_featured_content_layout_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_content_layout]', array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'choices'  	=> $choices,
	'label'    	=> __( 'Select Featured Content Layout', 'jomsom' ),
	'priority'	=> '2',
	'section'  	=> 'jomsom_featured_content',
	'settings' 	=> 'jomsom_theme_options[featured_content_layout]',
	'type'	  	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_position]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_position'],
	'sanitize_callback' => 'jomsom_sanitize_checkbox'
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_content_position]', array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'label'		=> __( 'Check to Move above Footer', 'jomsom' ),
	'priority'	=> '3',
	'section'  	=> 'jomsom_featured_content',
	'settings'	=> 'jomsom_theme_options[featured_content_position]',
	'type'		=> 'checkbox',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_slider]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_slider'],
	'sanitize_callback' => 'jomsom_sanitize_checkbox'
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_content_slider]', array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'label'		=> __( 'Check to Enable Slider', 'jomsom' ),
	'priority'	=> '4',
	'section'  	=> 'jomsom_featured_content',
	'settings'	=> 'jomsom_theme_options[featured_content_slider]',
	'type'		=> 'checkbox',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_image_loader]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_image_loader'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$featured_slider_image_loader_options = jomsom_featured_slider_image_loader();
$choices = array();
foreach ( $featured_slider_image_loader_options as $featured_slider_image_loader_option ) {
	$choices[$featured_slider_image_loader_option['value']] = $featured_slider_image_loader_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_content_image_loader]', array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'description'		=> __( 'True: Fixes the height overlap issue. Slideshow will start as soon as two slider are available. Slide may display in random, as image is fetch.<br>Wait: Fixes the height overlap issue.<br> Slideshow will start only after all images are available.', 'jomsom' ),
	'choices'   => $choices,
	'label'    	=> __( 'Image Loader', 'jomsom' ),
	'priority'	=> '4',
	'section'  	=> 'jomsom_featured_content',
	'settings' 	=> 'jomsom_theme_options[featured_content_image_loader]',
	'type'    	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_type]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_type'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$jomsom_featured_content_types = jomsom_featured_content_types();
$choices = array();
foreach ( $jomsom_featured_content_types as $jomsom_featured_content_type ) {
	$choices[$jomsom_featured_content_type['value']] = $jomsom_featured_content_type['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_content_type]', array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'choices'  	=> $choices,
	'label'    	=> __( 'Select Content Type', 'jomsom' ),
	'priority'	=> '3',
	'section'  	=> 'jomsom_featured_content',
	'settings' 	=> 'jomsom_theme_options[featured_content_type]',
	'type'	  	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_headline]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_headline'],
	'sanitize_callback'	=> 'wp_kses_post',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_content_headline]' , array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'description'	=> __( 'Leave field empty if you want to remove Headline', 'jomsom' ),
	'label'    		=> __( 'Headline for Featured Content', 'jomsom' ),
	'priority'		=> '4',
	'section'  		=> 'jomsom_featured_content',
	'settings' 		=> 'jomsom_theme_options[featured_content_headline]',
	'type'	   		=> 'text',
	)
);

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_subheadline]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_subheadline'],
	'sanitize_callback'	=> 'wp_kses_post',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_content_subheadline]' , array(
	'active_callback'	=> 'jomsom_is_featured_content_active',
	'description'	=> __( 'Leave field empty if you want to remove Sub-headline', 'jomsom' ),
	'label'    		=> __( 'Sub-headline for Featured Content', 'jomsom' ),
	'priority'		=> '5',
	'section'  		=> 'jomsom_featured_content',
	'settings' 		=> 'jomsom_theme_options[featured_content_subheadline]',
	'type'	   		=> 'text',
	)
);

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_number]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_number'],
	'sanitize_callback'	=> 'jomsom_sanitize_number_range',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_content_number]' , array(
	'active_callback'	=> 'jomsom_is_demo_featured_content_inactive',
	'description'	=> __( 'Save and refresh the page if No. of Featured Content is changed (Max no of Featured Content is 20)', 'jomsom' ),
	'input_attrs' 	=> array(
        'style' => 'width: 45px;',
        'min'   => 0,
        'max'   => 20,
        'step'  => 1,
    	),
	'label'    		=> __( 'No of Featured Content', 'jomsom' ),
	'priority'		=> '6',
	'section'  		=> 'jomsom_featured_content',
	'settings' 		=> 'jomsom_theme_options[featured_content_number]',
	'type'	   		=> 'number',
	)
);

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_enable_title]', array(
        'default'			=> $defaults['featured_content_enable_title'],
		'sanitize_callback'	=> 'jomsom_sanitize_checkbox',
	) );

$wp_customize->add_control(  'jomsom_theme_options[featured_content_enable_title]', array(
	'active_callback'	=> 'jomsom_is_demo_featured_content_inactive',
	'label'		=> __( 'Check to Enable Title', 'jomsom' ),
    'priority'	=> '7',
	'section'   => 'jomsom_featured_content',
    'settings'  => 'jomsom_theme_options[featured_content_enable_title]',
	'type'		=> 'checkbox',
) );


$wp_customize->add_setting( 'jomsom_theme_options[featured_content_enable_date]', array(
        'default'			=> $defaults['featured_content_enable_date'],
		'sanitize_callback'	=> 'jomsom_sanitize_checkbox',
	) );

$wp_customize->add_control(  'jomsom_theme_options[featured_content_enable_date]', array(
	'active_callback'	=> 'jomsom_is_demo_featured_content_inactive',
	'label'		=> __( 'Check to Enable Date', 'jomsom' ),
    'priority'	=> '7.5',
	'section'   => 'jomsom_featured_content',
    'settings'  => 'jomsom_theme_options[featured_content_enable_date]',
	'type'		=> 'checkbox',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_content_enable_excerpt_content]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_content_enable_excerpt_content'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$jomsom_featured_content_show = jomsom_featured_content_show();
$choices = array();
foreach ( $jomsom_featured_content_show as $jomsom_featured_content_shows ) {
	$choices[$jomsom_featured_content_shows['value']] = $jomsom_featured_content_shows['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_content_enable_excerpt_content]', array(
	'active_callback' => 'jomsom_is_featured_page_content_active',
	'choices'         => $choices,
	'label'           => __( 'Display Content', 'jomsom' ),
	'priority'        => '8',
	'section'         => 'jomsom_featured_content',
	'settings'        => 'jomsom_theme_options[featured_content_enable_excerpt_content]',
	'type'            => 'select',
) );

$priority	=	7;

for ( $i=1; $i <=  $options['featured_content_number'] ; $i++ ) {
	$wp_customize->add_setting( 'jomsom_theme_options[featured_content_page_'. $i .']', array(
		'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'jomsom_sanitize_page',
	) );

	$wp_customize->add_control( 'jomsom_featured_content_page_'. $i .'', array(
		'active_callback'	=> 'jomsom_is_featured_page_content_active',
		'label'    	=> __( 'Featured Page', 'jomsom' ) . ' ' . $i ,
		'priority'	=> '5' . $i,
		'section'  	=> 'jomsom_featured_content',
		'settings' 	=> 'jomsom_theme_options[featured_content_page_'. $i .']',
		'type'	   	=> 'dropdown-pages',
	) );
}
// Featured Content Setting End