<?php
/**
 * The default template for displaying header
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

	/**
	 * jomsom_doctype hook
	 *
	 * @hooked jomsom_doctype -  10
	 *
	 */
	do_action( 'jomsom_doctype' );?>

<head>
<?php
	/**
	 * jomsom_before_wp_head hook
	 *
	 * @hooked jomsom_head -  10
	 *
	 */
	do_action( 'jomsom_before_wp_head' );

	wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
	/**
     * jomsom_before_header hook
     *
     */
    do_action( 'jomsom_before' );

	/**
	 * jomsom_header hook
	 *
	 * @hooked jomsom_header_start -  10
	 * @hooked jomsom_primary_menu - 20
	 * @hooked jomsom_header_top - 30
	 * @hooked jomsom_mobile_header_nav_anchor - 40
	 * @hooked jomsom_site_branding_start- 60
	 * @hooked jomsom_site_branding - 70
	 * @hooked jomsom_site_branding_end- 90
	 * @hooked jomsom_header_end - 100
	 *
	 */
	do_action( 'jomsom_header' );

	/**
     * jomsom_after_header hook
     *
     * @hooked jomsom_featured_overall_image
     * @hooked jomsom_primary_menu - 20
     * @hooked jomsom_add_breadcrumb - 50
     */
	do_action( 'jomsom_after_header' );

	/**
	 * jomsom_before_content hook
	 *
	 * @hooked jomsom_slider - 10
	 */
	do_action( 'jomsom_before_content' );

	/**
     * jomsom_main hook
     *
     *  @hooked jomsom_content_start - 10
     *  @hooked jomsom_add_breadcrumb - 20
     *
     */
	do_action( 'jomsom_content' );