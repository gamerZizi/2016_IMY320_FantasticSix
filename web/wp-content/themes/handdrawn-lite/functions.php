<?php
/*
 * Enqueue style and scripts
 */
function handdrawn_theme_styles() {
		wp_enqueue_style( 'handdrawn_google_fonts', 'http://fonts.googleapis.com/css?family=Caveat|Source+Sans+Pro:700,300' );
		wp_enqueue_style( 'foundation', get_template_directory_uri() . '/css/foundation.min.css' );
		wp_enqueue_style( 'handdrawn_motion', get_template_directory_uri() . '/css/motion-ui.css' );
		wp_enqueue_style( 'handdrawn_main_css', get_stylesheet_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'handdrawn_theme_styles' );

function handdrawn_theme_scripts() {
		wp_enqueue_script( 'foundation', get_template_directory_uri() . '/js/foundation.min.js', array('jquery'), false, true );
		wp_enqueue_script( 'foundation-active', get_template_directory_uri() . '/js/foundation-active.js', array('jquery', 'foundation'), false, true );
        wp_enqueue_script( 'handdrawn_scripts', get_template_directory_uri() . '/js/handdrawn-scripts.js', array('jquery'), '', true );
		if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'handdrawn_theme_scripts' );

function handdrawn_setup(){
     
	 /*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'handdrawn-lite', get_template_directory() . '/languages' );
    
    register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'handdrawn-lite' ),
        'social' => __( 'Social Menu', 'handdrawn-lite' ),
	) );
	$args = array(
		'default-color' => 'ffffff',
		);
    add_theme_support( 'custom-background', $args );
    add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
		$args = array(
		'flex-width'    => true,
		'width'         => 1100,
		'flex-height'   => true,
		'header-text'   => false,
		'height'        => 126,
		'default-image' => '',
	);
	add_theme_support( 'custom-header', $args );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );
    add_theme_support( 'post-formats', array(
        'aside', 
    ) );
	add_theme_support( 'custom-logo', array(
	'height'      => 100,
	'width'       => 400,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
) );
	add_image_size( 'handdrawn-size', 703, 527 );

}
add_action( 'after_setup_theme', 'handdrawn_setup' );

function handdrawn_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'handdrawn_content_width', 1100 );
}
add_action( 'after_setup_theme', 'handdrawn_content_width', 0 );


/**
* Custom walker class for this theme.
*/
require_once ( get_template_directory().'/inc/custom-walker.php');

/**
* Custom template tags for this theme.
*/

require( get_template_directory().'/inc/template-tags.php');

/**
* Register widgetized area and update sidebar with default widgets
*/
function handdrawn_widgets_init() {
    
    register_sidebar( array(
        'name' => __( 'Right Sidebar Widget Area', 'handdrawn-lite' ),
        'id' => 'sidebar-1',
        'before_widget' => '<aside class="widget %2$s  "><div class="widget-container">',
        'after_widget' => '</div></aside><div class="clear"></div>',
        'before_title' => '<div class="widget-title"><span class="widget-icon-left "></span><h3 class="handy ">',
        'after_title' => '</h3><span class="widget-icon-right "></span></div>',
    ) );
}

add_action( 'widgets_init', 'handdrawn_widgets_init' );

if ( ! function_exists( 'handdrawn_the_custom_logo' ) ) :
/**
 * Displays the optional custom logo.
 *
 * Does nothing if the custom logo is not available.
 */
function handdrawn_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}
endif;


/*
 * Adds editor style 
 *
 */

function handdrawn_add_editor_styles() {
    $font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,700,300italic' );
    add_editor_style( $font_url );
    add_editor_style( 'handdrawn-editor-style.css' );
}
add_action( 'admin_init', 'handdrawn_add_editor_styles' );


/**
 * Function to avoid page scroll clicking on "Read More"
 */
if ( ! function_exists( 'handdrawn_remove_more_link_scroll' ) ) {
	function handdrawn_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'handdrawn_remove_more_link_scroll' );

/**
 * Function to customize the "Read More" link
 */

add_filter( 'the_content_more_link', 'handdrawn_modify_read_more_link' );
if ( ! function_exists( 'handdrawn_modify_read_more_link' ) ) {
	function handdrawn_modify_read_more_link() {
		return '<a class="more-link" href="' . esc_url( get_permalink() ). '">'. __( "Read More", 'handdrawn-lite' ).'</a>';
	}
}

/*
 * Function to avoid the archive name from the archive title. For example "Nature" instead of "Category: Nature"
 */

add_filter( 'get_the_archive_title', 'handdrawn_remove_name_from_archive_title' );

if ( ! function_exists( 'handdrawn_remove_name_from_archive_title' ) ) {
	function handdrawn_remove_name_from_archive_title( $title ) {
    		if ( is_category() ) {
        		$title = str_replace( 'Category:', '', $title );
    		} elseif ( is_tag() ) {
        		$title = str_replace( 'Tag:', '', $title );
    		} elseif ( is_author() ) {
        		$title = str_replace( 'Author:', '', $title );
    		} elseif ( is_year() ) {
        		$title = str_replace( 'Year:', '', $title );
    		} elseif ( is_month() ) {
        		$title = str_replace( 'Month:', '', $title );
    		} elseif ( is_day() ) {
        		$title = str_replace( 'Day:', '', $title );
    		}
    		return $title;
	}
}

/**
 * Generate custom search form
 *
 */
function handdrawn_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
				<div class="row small-collapse">
					<div class="small-9 columns">
							<label class="screen-reader-text" for="s">' . __( 'Search for:', 'handdrawn-lite' ) . '</label>
							<input type="text" value="' . esc_attr( get_search_query() ) . '" name="s" id="s" />
							<div class="svg-element">
								<div class="svg-container svg-search-form">
										<svg version="1.1" preserveAspectRatio="xMinYMin meet" viewBox="0 0 150 6.7" class="svg-content">
												<path class="search-fill" d="M2 3.8c26 0.5 51.7 0.4 77.6 0.5 12.6 0.1 25.2 0.1 37.8 0.2 6.6 0 13.2 0.1 19.9 0.1 4.2 0 10.3 1.3 12.6-3.1 0.2-0.4-0.4-1-0.7-0.7 -2.9 4.1-11.4 2.4-15.6 2.4 -6.3 0-12.6 0-18.9-0.1 -12.6 0-25.2-0.1-37.9-0.1C51.6 3 26.5 2.7 1.3 2.9 1 2.9 2 3.8 2 3.8z"/>
										</svg>
								</div>
							</div>
					</div>
					<div class="small-3 columns">
							<button type="submit" id="searchsubmit" title="';
		$form .= __( 'Search', 'handdrawn-lite' );
		$form .= '"><span class="icon-search"></span></button>
					</div>
				</div>
		</form>';
 
    return $form;
}
add_filter( 'get_search_form', 'handdrawn_search_form' );


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'handdrawn_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 */
function handdrawn_register_required_plugins() {
	
	$plugins = array(	
		array(
				'name'      => 'Advanced Custom Fields',
				'slug'      => 'advanced-custom-fields',
				'required'  => false,
				'force_activation'   => true,
		),
		array(
				'name'               => 'Handdrawn Lite Features', 
				'slug'               => 'handdrawn-lite-features', 
				'source'             => 'http://www.svgthemes.com/wp-content/plugins/handdrawn-lite-features.zip', 
				'required'           => false, 
				'version'            => '1.0', 
				'force_activation'   => true, 
				'force_deactivation' => true, 
				),
	);

	$config = array(
		'id'           => 'handdrawn-tgmpa',
		'menu'         => 'handdrawn-install-plugins', 
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => __("You need to install this plugin to use all the features included in the theme.", "handdrawn-lite"),                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
        );
	tgmpa( $plugins, $config );
}?>
