<?php
/**
 * Custom Background
**/

/* === Custom Background === */
$custom_backgound_args = array(
	'default-color'          => '#c9651c',
	'default-image'          => get_template_directory_uri() . '/assets/images/background.png',
	'default-repeat'         => 'repeat-x',
	'default-position-x'     => 'left',
	'wp-head-callback'       => '_custom_background_cb',
);
add_theme_support( 'custom-background', $custom_backgound_args );

