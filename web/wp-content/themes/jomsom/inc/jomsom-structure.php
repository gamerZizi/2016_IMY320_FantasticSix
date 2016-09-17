<?php
/**
 * The template for Managing Theme Structure
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

if ( ! function_exists( 'jomsom_doctype' ) ) :
	/**
	 * Doctype Declaration
	 *
	 * @since Jomsom 0.1
	 *
	 */
	function jomsom_doctype() {
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<?php
	}
endif;
add_action( 'jomsom_doctype', 'jomsom_doctype', 10 );


if ( ! function_exists( 'jomsom_head' ) ) :
	/**
	 * Header Codes
	 *
	 * @since Jomsom 0.1
	 *
	 */
	function jomsom_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php
	}
endif;
add_action( 'jomsom_before_wp_head', 'jomsom_head', 10 );


if ( ! function_exists( 'jomsom_header_start' ) ) :
	/**
	 * Start Header id #masthead and class .wrapper
	 *
	 * @since Jomsom 0.1
	 *
	 */
	function jomsom_header_start() {
		?>
		<header id="masthead" class="site-header" role="banner">
		<?php
	}
endif;
add_action( 'jomsom_header', 'jomsom_header_start', 10 );


if ( ! function_exists( 'jomsom_site_branding_start' ) ) :
	/**
	 * Doctype Declaration
	 *
	 * @since Jomsom 0.1
	 *
	 */
	function jomsom_site_branding_start() {
		?>
		<div id="site-branding-wrap">
			<div class="container">
		<?php
	}
endif;
add_action( 'jomsom_header', 'jomsom_site_branding_start', 60 );


if ( ! function_exists( 'jomsom_site_branding_end' ) ) :
/**
 * End footer id #colophon
 *
 * @since Jomsom 0.1
 */
function jomsom_site_branding_end() {
	?>
		</div><!-- .container -->
	</div><!-- #site-branding-wrap -->
	<?php
}
endif;
add_action( 'jomsom_header', 'jomsom_site_branding_end', 90 );


if ( ! function_exists( 'jomsom_header_end' ) ) :
	/**
	 * End Header id #masthead and class .wrapper
	 *
	 * @since Jomsom 0.1
	 *
	 */
	function jomsom_header_end() {
		?>
		</header><!-- #masthead -->
		<?php
	}
endif;
add_action( 'jomsom_header', 'jomsom_header_end', 100 );


if ( ! function_exists( 'jomsom_content_start' ) ) :
	/**
	 * Start div id #content and class .wrapper
	 *
	 * @since Jomsom 0.1
	 *
	 */
	function jomsom_content_start() {
		?>
		<div id="content" class="site-content">
			<div class="container">
	<?php
	}
endif;
add_action('jomsom_content', 'jomsom_content_start', 10 );

if ( ! function_exists( 'jomsom_content_end' ) ) :
	/**
	 * End div id #content and class .wrapper
	 *
	 * @since Jomsom 0.1
	 */
	function jomsom_content_end() {
		?>
			</div><!-- .container -->
	    </div><!-- #content -->
		<?php
	}

endif;
add_action( 'jomsom_after_content', 'jomsom_content_end', 30 );


if ( ! function_exists( 'jomsom_footer_content_start' ) ) :
/**
 * Start footer id #colophon
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_content_start() {
	?>
	<footer id="colophon" class="site-footer" role="contentinfo">
    <?php
}
endif;
add_action('jomsom_footer', 'jomsom_footer_content_start', 30 );


if ( ! function_exists( 'jomsom_footer_sidebar' ) ) :
/**
 * Footer Sidebar
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_sidebar() {
	//footer-t div inside the sidebar
	get_sidebar( 'footer' );
}
endif;
add_action( 'jomsom_footer', 'jomsom_footer_sidebar', 40 );


if ( ! function_exists( 'jomsom_footer_b_start' ) ) :
/**
 * Start Footer b containing footer menu, social icons and copyright info
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_b_start() {
	?>
	<div class="footer-b">
		<div class="container">
	<?php
}
endif;
add_action( 'jomsom_footer', 'jomsom_footer_b_start', 50 );


if ( ! function_exists( 'jomsom_footer_social_icon' ) ) :
/**
 * Add Social Icons to footer
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_social_icon() {
	$options = jomsom_get_theme_options();

	if ( $options['disable_social_footer'] ){
		//Bail early if social icons are disabled in footer
		return;
	}

	if ( '' != ( $jomsom_social_icons = jomsom_get_social_icons() ) ) {
		echo $jomsom_social_icons;
	}
}
endif;
add_action( 'jomsom_footer', 'jomsom_footer_social_icon', 70 );


if ( ! function_exists( 'jomsom_footer_b_end' ) ) :
/**
 * End Footer b containing footer menu, social icons and copyright info
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_b_end() {
	?>
		</div><!-- .container -->
	</div><!-- .footer-b -->
	<?php
}
endif;
add_action( 'jomsom_footer', 'jomsom_footer_b_end', 90 );


if ( ! function_exists( 'jomsom_footer_content_end' ) ) :
/**
 * End footer id #colophon
 *
 * @since Jomsom 0.1
 */
function jomsom_footer_content_end() {
	?>
	</footer><!-- #colophon -->
	<?php
}
endif;
add_action( 'jomsom_footer', 'jomsom_footer_content_end', 110 );