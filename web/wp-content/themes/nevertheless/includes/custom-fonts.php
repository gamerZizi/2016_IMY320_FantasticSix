<?php
/**
 * Custom Fonts Options
 * Google fonts also enqueued using this feature.
**/

/* Customizer setting configuration */
$fonts_config = array(
	'font_site_title' => array(
		'label' => _x( 'Site Title Font', 'customizer', 'nevertheless' ),
		'description' => _x( 'You can select your preferred font for your site title below.', 'customizer', 'nevertheless' ),
		'target' => '#site-title',
		'fonts' => array( 'websafe', 'heading', 'base' ),
		'default' => 'Rancho',
	),
	'font_post_title' => array(
		'label' => _x( 'Post Title Font', 'customizer', 'nevertheless' ),
		'target' => '#content .entry-title',
		'fonts' => array( 'websafe', 'heading', 'base' ),
		'default' => 'Open Sans',
	),
	'font_widget_title' => array(
		'label' => _x( 'Widget Title', 'customizer', 'nevertheless' ),
		'target' => '.widget-title',
		'fonts' => array( 'websafe', 'heading', 'base' ),
		'default' => 'Open Sans',
	),
	'font_base' => array(
		'label' => _x( 'Base Font', 'customizer', 'nevertheless' ),
		'target' => 'body.wordpress,body#tinymce',
		'fonts' => array( 'websafe', 'base' ),
		'default' => 'Open Sans',
	),
);

/* Additional settings for custom font features */
$fonts_settings = array(
	'editor_styles' => array(
		'font_base',
	),
	/**
	 * Translators: to add an additional font character subset specific to your language
	 * translate this to 'greek', 'cyrillic', or 'vietnamese'. Do not translate into your own language.
	 * Note: availability of the subset depends on fonts selected.
	 */
	'font_subset' => _x( 'no-subset', 'Google Font Subset: add new subset( greek, cyrillic, vietnamese )', 'nevertheless' ),
	'allowed_weight' => array( '300', '300italic', '400', '400italic', '700', '700italic', '800', '800italic' ),
);

/* Additional strings used in custom font feature. */
$fonts_strings = array(
	'fonts' => _x( 'Fonts', 'customizer', 'nevertheless' ),
);

add_theme_support( 'tamatebako-custom-fonts', $fonts_config, $fonts_settings, $fonts_strings );
