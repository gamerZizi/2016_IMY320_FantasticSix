<?php
/**
 * Custom Header Image
**/

/* Registers default headers for the theme. */
register_default_headers(
	array(
		'default' => array(
			'url'           => '%s/assets/images/header.png',
			'thumbnail_url' => '%s/assets/images/header-thumbnail.jpg',
			'description'   => __( 'Default', 'nevertheless' )
		),
	)
);

/* === Custom Header Image === */
$custom_header_args = array(
	'default-image'          => '%s/assets/images/header.png',
	'random-default'         => false,
	'width'                  => 940,
	'height'                 => 130,
	'flex-height'            => true,
	'flex-width'             => true,
	'default-text-color'     => false,
	'header-text'            => false, /* no option */
	'uploads'                => true,
	'wp-head-callback'       => '__return_false',
);
add_theme_support( 'custom-header', $custom_header_args );
