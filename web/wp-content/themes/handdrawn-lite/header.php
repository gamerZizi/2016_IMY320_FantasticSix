<?php ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
		<div class="off-canvas-wrapper">
			<div class="off-canvas-wrapper-inner" data-off-canvas-wrapper>
				<?php if ( get_header_image() ) : ?>
				<div class="row">
					<div id="site-header">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img class="custom-header" src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
						</a>
					</div>
				</div>
				<?php endif; ?>
				<header id="masthead" class="site-header  ">
					<div class="main-header">
						<!-- off-canvas title bar for 'small' screen -->
						<div class="title-bar   row small-collapse medium-uncollapse" data-responsive-toggle="widemenu" data-hide-for="large">
							<div class="title-bar-left small-1 columns">
								<button class="icon-menu" type="button" data-open="offCanvasLeft"></button>
							</div>
							<div class="title-bar-center small-10 columns">
								<?php handdrawn_the_custom_logo(); ?>
								<?php if( is_home() ) { ?>
									<h1 class="site-title handy"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<?php } else { ?>
									<p class="site-title handy"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
								<?php } ?>
									<p class="site-description"><?php bloginfo( 'description' ); ?></p>
							</div>
							<div class="title-bar-right small-1 columns">
								<button class="icon-arrow-right" type="button" data-open="offCanvasRight"></button>
							</div>
						</div>
						<!-- off-canvas left menu -->
						<div class="off-canvas position-left" id="offCanvasLeft" data-off-canvas>
							<ul class="vertical menu handy" data-accordion-menu>
								<?php
								if ( has_nav_menu( 'primary' ) ) {
									wp_nav_menu( array(
										'menu' => 'primary',
										'theme_location' => 'primary',
										'menu_id' => 'off-canvas-left',
										'container'       => false,
										'depth'           => 3,
										'items_wrap' => '%3$s',
										'walker' => new Handdrawn_Off_Canvas_Menu(),
									));
								} ?>
							</ul>
							<div class="search-off-canv">
								<?php get_search_form(); ?>	
							</div>
						</div>
				
						<!-- off-canvas right menu -->
						<div class="off-canvas position-right" id="offCanvasRight" data-off-canvas data-position="right">
							<div class="row">
								<div class="small-12 columns">
									<?php if ( has_nav_menu( 'social' ) ) { ?>
									<div class="off-canvas-social">
										<p class=" handy off-canvas-title"><?php _e( 'Social channels', 'handdrawn-lite' ); ?></p>
										<?php
										wp_nav_menu( array(
											'theme_location' => 'social',
											'container'       => false,
											'menu_class'      => 'social-links',
											'depth'           => 1,
											'link_before'     => '<span class="screen-reader-text">',
											'link_after'      => '</span>',
											'fallback_cb'     => '',
										)); ?>
									</div>
									<?php } ?>
									<div class="sidebar widget-area">	
										<?php dynamic_sidebar( 'sidebar-1' ); ?>
									</div>
									</div>
								</div>
						</div>
				
						<!-- header for large-size devices -->
						<div class="row header-large show-for-large" >
							<div class="large-8 columns header-social-section float-right">
								<div class="row large-collapse">
									<div class="large-8 columns">
										<?php
										wp_nav_menu( array(
											'theme_location' => 'social',
											'container'       => false,
											'menu_class'      => 'social-links',
											'depth'           => 1,
											'link_before'     => '<span class="screen-reader-text">',
											'link_after'      => '</span>',
											'fallback_cb'     => '',
										)); ?>
									</div>
									
									<div class="large-4 columns header-search-form-container">
										<?php get_search_form(); ?>
									</div>
								</div>
								
							</div>
							<div class="large-4 columns logo float-left">
								<?php handdrawn_the_custom_logo(); ?>
								<?php if( is_home() ) { ?>
											<h1 class="site-title handy"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
										<?php } else { ?>
											<p class="site-title handy"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
									<?php } ?>
										<p class="site-description"><?php bloginfo( 'description' ); ?></p>
									
							</div><!-- .logo -->
							
							<div id="widemenu" class="top-bar medium-12 columns">
								<div class="top-bar-center">
									<ul class="dropdown menu handy" data-dropdown-menu>
									  <?php
										if ( has_nav_menu( 'primary' ) ) {
											wp_nav_menu( array(
												'menu' => 'primary',
												'theme_location' => 'primary',
												'menu_id' => 'primary',
												'container'       => false,
												'depth'           => 3,
												'items_wrap' => '%3$s',
												'walker' => new Handdrawn_Dropdown_Menu(),
											));
										} ?>
									</ul>
								</div>
							</div>
						</div><!-- end .row .header-large-->
					</div>
				</header>
				
				<!-- content goes in this container -->
				<div class="off-canvas-content" data-off-canvas-content>
