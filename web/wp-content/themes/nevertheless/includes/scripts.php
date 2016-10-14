<?php
/**
 * Scripts Setup
**/

/* === BODY CLASSES === */

add_filter( 'body_class', 'nevertheless_scripts_body_class' );

/**
 * Scripts Body Class Helper
 */
function nevertheless_scripts_body_class( $classes ){
	$classes[] = 'theme-genericons-active';
	return $classes;
}

/* === EDITOR STYLE === */

$editor_css = array(
	'assets/css/editor.css',
);
add_editor_style( $editor_css );


/* === ENQUEUE SCRIPTS === */

add_action( 'wp_enqueue_scripts', 'nevertheless_enqueue_scripts' );

/**
 * Enqueue Scripts
 */
function nevertheless_enqueue_scripts(){
	global $tamatebako;
	$name = $tamatebako->name;
	$child = $tamatebako->child;

	/* == JS == */
	wp_enqueue_script( "fitvids" );
	wp_enqueue_script( "{$name}-script" );

	/* == CSS == */
	wp_enqueue_style( "genericons" );
	wp_enqueue_style( "{$name}-style" ); /* main css. */

	if( is_child_theme() ) wp_enqueue_style( "{$child}-style" ); /* child theme css. */
	if( tamatebako_is_debug() ) wp_enqueue_style( "{$name}-debug" ); /* media queries debug. */
}


/* === REGISTER SCRIPTS === */

add_action( 'wp_enqueue_scripts', 'nevertheless_register_scripts', 1 );

/**
 * Register Scripts
 */
function nevertheless_register_scripts(){
	global $tamatebako;
	$name = $tamatebako->name;

	/* FitVids (JS) */
	wp_register_script( "fitvids", tamatebako_theme_file( "assets/js/jquery.fitvids", "js" ) , array( 'jquery' ), '1.1.0', true );

	/* Theme Custom (JS) */
	wp_register_script( "{$name}-script", tamatebako_theme_file( "assets/js/jquery.theme", "js" ), array( 'jquery', 'fitvids' ), tamatebako_theme_version(), true );

	/* === CSS === */

	/* Genericons */
	wp_register_style( "genericons", tamatebako_theme_file( "assets/genericons/genericons", "css" ), array(), '3.3.1', 'all' );

	/* Theme Debug */
	wp_register_style( "{$name}-debug", tamatebako_theme_file( "assets/css/base/debug", "css" ), array() );

}
