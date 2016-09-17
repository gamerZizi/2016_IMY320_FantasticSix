<?php
/**
 * Compatibility settings and functions for Jetpack from Automattic
 * See http://jetpack.me/support/infinite-scroll/
 *
 * @package Bouquet
 */

/**
 * Add support for Infinite Scroll.
 */
function bouquet_infinite_scroll_init() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'content',
		'footer_widgets' => ( is_active_sidebar( 'sidebar-1' ) && jetpack_is_mobile() ),
	) );
}
add_action( 'after_setup_theme', 'bouquet_infinite_scroll_init' );