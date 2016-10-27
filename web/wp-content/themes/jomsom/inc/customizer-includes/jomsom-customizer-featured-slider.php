<?php
/**
 * The template for adding Featured Slider Options in Customizer
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

// Featured Slider
$wp_customize->add_section( 'jomsom_featured_slider', array(
	'priority'		=> 500,
	'title'			=> __( 'Featured Slider', 'jomsom' ),
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_slider_option]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slider_option'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$featured_slider_content_options = jomsom_featured_slider_content_options();
$choices = array();
foreach ( $featured_slider_content_options as $featured_slider_content_option ) {
	$choices[$featured_slider_content_option['value']] = $featured_slider_content_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_slider_option]', array(
	'choices'   => $choices,
	'label'    	=> __( 'Enable Slider on', 'jomsom' ),
	'priority'	=> 2,
	'section'  	=> 'jomsom_featured_slider',
	'settings' 	=> 'jomsom_theme_options[featured_slider_option]',
	'type'    	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_slide_transition_effect]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slide_transition_effect'],
	'sanitize_callback'	=> 'jomsom_sanitize_select',
) );

$jomsom_featured_slide_transition_effects = jomsom_featured_slide_transition_effects();
$choices = array();
foreach ( $jomsom_featured_slide_transition_effects as $jomsom_featured_slide_transition_effect ) {
	$choices[$jomsom_featured_slide_transition_effect['value']] = $jomsom_featured_slide_transition_effect['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_slide_transition_effect]' , array(
	'active_callback'	=> 'jomsom_is_slider_active',
	'choices'  	=> $choices,
	'label'		=> __( 'Transition Effect', 'jomsom' ),
	'priority'	=> 3,
	'section'  	=> 'jomsom_featured_slider',
	'settings' 	=> 'jomsom_theme_options[featured_slide_transition_effect]',
	'type'	  	=> 'select',
	)
);

$wp_customize->add_setting( 'jomsom_theme_options[featured_slide_transition_delay]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slide_transition_delay'],
	'sanitize_callback'	=> 'jomsom_sanitize_number_range',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_slide_transition_delay]' , array(
	'active_callback'	=> 'jomsom_is_slider_active',
	'description'	=> __( 'seconds(s)', 'jomsom' ),
	'input_attrs' => array(
    	'style' => 'width: 40px;'
	),
	'label'    		=> __( 'Transition Delay', 'jomsom' ),
	'priority'		=> 3,
	'section'  		=> 'jomsom_featured_slider',
	'settings' 		=> 'jomsom_theme_options[featured_slide_transition_delay]',
	)
);

$wp_customize->add_setting( 'jomsom_theme_options[featured_slide_transition_length]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slide_transition_length'],
	'sanitize_callback'	=> 'jomsom_sanitize_number_range',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_slide_transition_length]' , array(
	'active_callback'	=> 'jomsom_is_slider_active',
	'description'	=> __( 'seconds(s)', 'jomsom' ),
	'input_attrs' => array(
    	'style' => 'width: 40px;'
	),
	'label'    		=> __( 'Transition Length', 'jomsom' ),
	'priority'		=> 4,
	'section'  		=> 'jomsom_featured_slider',
	'settings' 		=> 'jomsom_theme_options[featured_slide_transition_length]',
	)
);

$wp_customize->add_setting( 'jomsom_theme_options[featured_slider_image_loader]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slider_image_loader'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$featured_slider_image_loader_options = jomsom_featured_slider_image_loader();
$choices = array();
foreach ( $featured_slider_image_loader_options as $featured_slider_image_loader_option ) {
	$choices[$featured_slider_image_loader_option['value']] = $featured_slider_image_loader_option['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_slider_image_loader]', array(
	'active_callback'	=> 'jomsom_is_slider_active',
	'description'		=> __( 'True: Fixes the height overlap issue. Slideshow will start as soon as two slider are available. Slide may display in random, as image is fetch.<br>Wait: Fixes the height overlap issue.<br> Slideshow will start only after all images are available.', 'jomsom' ),
	'choices'   => $choices,
	'label'    	=> __( 'Image Loader', 'jomsom' ),
	'priority'	=> 5,
	'section'  	=> 'jomsom_featured_slider',
	'settings' 	=> 'jomsom_theme_options[featured_slider_image_loader]',
	'type'    	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_slider_type]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slider_type'],
	'sanitize_callback' => 'jomsom_sanitize_select',
) );

$featured_slider_types = jomsom_featured_slider_types();
$choices = array();
foreach ( $featured_slider_types as $featured_slider_type ) {
	$choices[$featured_slider_type['value']] = $featured_slider_type['label'];
}

$wp_customize->add_control( 'jomsom_theme_options[featured_slider_type]', array(
	'active_callback'	=> 'jomsom_is_slider_active',
	'choices'  	=> $choices,
	'label'    	=> __( 'Select Slider Type', 'jomsom' ),
	'priority'	=> 6,
	'section'  	=> 'jomsom_featured_slider',
	'settings' 	=> 'jomsom_theme_options[featured_slider_type]',
	'type'	  	=> 'select',
) );

$wp_customize->add_setting( 'jomsom_theme_options[featured_slide_number]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['featured_slide_number'],
	'sanitize_callback'	=> 'jomsom_sanitize_number_range',
) );

$wp_customize->add_control( 'jomsom_theme_options[featured_slide_number]' , array(
	'active_callback'	=> 'jomsom_is_demo_slider_inactive',
	'description'	=> __( 'Save and refresh the page if No. of Slides is changed (Max no of slides is 20)', 'jomsom' ),
	'input_attrs' 	=> array(
        'style' => 'width: 45px;',
        'min'   => 0,
        'max'   => 20,
        'step'  => 1,
    	),
	'label'    		=> __( 'No of Slides', 'jomsom' ),
	'priority'		=> 7,
	'section'  		=> 'jomsom_featured_slider',
	'settings' 		=> 'jomsom_theme_options[featured_slide_number]',
	'type'	   		=> 'number',
	)
);

//loop for featured post sliders
for ( $i=1; $i <=  $options['featured_slide_number'] ; $i++ ) {
	$wp_customize->add_setting( 'jomsom_theme_options[featured_slider_page_'. $i .']', array(
		'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'jomsom_sanitize_page',
	) );

	$wp_customize->add_control( 'jomsom_theme_options[featured_slider_page_'. $i .']', array(
		'active_callback'	=> 'jomsom_is_page_slider_active',
		'label'    	=> __( 'Featured Page', 'jomsom' ) . ' # ' . $i ,
		'priority'	=> '9' . $i,
		'section'  	=> 'jomsom_featured_slider',
		'settings' 	=> 'jomsom_theme_options[featured_slider_page_'. $i .']',
		'type'	   	=> 'dropdown-pages',
	) );
}
// Featured Slider End