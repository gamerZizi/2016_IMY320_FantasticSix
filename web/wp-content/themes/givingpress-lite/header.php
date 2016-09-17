<?php
/**
 * The Header for our theme.
 * Displays all of the <head> section and everything up till <div id="wrap">
 *
 * @package GivingPress Lite
 * @since GivingPress Lite 1.0
 */

?><!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<!-- Mobile View -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php echo esc_url( bloginfo( 'pingback_url' ) ); ?>">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<!-- BEGIN #wrapper -->
<div id="wrapper">

<!-- BEGIN #top-info -->
<div id="top-info">

	<?php if ( has_nav_menu( "authentication-menu") || get_theme_mod( 'givingpress_lite_contact_email', 'info@givingpress.com' ) || get_theme_mod( 'givingpress_lite_contact_phone', '808.123.4567' ) || get_theme_mod( 'givingpress_lite_contact_address', '231 Front Street, Lahaina, HI 96761' ) ) { ?>

	<!-- BEGIN #contact-info -->
	<div id="contact-info">

		<!-- BEGIN .row -->
		<div class="row">

			<!-- BEGIN .content -->
			<div class="content">

				<div class="align-left">

				<?php if ( get_theme_mod( 'givingpress_lite_contact_address', '231 Front Street, Lahaina, HI 96761' ) ) { ?>
					<span class="contact-address"><i class="fa fa-map-marker"></i> <?php echo esc_html( get_theme_mod( 'givingpress_lite_contact_address', '231 Front Street, Lahaina, HI 96761' ) ); ?></span>
				<?php } ?>

				<?php if ( get_theme_mod( 'givingpress_lite_contact_email', 'info@givingpress.com' ) ) { ?>
					<span class="contact-email text-right"><i class="fa fa-envelope"></i> <a class="link-email" href="mailto:<?php echo esc_html( get_theme_mod( 'givingpress_lite_contact_email', 'info@givingpress.com' ) ); ?>" target="_blank"><?php echo esc_html( get_theme_mod( 'givingpress_lite_contact_email', 'info@givingpress.com' ) ); ?></a></span>
				<?php } ?>

				<?php if ( get_theme_mod( 'givingpress_lite_contact_phone', '808.123.4567' ) ) { ?>
					<span class="contact-phone text-right"><i class="fa fa-phone"></i> <?php echo esc_html( get_theme_mod( 'givingpress_lite_contact_phone', '808.123.4567' ) ); ?></span>
				<?php } ?>

				</div>

				<?php if ( '1' == get_theme_mod( 'givingpress_lite_display_header_search', '1' ) || has_nav_menu( "authentication-menu") ) { ?>

				<div class="align-right">
					<?php if ( '1' == get_theme_mod( 'givingpress_lite_display_header_search', '1' ) ) : ?>
						<div class="header-search"><i class="fa fa-search"></i> <?php get_template_part( 'searchform' ); ?></div>
					<?php else: ?>						
						<!-- BEGIN Authentication Navigation -->
						<div id="auth-navigation" class="navigation-main">

							<?php wp_nav_menu( array(
								'theme_location'		=> 'authentication-menu',
								'title_li' 				=> '',
								'depth' 				=> 4,
								'fallback_cb'			=> 'wp_page_menu',
								'container_class'		=> '',
								'menu_class'			=> 'menu',
								)
							); ?>

						<!-- END Authentication Navigation -->
						</div>
					<?php endif; ?>
				</div>

				<?php } ?>

			<!-- END .content -->
			</div>

		<!-- END .row -->
		</div>

	<!-- END #contact-info -->
	</div>

	<?php } ?>

	<!-- BEGIN #top-nav -->
	<div id="top-nav">

		<!-- BEGIN .row -->
		<div class="row">

			<!-- BEGIN .content -->
			<div class="content no-bg">

			<?php givingpress_lite_custom_logo(); ?>

			<?php if ( has_nav_menu( 'main-menu' ) ) { ?>

				<!-- BEGIN #navigation -->
				<nav id="navigation" class="navigation-main vertical-center">

					<button class="menu-toggle"><i class="fa fa-bars"></i></button>

					<?php wp_nav_menu( array(
						'theme_location' 		=> 'main-menu',
						'title_li' 					=> '',
						'depth' 						=> 4,
						'fallback_cb'     	=> 'wp_page_menu',
						'container_class' 	=> '',
						'menu_class'      	=> 'menu',
						)
					); ?>

				<!-- END #navigation -->
				</nav>

			<?php } elseif ( current_user_can( 'publish_posts' ) ) { ?>

				<!-- BEGIN #navigation -->
				<nav id="navigation" class="navigation-main vertical-center">

					<p class="instruction"><?php printf( wp_kses( __( 'Create a Custom Navigation Menu <a href="%1$s">here</a>.', 'givingpress-lite' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'nav-menus.php' ) ) ); ?></p>

				<!-- END #navigation -->
				</nav>

			<?php } ?>

			<!-- END .content -->
			</div>

		<!-- END .row -->
		</div>

	<!-- END #top-nav -->
	</div>

<!-- END #top-info -->
</div>

<?php $header_image = get_header_image(); ?>

<?php if ( ! is_page() || is_home() || is_page() && ! has_post_thumbnail() ) { ?>
<?php if ( 'blank' != get_theme_mod( 'header_textcolor' ) || ! empty( $header_image ) ) { ?>

<!-- BEGIN #header -->
<div id="header">

	<?php if ( ! empty( $header_image ) ) { ?>

	<!-- BEGIN .custom-header -->
	<div class="custom-header" <?php if ( ! empty( $header_image ) ) { ?> style="background-image: url(<?php header_image(); ?>);"<?php } ?>>

	<?php } ?>

	<!-- BEGIN #site-info -->
	<div id="site-info">

		<!-- BEGIN .content -->
		<div class="content">

			<div id="header-content" <?php if ( ! empty( $header_image ) ) { ?>class="vertical-center"<?php } ?>>

				<?php if ( 'blank' != get_theme_mod( 'header_textcolor' ) ) { ?>

					<!-- BEGIN #masthead -->
					<div id="masthead">

						<h2 class="site-description">
							<?php $heading_info = (isset(get_post_custom_values( "header_information" )[0]) && !empty(get_post_custom_values( "header_information" )[0])) ? get_post_custom_values( "header_information" )[0] : get_bloginfo( 'description' ); ?>
							<?php echo html_entity_decode( $heading_info ); ?>
						</h2>

					<!-- END #masthead -->
					</div>

				<?php } ?>

			</div>

			<?php if ( ! empty( $header_image ) ) { ?>

			<img class="hide-img" src="<?php header_image(); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php echo esc_attr( get_bloginfo() ); ?>" />

			<?php } ?>

		<!-- END .content -->
		</div>

	<!-- END #site-info -->
	</div>

	<?php if ( ! empty( $header_image ) ) { ?>

	<!-- BEGIN .custom-header -->
	</div>

	<?php } ?>

<!-- END #header -->
</div>

<?php } ?>
<?php } ?>

<!-- BEGIN .container -->
<div class="container">
