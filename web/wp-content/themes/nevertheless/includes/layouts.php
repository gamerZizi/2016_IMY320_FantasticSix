<?php
/**
 * Layouts Setup
**/
$image_dir = get_template_directory_uri() . '/assets/images/layouts/';
$layouts = array(
	/* One Column */
	'content' => array(
		'name'          => _x( 'Full Width', 'layout name', 'nevertheless' ),
		'thumbnail'     => $image_dir . 'content.png',
	),
	/* Two Columns */
	'content-sidebar1'  => array(
		'name'          => _x( 'Right Sidebar', 'layout name', 'nevertheless' ),
		'thumbnail'     => $image_dir . 'content-sidebar1.png',
	),
	'sidebar1-content'  => array(
		'name'          => _x( 'Left Sidebar', 'layout name', 'nevertheless' ),
		'thumbnail'     => $image_dir . 'sidebar1-content.png',
	),
);
$layouts_args = array(
	'default'           => 'content-sidebar1',
	'customize'         => true,
	'post_meta'         => false,
	'post_types'        => array( 'post' ),
	'thumbnail'         => true,
);
$layouts_strings = array(
	'default'           => _x( 'Default', 'layout', 'nevertheless' ),
	'layout'            => _x( 'Layout', 'layout', 'nevertheless' ),
	'global_layout'     => _x( 'Global Layout', 'layout', 'nevertheless' ),
);
add_theme_support( 'tamatebako-layouts', $layouts, $layouts_args, $layouts_strings );
