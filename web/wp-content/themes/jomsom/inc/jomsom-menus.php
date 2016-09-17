<?php
/**
 * The template for displaying custom menus
 *
 * @package Catch Themes
 * @subpackage Jomsom
 * @since Jomsom 0.1
 */

if ( ! function_exists( 'jomsom_primary_menu' ) ) :
/**
 * Shows the Primary Menu
 */
function jomsom_primary_menu() {
    ?>
	<nav id="site-navigation" class="jomsom-menus main-navigation nav-primary" role="navigation">
        <div class="container">
            <h1 class="screen-reader-text"><?php _e( 'Primary Menu', 'jomsom' ); ?></h1>
            <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'jomsom' ); ?>"><?php _e( 'Skip to content', 'jomsom' ); ?></a></div>
            <?php
                if ( has_nav_menu( 'primary' ) ) {
                    $jomsom_primary_menu_args = array(
                        'theme_location'    => 'primary',
                        'menu_class'        => 'menu jomsom-nav-menu',
                        'container'         => false
                    );
                    wp_nav_menu( $jomsom_primary_menu_args );
                }
                else {
                    wp_page_menu( array( 'menu_class'  => 'menu jomsom-nav-menu' ) );
                }
                ?>
    	</div><!-- .container -->
    </nav><!-- .nav-primary -->
    <?php
}
endif; //jomsom_primary_menu
add_action( 'jomsom_header', 'jomsom_primary_menu', 20 );


if ( ! function_exists( 'jomsom_add_page_menu_class' ) ) :
/**
 * Filters wp_page_menu to add menu class  for default page menu
 *
 */
function jomsom_add_page_menu_class( $ulclass ) {
  return preg_replace( '/<ul>/', '<ul class="menu page-menu-wrap">', $ulclass, 1 );
}
endif; //jomsom_add_page_menu_class
add_filter( 'wp_page_menu', 'jomsom_add_page_menu_class' );


if ( ! function_exists( 'jomsom_footer_menu' ) ) :
/**
 * Shows the Footer Menu
 */
function jomsom_footer_menu() {
	if ( has_nav_menu( 'footer' ) ) {
    ?>
	<nav id="nav-footer" class="jomsom-menus" role="navigation">
        <div class="container">
            <h1 class="screen-reader-text"><?php _e( 'Footer Menu', 'jomsom' ); ?></h1>
            <?php
                $jomsom_footer_menu_args = array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'menu jomsom-nav-menu',
                    'depth'          => 1
                );
                wp_nav_menu( $jomsom_footer_menu_args );
            ?>
    	</div><!-- .wrapper -->
    </nav><!-- .nav-footer -->
<?php
    }
}
endif; //jomsom_footer_menu
add_action( 'jomsom_footer', 'jomsom_footer_menu', 60 );


if ( ! function_exists( 'jomsom_mobile_menus' ) ) :
/**
 * This function loads Mobile Menus
 *
 * @get the data value from theme options
 * @uses jomsom_after action to add the code in the footer
 */
function jomsom_mobile_menus() {
    echo '<nav id="mobile-header-left-nav" class="mobile-menu" role="navigation">';
        $args = array(
            'theme_location'    => 'primary',
            'container'         => false,
            'items_wrap'        => '<ul id="header-left-nav" class="menu">%3$s</ul>'
        );

        if ( !has_nav_menu( 'primary' ) ) {
            wp_page_menu( array( 'menu_class'  => 'menu' ) );
        }
        else {
            wp_nav_menu( $args );
        }

    echo '</nav><!-- #mobile-header-left-nav -->';
}
endif; //jomsom_mobile_menus

add_action( 'jomsom_after', 'jomsom_mobile_menus', 20 );


if ( ! function_exists( 'jomsom_mobile_header_nav_anchor' ) ) :
/**
 * This function loads Mobile Menus Left Anchor
 *
 * @get the data value from theme options
 * @uses jomsom_header action to add in the Header
 */
function jomsom_mobile_header_nav_anchor() {
    if ( has_nav_menu( 'primary' ) ) {
        $classes = "mobile-menu-anchor primary-menu";
    }
    else {
        $classes = "mobile-menu-anchor page-menu";
    }
    ?>
    <div id="mobile-menu-bar" class="main-mobile-bar">
        <div class="container">
            <div id="mobile-header-left-menu" class="<?php echo $classes; ?>">
                <a href="#mobile-header-left-nav" id="header-left-menu" class="fa fa-bars">
                    <span class="mobile-menu-text screen-reader-text"><?php _e( 'Menu', 'jomsom' );?></span>
                </a>
            </div><!-- #mobile-header-menu -->
        </div><!-- .container -->
    </div><!-- #mobile-menu-bar -->
    <?php
}
endif; //jomsom_mobile_menus
add_action( 'jomsom_header', 'jomsom_mobile_header_nav_anchor', 40 );