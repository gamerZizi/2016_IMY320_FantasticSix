<header role="banner" id="header" style="background-image:url('<?php header_image(); ?>');">

	<div id="branding">

		<?php if( current_theme_supports( 'custom-logo' ) ) the_custom_logo(); // custom logo ?>

		<?php if( is_front_page() && is_home() ){ ?>

			<h1 id="site-title" class="site-title"><a rel="home" href="<?php echo esc_url( user_trailingslashit( home_url() ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>

		<?php } else { ?>

			<p id="site-title" class="site-title"><a rel="home" href="<?php echo esc_url( user_trailingslashit( home_url() ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>

		<?php } ?>

		<p id="site-description" class="site-description"><?php bloginfo( 'description' ); ?></p>

	</div><!-- #branding -->

	<?php get_template_part( 'menu/social-links' ); ?>

</header><!-- #header-->